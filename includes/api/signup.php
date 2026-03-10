<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    return;
}

include_once('../database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $name = trim($input['name'] ?? $_POST['name'] ?? '');
    $email = trim($input['email'] ?? $_POST['email'] ?? '');
    $phone = trim($input['phone'] ?? $_POST['phone'] ?? '');
    $password = $input['password'] ?? $_POST['password'] ?? '';
    $confirm_password = $input['confirm_password'] ?? $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
        return;
    }

    if (strlen($phone) < 10) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid Phone Number']);
        return;
    }

    if ($password !== $confirm_password) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
        return;
    }

    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long']);
        return;
    }

    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Email is already registered']);
        return;
    }
    $stmt->close();

    // Check if name already exists (since there's a unique key on name in DB)
    $stmt = $db->prepare("SELECT id FROM users WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Username is already taken']);
        return;
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $verification_code = md5(rand()); // 32 characters

    $stmt = $db->prepare("INSERT INTO users (name, email, phone, password, verification_code, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $verification_code);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'message' => 'Signup successful! You can now login.'
        ]);

        // send verification email
        $to = $email;
        $subject = "Signup Verification";
        $message = "Click the link to verify your email address: http://example.com/verify.php?code=$verification_code";
        $headers = "From: no-reply@example.com";
        mail($to, $subject, $message, $headers);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to create account']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>