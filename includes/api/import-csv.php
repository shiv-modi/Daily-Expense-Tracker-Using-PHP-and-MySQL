<?php
session_start();
header('Content-Type: application/json');
include_once('../database.php');

// Helper function to convert various date formats to YYYY-MM-DD
function convertDateFormat($dateStr) {
    $dateStr = trim($dateStr);
    
    // Try to parse using strtotime which handles many formats
    $timestamp = strtotime($dateStr);
    if ($timestamp !== false) {
        return date('Y-m-d', $timestamp);
    }
    
    // Fallback: try manual parsing for common formats
    if (strpos($dateStr, '/') !== false) {
        $parts = explode('/', $dateStr);
        if (count($parts) === 3) {
            // Clean up parts
            $parts = array_map('trim', $parts);
            
            // Determine format
            if (strlen($parts[0]) === 4) {
                // YYYY/MM/DD
                return $parts[0] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[2], 2, '0', STR_PAD_LEFT);
            } else if (strlen($parts[2]) === 4) {
                // DD/MM/YYYY or MM/DD/YYYY - assume DD/MM/YYYY for international format
                return $parts[2] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            }
        }
    } elseif (strpos($dateStr, '-') !== false) {
        $parts = explode('-', $dateStr);
        if (count($parts) === 3) {
            $parts = array_map('trim', $parts);
            
            if (strlen($parts[0]) === 4) {
                // YYYY-MM-DD
                return $parts[0] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[2], 2, '0', STR_PAD_LEFT);
            } else if (strlen($parts[2]) === 4) {
                // DD-MM-YYYY
                return $parts[2] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            }
        }
    }
    
    return '';
}

// Helper function to validate date format
function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['detsuid'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $userid = $_SESSION['detsuid'];

    if (!isset($_FILES['csv_file']) && !isset($_FILES['csv-file'])) {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
        exit;
    }

    $fileKey = isset($_FILES['csv_file']) ? 'csv_file' : 'csv-file';
    
    if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Upload error: ' . $_FILES[$fileKey]['error']]);
        exit;
    }

    $file = $_FILES[$fileKey]['tmp_name'];
    $handle = fopen($file, 'r');
    
    if ($handle === false) {
        echo json_encode(['status' => 'error', 'message' => 'Could not open file']);
        exit;
    }

    $header = fgetcsv($handle);
    if ($header === false) {
        fclose($handle);
        echo json_encode(['status' => 'error', 'message' => 'Empty file']);
        exit;
    }

    $header = array_map('strtolower', array_map('trim', $header));
    
    $dateIdx = array_search('date', $header);
    $particularsIdx = array_search('particulars', $header);
    $expenseIdx = array_search('expense', $header);
    $incomeIdx = array_search('income', $header);
    $categoryIdx = array_search('category', $header);
    $isLendingIdx = array_search('is_lending', $header);

    if ($dateIdx === false || $particularsIdx === false) {
        fclose($handle);
        echo json_encode(['status' => 'error', 'message' => 'Missing required columns: Date, Particulars']);
        exit;
    }

    $imported = 0;
    $errors = [];

    while (($row = fgetcsv($handle)) !== false) {
        try {
            $date = trim($row[$dateIdx] ?? '');
            $particulars = trim($row[$particularsIdx] ?? '');
            $expense = floatval(trim($row[$expenseIdx] ?? 0));
            $income = floatval(trim($row[$incomeIdx] ?? 0));
            $categoryName = trim($row[$categoryIdx] ?? 'General');
            $isLending = strtolower(trim($row[$isLendingIdx] ?? 'no'));

            if (empty($date)) continue;

            // Convert date to YYYY-MM-DD format
            $date = convertDateFormat($date);
            
            if (empty($date) || !isValidDate($date)) {
                $errors[] = "Invalid date format: {$row[$dateIdx]}";
                continue;
            }

            if ($isLending === 'yes' || $isLending === '1' || $isLending === 'true') {
                $status = $expense > 0 ? 'pending' : 'received';
                $amount = $expense > 0 ? $expense : $income;
                
                $stmt = $db->prepare("INSERT INTO lending (UserId, name, date_of_lending, amount, description, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issdss", $userid, $particulars, $date, $amount, $particulars, $status);
                $stmt->execute();
                $stmt->close();
            } else {
                $mode = $expense > 0 ? 'expense' : 'income';
                
                $catStmt = $db->prepare("SELECT CategoryId FROM tblcategory WHERE UserId = ? AND LOWER(CategoryName) = LOWER(?) AND Mode = ?");
                $catStmt->bind_param("iss", $userid, $categoryName, $mode);
                $catStmt->execute();
                $catResult = $catStmt->get_result();
                $catRow = $catResult->fetch_assoc();
                $catStmt->close();
                
                if (!$catRow) {
                    $insertCat = $db->prepare("INSERT INTO tblcategory (CategoryName, Mode, UserId) VALUES (?, ?, ?)");
                    $insertCat->bind_param("ssi", $categoryName, $mode, $userid);
                    $insertCat->execute();
                    $catId = $insertCat->insert_id;
                    $insertCat->close();
                } else {
                    $catId = $catRow['CategoryId'];
                }
                
                if ($expense > 0) {
                    $stmt = $db->prepare("INSERT INTO tblexpense (UserId, ExpenseDate, CategoryId, category, ExpenseCost, Description) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isisss", $userid, $date, $catId, $categoryName, $expense, $particulars);
                    $stmt->execute();
                    $stmt->close();
                } else if ($income > 0) {
                    $stmt = $db->prepare("INSERT INTO tblincome (UserId, IncomeDate, CategoryId, category, IncomeAmount, Description) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isisss", $userid, $date, $catId, $categoryName, $income, $particulars);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            $imported++;
        } catch (Exception $e) {
            $errors[] = "Row error: " . $e->getMessage();
        }
    }

    fclose($handle);

    if ($imported > 0) {
        $message = "$imported records imported successfully";
        if (count($errors) > 0) {
            $message .= ". " . count($errors) . " errors occurred.";
        }
        echo json_encode(['status' => 'success', 'message' => $message, 'imported' => $imported, 'errors' => $errors]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No records imported', 'errors' => $errors]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
