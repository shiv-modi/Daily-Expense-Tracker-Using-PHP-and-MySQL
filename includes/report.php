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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/auth.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        .report-table { margin-top: 20px; }
        .summary-card { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .summary-card h4 { margin: 0 0 10px 0; }
        .loading { text-align: center; padding: 40px; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo-details"><i class='bx bx-album'></i><span class="logo_name">Expenditure</span></div>
    <ul class="nav-links">
        <li><a href="home.php"><i class='bx bx-grid-alt'></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="add-expenses.php"><i class='bx bx-box'></i><span class="links_name">Expenses</span></a></li>
        <li><a href="add-income.php"><i class='bx bx-box'></i><span class="links_name">Income</span></a></li>
        <li><a href="manage-transaction.php"><i class='bx bx-list-ul'></i><span class="links_name">Manage List</span></a></li>
        <li><a href="lending.php"><i class='bx bx-money'></i><span class="links_name">lending</span></a></li>
        <li><a href="manage-lending.php"><i class='bx bx-coin-stack'></i><span class="links_name">Manage lending</span></a></li>
        <li><a href="analytics.php"><i class='bx bx-pie-chart-alt-2'></i><span class="links_name">Analytics</span></a></li>
        <li><a href="report.php" class="active"><i class="bx bx-file"></i><span class="links_name">Report</span></a></li>
        <li><a href="user_profile.php"><i class='bx bx-cog'></i><span class="links_name">Setting</span></a></li>
        <li class="log_out"><a href="logout.php"><i class='bx bx-log-out'></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

<section class="home-section">
    <nav>
        <div class="sidebar-button"><i class='bx bx-menu sidebarBtn'></i><span class="dashboard">Expenditure</span></div>
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
            <div class="col-md-12">
                <br>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Generate Report</h4>
                    </div>
                    <div class="card-body">
                        <form id="reportForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="reportType">Report Type</label>
                                        <select class="form-control" id="reportType" name="reportType" required>
                                            <option value="" disabled selected>Select a report type</option>
                                            <option value="expense">Expense Report</option>
                                            <option value="income">Income Report</option>
                                            <option value="pending">Pending Lending Report</option>
                                            <option value="received">Received Lending Report</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="startDate">Start Date</label>
                                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="endDate">End Date</label>
                                        <input type="date" class="form-control" id="endDate" name="endDate" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block" id="generateBtn">
                                            <span id="btnText">Generate Report</span>
                                            <span id="btnSpinner" class="spinner-border spinner-border-sm" style="display:none;"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div id="report-results" style="display:none;">
                    <div class="card mt-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="card-title mb-0" id="report-title">Report Results</h4>
                                </div>
                                <div class="col-md-6 text-right">
                                    <span id="date-range" class="text-muted"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="summary-card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Total Records: <span id="total-records">0</span></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Total Amount: Rs. <span id="total-amount">0</span></h5>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive report-table">
                                <table class="table table-striped table-bordered">
                                    <thead id="report-thead"></thead>
                                    <tbody id="report-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function checkAuth() {
    var hasToken = localStorage.getItem('access_token');
    var hasSession = <?php echo $sessionValid ? 'true' : 'false'; ?>;
    if (!hasToken && !hasSession) {
        window.location.href = 'index.php';
        return false;
    }
    return true;
}

$(document).ready(function() {
    if (!checkAuth()) return;
    
    var userData = localStorage.getItem('user_data');
    if (userData) {
        var user = JSON.parse(userData);
        $('#user-name').text(user.name || 'User');
    }
    
    var today = new Date();
    var thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    $('#endDate').val(today.toISOString().split('T')[0]);
    $('#startDate').val(thirtyDaysAgo.toISOString().split('T')[0]);
    
    $('#reportForm').on('submit', function(e) {
        e.preventDefault();
        
        var reportType = $('#reportType').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        
        if (!reportType || !startDate || !endDate) {
            alert('Please fill in all fields');
            return;
        }
        
        $('#generateBtn').prop('disabled', true);
        $('#btnText').hide();
        $('#btnSpinner').show();
        
        $.ajax({
            url: 'api/report.php',
            type: 'GET',
            data: {
                type: reportType,
                start_date: startDate,
                end_date: endDate
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayReport(response);
                } else {
                    alert(response.message || 'Error generating report');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    localStorage.removeItem('access_token');
                    window.location.href = 'index.php';
                } else {
                    alert('Error generating report. Please try again.');
                }
            },
            complete: function() {
                $('#generateBtn').prop('disabled', false);
                $('#btnText').show();
                $('#btnSpinner').hide();
            }
        });
    });
});

function displayReport(response) {
    var reportType = response.report_type;
    var data = response.data;
    var summary = response.summary;
    
    var titles = {
        'expense': 'Expense Report',
        'income': 'Income Report',
        'pending': 'Pending Lending Report',
        'received': 'Received Lending Report'
    };
    
    $('#report-title').text(titles[reportType] || 'Report');
    $('#date-range').text(response.date_range.start + ' to ' + response.date_range.end);
    $('#total-records').text(summary.total_records || 0);
    
    var totalAmount = summary.total_amount || summary.total_pending || summary.total_received || 0;
    $('#total-amount').text(totalAmount.toFixed(2));
    
    var theadHtml = '<tr>';
    var tbodyHtml = '';
    
    if (reportType === 'expense' || reportType === 'income') {
        theadHtml += '<th>#</th><th>Date</th><th>Category</th><th>Amount</th><th>Description</th></tr>';
        
        data.forEach(function(item, index) {
            tbodyHtml += '<tr>' +
                '<td>' + (index + 1) + '</td>' +
                '<td>' + item.date + '</td>' +
                '<td>' + (item.category || '-') + '</td>' +
                '<td>Rs. ' + item.amount.toFixed(2) + '</td>' +
                '<td>' + (item.description || '-') + '</td>' +
            '</tr>';
        });
    } else {
        theadHtml += '<th>#</th><th>Name</th><th>Date</th><th>Amount</th><th>Description</th><th>Status</th></tr>';
        
        data.forEach(function(item, index) {
            var statusBadge = item.status === 'received' 
                ? '<span class="badge badge-success">Received</span>' 
                : '<span class="badge badge-warning">Pending</span>';
            
            tbodyHtml += '<tr>' +
                '<td>' + (index + 1) + '</td>' +
                '<td>' + item.name + '</td>' +
                '<td>' + item.date + '</td>' +
                '<td>Rs. ' + item.amount.toFixed(2) + '</td>' +
                '<td>' + (item.description || '-') + '</td>' +
                '<td>' + statusBadge + '</td>' +
            '</tr>';
        });
    }
    
    if (data.length === 0) {
        tbodyHtml = '<tr><td colspan="6" class="text-center">No data found for the selected criteria</td></tr>';
    }
    
    $('#report-thead').html(theadHtml);
    $('#report-tbody').html(tbodyHtml);
    $('#report-results').show();
}

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
