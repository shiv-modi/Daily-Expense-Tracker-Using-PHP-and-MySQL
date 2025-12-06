<?php
session_start();
error_reporting(0);
include('database.php');
if (strlen($_SESSION['detsuid']) == 0) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>

    <style>
        .container {
            background-color: #f2f2f2;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #aaa;
            padding: 20px;
            margin-top: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .invalid-feedback {
            color: red;
            font-size: 12px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        
        /* Table Styles */
        .table th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #495057;
        }
        
        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-album'></i>
            <span class="logo_name">Expenditure</span>
        </div>
        <ul class="nav-links">
            <li><a href="home.php"><i class='bx bx-grid-alt'></i><span class="links_name">Dashboard</span></a></li>
            <li><a href="add-expenses.php"><i class='bx bx-box'></i><span class="links_name">Expenses</span></a></li>
            <li><a href="add-income.php"><i class='bx bx-box'></i><span class="links_name">Income</span></a></li>
            <li><a href="manage-transaction.php" class="active"><i class='bx bx-list-ul'></i><span class="links_name">Manage List</span></a></li>
            <li><a href="lending.php"><i class='bx bx-money'></i><span class="links_name">lending</span></a></li>
            <li><a href="manage-lending.php"><i class='bx bx-coin-stack'></i><span class="links_name">Manage lending</span></a></li>
            <li><a href="analytics.php"><i class='bx bx-pie-chart-alt-2'></i><span class="links_name">Analytics</span></a></li>
            <li><a href="report.php"><i class="bx bx-file"></i><span class="links_name">Report</span></a></li>
            <li><a href="user_profile.php"><i class='bx bx-cog'></i><span class="links_name">Setting</span></a></li>
            <li class="log_out"><a href="logout.php"><i class='bx bx-log-out'></i><span class="links_name">Log out</span></a></li>
        </ul>
    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Expenditure</span>
            </div>
            <div class="search-box">
                <input type="text" id="search-input" class="form-control form-control-sm mx-2" placeholder="Search...">
                <i class='bx bx-search'></i>
            </div>
            
            <?php
            $uid = $_SESSION['detsuid'];
            $ret = mysqli_query($db, "select name from users where id='$uid'");
            $row = mysqli_fetch_array($ret);
            $name = $row['name'];
            ?>
            
            <div class="profile-details">
                <img src="images/maex.png" alt="">
                <span class="admin_name"><?php echo $name; ?></span>
                <i class='bx bx-chevron-down' id='profile-options-toggle'></i>
                <ul class="profile-options" id='profile-options'>
                    <li><a href="user_profile.php"><i class="fas fa-user-circle"></i> User Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="home-content">
            <div class="overview-boxes">
                <div class="col-sm-10 col-sm-offset-3 col-lg-12 col-lg-offset-2 main">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <h4 class="card-title mb-0">Manage Transactions</h4>
                                            </div>
                                            <div class="col-md-7 text-right">
                                                <a href="api/export-csv.php?type=all" class="btn btn-success btn-sm mr-1">
                                                    <i class="fas fa-download"></i> Export CSV
                                                </a>
                                                <button type="button" class="btn btn-info btn-sm mr-1" data-toggle="modal" data-target="#import-csv-modal">
                                                    <i class="fas fa-upload"></i> Import CSV
                                                </button>
                                                <label class="mb-0 ml-2">Show
                                                    <select class="form-control-sm mx-1" id="select-entries">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                    entries
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="col-md-12 mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="10%">Type</th>
                                                            <th width="15%">Category</th>
                                                            <th width="15%">Amount</th>
                                                            <th width="25%">Description</th>
                                                            <th width="15%">Date</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $userid = $_SESSION['detsuid'];
                                                        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
                                                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                                        $offset = ($page - 1) * $limit;

                                                        // Union Query for Income and Expense
                                                        $query = "
                                                            SELECT ID, 'Expense' as Type, Category, ExpenseCost as Amount, Description, ExpenseDate as TransactionDate 
                                                            FROM tblexpense WHERE userid='$userid'
                                                            UNION ALL
                                                            SELECT ID, 'Income' as Type, Category, IncomeAmount as Amount, Description, IncomeDate as TransactionDate 
                                                            FROM tblincome WHERE userid='$userid'
                                                            ORDER BY TransactionDate DESC
                                                            LIMIT $limit OFFSET $offset
                                                        ";
                                                        
                                                        $ret = mysqli_query($db, $query);
                                                        
                                                        if (mysqli_num_rows($ret) > 0) {
                                                            $cnt = $offset + 1;
                                                            while ($row = mysqli_fetch_array($ret)) {
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $cnt; ?></td>
                                                                    <td>
                                                                        <?php if($row['Type'] == 'Income'): ?>
                                                                            <span class="badge badge-success">Income</span>
                                                                        <?php else: ?>
                                                                            <span class="badge badge-danger">Expense</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?php echo htmlspecialchars($row['Category']); ?></td>
                                                                    <td><?php echo htmlspecialchars($row['Amount']); ?></td>
                                                                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                                                                    <td><?php echo htmlspecialchars($row['TransactionDate']); ?></td>
                                                                    <td>
                                                                        <button class="btn btn-sm btn-danger delete-btn" 
                                                                                data-id="<?php echo $row['ID']; ?>" 
                                                                                data-type="<?php echo $row['Type']; ?>">
                                                                            <i class="fas fa-trash-alt"></i> Delete
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                                $cnt++;
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='7' class='text-center'>No transactions found</td></tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php
                                            // Count total rows for pagination
                                            $count_query = "
                                                SELECT COUNT(*) as total FROM (
                                                    SELECT ID FROM tblexpense WHERE userid='$userid'
                                                    UNION ALL
                                                    SELECT ID FROM tblincome WHERE userid='$userid'
                                                ) as combined_table
                                            ";
                                            $result = mysqli_query($db, $count_query);
                                            $data = mysqli_fetch_assoc($result);
                                            $total_pages = ceil($data['total'] / $limit);
                                            ?>

                                            <ul class="pagination justify-content-end">
                                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                                    <a class="page-link" href="<?php if ($page > 1) echo "?page=" . ($page - 1) . "&limit=$limit"; else echo '#'; ?>">Previous</a>
                                                </li>
                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                    <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                                        <a class="page-link" href="<?php echo "?page=$i&limit=$limit"; ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                                    <a class="page-link" href="<?php if ($page < $total_pages) echo "?page=" . ($page + 1) . "&limit=$limit"; else echo '#'; ?>">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Import CSV Modal -->
    <div class="modal fade" id="import-csv-modal" tabindex="-1" role="dialog" aria-labelledby="import-csv-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="import-csv-form" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="import-csv-modal-title">Import CSV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>CSV Format:</strong> Date, Particulars, Expense, Income, Category, Is_Lending<br>
                            <small>2024-01-15, "Salary", 0, 5000, Salary, 0</small>
                        </div>
                        <div class="form-group">
                            <label for="csv-file">Select CSV File</label>
                            <input type="file" class="form-control-file" id="csv-file" name="csv-file" accept=".csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }

        // Profile Menu Toggle
        const toggleButton = document.getElementById('profile-options-toggle');
        const profileOptions = document.getElementById('profile-options');
        toggleButton.addEventListener('click', () => {
            profileOptions.classList.toggle('show');
        });

        $(document).ready(function() {
            // Pagination Limit Change
            var limit = "<?php echo $limit; ?>";
            $('#select-entries').val(limit);
            $('#select-entries').on('change', function() {
                var limit = $(this).val();
                var urlParams = new URLSearchParams(window.location.search);
                urlParams.set('limit', limit);
                urlParams.set('page', 1); // Reset to page 1
                window.location.href = window.location.pathname + '?' + urlParams.toString();
            });

            // Search Functionality
            var originalTableHtml = $('table tbody').html();
            $('#search-input').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                var found = false;
                if (value) {
                    $('table tbody tr').filter(function() {
                        var matches = $(this).text().toLowerCase().indexOf(value) > -1;
                        $(this).toggle(matches);
                        if (matches) found = true;
                    });
                } else {
                    $('table tbody').html(originalTableHtml);
                    found = true;
                }
                if (!found) {
                    $('table tbody').html('<tr><td colspan="7" style="text-align:center;">No data found</td></tr>');
                }
            });

            // Delete Functionality
            $('.delete-btn').on('click', function() {
                var id = $(this).data('id');
                var type = $(this).data('type');
                if (confirm('Are you sure you want to delete this ' + type + '?')) {
                    $.ajax({
                        url: 'api/delete-transaction.php',
                        type: 'POST',
                        data: {
                            id: id,
                            type: type
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred while deleting.');
                        }
                    });
                }
            });

            // CSV Import
            $('#import-csv-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'api/import-csv.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while importing the CSV file.');
                    }
                });
            });
        });
    </script>
</body>
</html>