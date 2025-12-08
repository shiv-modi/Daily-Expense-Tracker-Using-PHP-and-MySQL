<?php
session_start();
error_reporting(0);
include('database.php');
$sessionValid = !empty($_SESSION['detsuid']);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/auth.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <style>
        .container { background-color: #f2f2f2; border-radius: 5px; box-shadow: 0px 0px 10px #aaa; padding: 20px; margin-top: 20px; }
        .table th { background-color: #f8f9fa; border-color: #dee2e6; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; color: #495057; }
        .table td { vertical-align: middle; font-size: 0.9rem; }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .loading-spinner { text-align: center; padding: 40px; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo-details"><i class='bx bx-album'></i><span class="logo_name">Expenditure</span></div>
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
        <div class="sidebar-button"><i class='bx bx-menu sidebarBtn'></i><span class="dashboard">Expenditure</span></div>
        <div class="search-box">
            <input type="text" id="search-input" class="form-control form-control-sm mx-2" placeholder="Search...">
            <i class='bx bx-search'></i>
        </div>
        <div class="profile-details">
            <img src="images/maex.png" alt="">
            <span class="admin_name" id="user-name">User</span>
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
                                        <div class="col-md-5"><h4 class="card-title mb-0">Manage Transactions</h4></div>
                                        <div class="col-md-7 text-right">
                                            <a href="api/export-csv.php?type=all" class="btn btn-success btn-sm mr-1"><i class="fas fa-download"></i> Export CSV</a>
                                            <button type="button" class="btn btn-info btn-sm mr-1" data-toggle="modal" data-target="#import-csv-modal"><i class="fas fa-upload"></i> Import CSV</button>
                                            <label class="mb-0 ml-2">Show
                                                <select class="form-control-sm mx-1" id="select-entries">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                                entries
                                            </label>
                                            <select class="form-control-sm ml-2" id="type-filter">
                                                <option value="all">All</option>
                                                <option value="expense">Expenses</option>
                                                <option value="income">Income</option>
                                            </select>
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
                                                <tbody id="transactions-tbody">
                                                    <tr><td colspan="7" class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <nav><ul class="pagination justify-content-end" id="pagination"></ul></nav>
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

<div class="modal fade" id="import-csv-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="import-csv-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Import CSV</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info"><strong>CSV Format:</strong> Date, Particulars, Expense, Income, Category, Is_Lending</div>
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
var currentPage = 1;
var currentLimit = 10;
var currentType = 'all';

function checkAuth() {
    var hasToken = localStorage.getItem('access_token');
    var hasSession = <?php echo $sessionValid ? 'true' : 'false'; ?>;
    if (!hasToken && !hasSession) {
        window.location.href = 'index.php';
        return false;
    }
    return true;
}

function loadTransactions() {
    if (!checkAuth()) return;
    
    $.ajax({
        url: 'api/transactions.php',
        type: 'GET',
        data: { page: currentPage, limit: currentLimit, type: currentType },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                renderTransactions(response.data.transactions, response.data.pagination);
            } else {
                $('#transactions-tbody').html('<tr><td colspan="7" class="text-center text-danger">' + response.message + '</td></tr>');
            }
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                localStorage.removeItem('access_token');
                window.location.href = 'index.php';
            } else {
                $('#transactions-tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading transactions</td></tr>');
            }
        }
    });
}

function renderTransactions(transactions, pagination) {
    if (transactions.length === 0) {
        $('#transactions-tbody').html('<tr><td colspan="7" class="text-center">No transactions found</td></tr>');
        $('#pagination').html('');
        return;
    }
    
    var html = '';
    var startIdx = (pagination.current_page - 1) * pagination.limit + 1;
    
    transactions.forEach(function(item, index) {
        var badgeClass = item.type === 'Income' ? 'badge-success' : 'badge-danger';
        html += '<tr>' +
            '<td>' + (startIdx + index) + '</td>' +
            '<td><span class="badge ' + badgeClass + '">' + item.type + '</span></td>' +
            '<td>' + (item.category || '-') + '</td>' +
            '<td>' + item.amount + '</td>' +
            '<td>' + (item.description || '-') + '</td>' +
            '<td>' + item.date + '</td>' +
            '<td><button class="btn btn-sm btn-danger delete-btn" data-id="' + item.id + '" data-type="' + item.type + '"><i class="fas fa-trash-alt"></i> Delete</button></td>' +
        '</tr>';
    });
    
    $('#transactions-tbody').html(html);
    
    var paginationHtml = '<li class="page-item ' + (pagination.current_page <= 1 ? 'disabled' : '') + '">' +
        '<a class="page-link" href="#" data-page="' + (pagination.current_page - 1) + '">Previous</a></li>';
    
    for (var i = 1; i <= pagination.total_pages; i++) {
        paginationHtml += '<li class="page-item ' + (pagination.current_page === i ? 'active' : '') + '">' +
            '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
    }
    
    paginationHtml += '<li class="page-item ' + (pagination.current_page >= pagination.total_pages ? 'disabled' : '') + '">' +
        '<a class="page-link" href="#" data-page="' + (pagination.current_page + 1) + '">Next</a></li>';
    
    $('#pagination').html(paginationHtml);
}

$(document).ready(function() {
    var userData = localStorage.getItem('user_data');
    if (userData) {
        var user = JSON.parse(userData);
        $('#user-name').text(user.name || 'User');
    }
    
    loadTransactions();
    
    $('#select-entries').on('change', function() {
        currentLimit = parseInt($(this).val());
        currentPage = 1;
        loadTransactions();
    });
    
    $('#type-filter').on('change', function() {
        currentType = $(this).val();
        currentPage = 1;
        loadTransactions();
    });
    
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = parseInt($(this).data('page'));
        if (page > 0) {
            currentPage = page;
            loadTransactions();
        }
    });
    
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        var type = $(this).data('type');
        if (confirm('Are you sure you want to delete this ' + type + '?')) {
            $.ajax({
                url: 'api/delete-transaction.php',
                type: 'POST',
                data: { id: id, type: type },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        loadTransactions();
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
    
    $('#search-input').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#transactions-tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
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
                    $('#import-csv-modal').modal('hide');
                    loadTransactions();
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

let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function() {
    sidebar.classList.toggle("active");
    if (sidebar.classList.contains("active")) {
        sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
    } else {
        sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
}

const toggleButton = document.getElementById('profile-options-toggle');
const profileOptions = document.getElementById('profile-options');
toggleButton.addEventListener('click', () => { profileOptions.classList.toggle('show'); });
</script>
</body>
</html>
