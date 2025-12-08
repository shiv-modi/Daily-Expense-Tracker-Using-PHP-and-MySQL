<?php
session_start();
error_reporting(0);
include('database.php');

$sessionValid = !empty($_SESSION['detsuid']);
$hasToken = false;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/auth.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="sidebar">
    <div class="logo-details">
        <i class='bx bx-album'></i>
        <span class="logo_name">Expenditure</span>
    </div>
    <ul class="nav-links">
        <li><a href="#" class="active"><i class='bx bx-grid-alt'></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="add-expenses.php"><i class='bx bx-box'></i><span class="links_name">Expenses</span></a></li>
        <li><a href="add-income.php"><i class='bx bx-box'></i><span class="links_name">Income</span></a></li>
        <li><a href="manage-transaction.php"><i class='bx bx-list-ul'></i><span class="links_name">Manage List</span></a></li>
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
            <span class="dashboard">Dashboard</span>
        </div>
        <div class="search-box">
            <input type="text" id="search-input" class="form-control form-control-sm mx-2" placeholder="Search...">
            <i class='bx bx-search'></i>
        </div>
        <div class="profile-details">
            <img src="images/maex.png" alt="">
            <span class="admin_name" id="user-name">Loading...</span>
            <i class='bx bx-chevron-down' id='profile-options-toggle'></i>
            <ul class="profile-options" id='profile-options'>
                <li><a href="user_profile.php"><i class="fas fa-user-circle"></i> User Profile</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="home-content">
        <div class="overview-boxes">
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Today Expense</div>
                    <div class="number" id="today-expense">0</div>
                    <div class="indicator"><i class='bx bx-up-arrow-alt'></i><span class="text">Up from Today</span></div>
                </div>
                <i class='fas fa-circle-plus cart'></i>
            </div>

            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Yesterday Expense</div>
                    <div class="number" id="yesterday-expense">0</div>
                    <div class="indicator"><i class='bx bx-up-arrow-alt'></i><span class="text">Up from yesterday</span></div>
                </div>
                <i class="fas fa-wallet cart two"></i>
            </div>

            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Last 30 day Expense</div>
                    <div class="number" id="monthly-expense">0</div>
                    <div class="indicator"><i class='bx bx-up-arrow-alt'></i><span class="text">Up from Last 30 day</span></div>
                </div>
                <i class='fas fa-history cart three'></i>
            </div>

            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Total Expense</div>
                    <div class="number" id="total-expense">0</div>
                    <div class="indicator"><i class='bx bx-up-arrow-alt up'></i><span class="text">Up from Year</span></div>
                </div>
                <i class='fas fa-piggy-bank cart four'></i>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title">Expense Chart</h5></div>
            <div class="card-body"><canvas id="myChart"></canvas></div>
        </div>

        <div class="card1">
            <div class="card-header"><h5 class="card-title">Category Table</h5></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>Percentage</th><th>Category</th><th>Amount</th></tr>
                    </thead>
                    <tbody id="expense-table-body"></tbody>
                    <tfoot>
                        <tr><th></th><th>Total</th><th>Rs. <span id="category-total">0</span></th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<button id="add-button" title="Add Expense"><i class="fas fa-plus"></i></button>

<script>
var chart;

function checkAuthAndRedirect() {
    var hasToken = localStorage.getItem('access_token');
    var hasSession = <?php echo $sessionValid ? 'true' : 'false'; ?>;
    
    if (!hasToken && !hasSession) {
        window.location.href = 'index.php';
        return false;
    }
    return true;
}

function loadDashboardData() {
    if (!checkAuthAndRedirect()) return;
    
    $.ajax({
        url: 'api/dashboard.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var data = response.data;
                
                $('#user-name').text(data.user.name || 'User');
                $('#today-expense').text(data.today_expense || 0);
                $('#yesterday-expense').text(data.yesterday_expense || 0);
                $('#monthly-expense').text(data.monthly_expense || 0);
                $('#total-expense').text(data.total_expense || 0);
                
                updateChart(data.chart.labels, data.chart.data);
                updateCategoryTable(data.categories);
            } else {
                console.error('Dashboard error:', response.message);
                if (response.message && response.message.includes('Unauthorized')) {
                    localStorage.removeItem('access_token');
                    window.location.href = 'index.php';
                }
            }
        },
        error: function(xhr) {
            console.error('Dashboard request failed');
            if (xhr.status === 401) {
                localStorage.removeItem('access_token');
                window.location.href = 'index.php';
            }
        }
    });
}

function updateChart(labels, data) {
    var ctx = document.getElementById('myChart').getContext('2d');
    
    if (chart) {
        chart.destroy();
    }
    
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Expenses',
                data: data,
                backgroundColor: 'rgba(224, 82, 96, 0.5)',
                borderColor: '#e05260',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function updateCategoryTable(categories) {
    var total = categories.reduce(function(acc, curr) { return acc + curr.total_expense; }, 0);
    var colors = ['#FF6384', '#36A2EB', '#FFCE56', '#8E44AD', '#3498DB', '#FFA07A', '#6B8E23', '#FF00FF', '#FFD700', '#00FFFF'];
    
    var rows = categories.map(function(item, i) {
        var percentage = total > 0 ? ((item.total_expense / total) * 100).toFixed(2) : 0;
        var color = colors[i % colors.length];
        return '<tr>' +
            '<td><span class="badge badge-pill badge-primary" style="background-color: ' + color + '">' + percentage + '%</span></td>' +
            '<td>' + item.category + '</td>' +
            '<td>Rs. ' + item.total_expense.toFixed(2) + '</td>' +
        '</tr>';
    }).join('');
    
    $('#expense-table-body').html(rows);
    $('#category-total').text(total.toFixed(2));
}

$(document).ready(function() {
    loadDashboardData();
    
    setInterval(loadDashboardData, 30000);
    
    $('#search-input').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
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
toggleButton.addEventListener('click', () => {
    profileOptions.classList.toggle('show');
});

const addButton = document.getElementById('add-button');
addButton.addEventListener('click', () => {
    addButton.style.transform = 'translateX(50px)';
    setTimeout(() => { window.location.href = "add-expenses.php"; }, 200);
});
</script>

<style>
canvas { width: 100%; height: auto; }
.card { border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px; padding: 20px; background-color: #fff; height: 500px; }
.card1 { border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px; padding: 20px; background-color: #fff; }
.card-header { background-color: #f7f7f7; border-bottom: 1px solid #ddd; margin-bottom: 20px; padding: 10px; }
.card-title { font-size: 24px; font-weight: bold; margin: 0; }
.card-body { padding: 0; }
.table { border-collapse: collapse; width: 100%; font-size: 16px; text-align: left; }
.table th { background-color: #f2f2f2; font-weight: bold; padding: 10px 20px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; }
.table td { padding: 10px 20px; border-bottom: 1px solid #ddd; }
.badge { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; padding: 5px 10px; }
#add-button { position: fixed; bottom: 24px; right: 24px; border: none; border-radius: 50%; background-color: #4285f4; width: 64px; height: 64px; cursor: pointer; display: flex; justify-content: center; align-items: center; box-shadow: 0px 4px 8px rgba(0,0,0,0.2); transition: all 0.2s ease-in-out; }
#add-button:hover { transform: translateY(-2px); box-shadow: 0px 8px 16px rgba(0,0,0,0.2); background-color: #000; }
#add-button i { font-size: 24px; color: #fff; transition: all 0.2s ease-in-out; }
#add-button:hover i { transform: rotate(-45deg); }
@media (max-width: 768px) { .card { margin: 10px; padding: 10px; } .card-title { font-size: 20px; } }
</style>
</body>
</html>
