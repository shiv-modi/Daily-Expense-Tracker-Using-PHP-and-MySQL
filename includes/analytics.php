<?php
session_start();
error_reporting(0);
include('database.php');
if (empty($_SESSION['detsuid'])) {
  header('location:logout.php');
} else {
?>

  <!DOCTYPE html>
  <!-- Designined by CodingLab | www.youtube.com/codinglabyt -->
  <html lang="en" dir="ltr">

  <head>
    <meta charset="UTF-8">
    <!--<title> Responsiive Admin Dashboard | CodingLab </title>-->
    <link rel="stylesheet" href="css/style.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  </head>

  <body>
    <div class="sidebar">
      <div class="logo-details">
        <i class='bx bx-album'></i>
        <span class="logo_name">Expenditure</span>
      </div>
      <ul class="nav-links">
        <li>
          <a href="home.php">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="add-expenses.php">
            <i class='bx bx-box'></i>
            <span class="links_name">Expenses</span>
          </a>
        </li>
        <li>
          <a href="add-income.php">
            <i class='bx bx-box'></i>
            <span class="links_name">Income</span>
          </a>
        </li>
        <li>
          <a href="manage-transaction.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Manage List</span>
          </a>
        </li>

        <li>
          <a href="lending.php">
            <i class='bx bx-money'></i>
            <span class="links_name">lending</span>
          </a>
        </li>
        <li>
          <a href="manage-lending.php">
            <i class='bx bx-coin-stack'></i>
            <span class="links_name">Manage lending</span>
          </a>
        </li>
        <li>
          <a href="analytics.php" class="active">
            <i class='bx bx-pie-chart-alt-2'></i>
            <span class="links_name">Analytics</span>
          </a>
        </li>
        <li>
          <a href="report.php">
            <i class="bx bx-file"></i>
            <span class="links_name">Report</span>
          </a>
        </li>
        <li>
          <a href="user_profile.php">
            <i class='bx bx-cog'></i>
            <span class="links_name">Setting</span>
          </a>
        </li>
        <li class="log_out">
          <a href="logout.php">
            <i class='bx bx-log-out'></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
    </div>
    <section class="home-section">
      <nav>
        <div class="sidebar-button">
          <i class='bx bx-menu sidebarBtn'></i>
          <span class="dashboard">Expenditure</span>
        </div>


        <?php
        $uid = $_SESSION['detsuid'];
        $ret = mysqli_query($db, "select name  from users where id='$uid'");
        $row = mysqli_fetch_array($ret);
        $name = $row['name'];

        ?>

        <div class="profile-details">
          <img src="images/maex.png" alt="">
          <span class="admin_name"><?php echo $name; ?></span>
          <i class='bx bx-chevron-down' id='profile-options-toggle'></i>
          <ul class="profile-options" id='profile-options'>
            <li><a href="#"><i class="fas fa-user-circle"></i> User Profile</a></li>
            <!-- <li><a href="#"><i class="fas fa-cog"></i> Account Settings</a></li> -->
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>


        <script>
          const toggleButton = document.getElementById('profile-options-toggle');
          const profileOptions = document.getElementById('profile-options');

          toggleButton.addEventListener('click', () => {
            profileOptions.classList.toggle('show');
          });
        </script>


      </nav>

      <div class="home-content">
        <div class="overview-boxes">


          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Analytics</h5>

              </div>
              <div class="card-body">
                <canvas id="myChart" width="1028" height="632" style="display: block;box-sizing: border-box;height: 493px;width: 822px;"></canvas>
              </div>


            </div>
          </div>
        </div>


      </div>
      </div>

      <script>
        fetch('pie-data.php')
          .then(response => response.json())
          .then(data => {
            if (data.length == 0) {
              const noDataMessage = document.createElement('div');
              noDataMessage.textContent = 'No data found.';
              document.getElementById('myChart').replaceWith(noDataMessage);
              return;
            }

            const labels = data.map(item => item.category);
            const values = data.map(item => item.total_expense);

            // Calculate the percentage and total expense for each category
            const total = values.reduce((acc, curr) => acc + curr, 0);
            const percentages = values.map(value => ((value / total) * 100).toFixed(2));
            const expenses = data.map(item => item.total_expense);

            // Generate the pie chart
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: labels,
                datasets: [{
                  data: values,
                  backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#8E44AD',
                    '#3498DB',
                    '#FFA07A',
                    '#6B8E23',
                    '#FF00FF',
                    '#FFD700',
                    '#00FFFF'
                  ]

                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                  callbacks: {
                    label: function(tooltipItem, data) {
                      const index = tooltipItem.index;
                      const expense = expenses[index];
                      const percentage = percentages[index];
                      const label = data.labels[index];
                      return `${label}: ${percentage}% ~ Rs ${expense}`;
                    }
                  }
                },
                legend: {
                  display: false
                }
              },
              plugins: [{
                beforeInit: function(chart, options) {
                  const legend = document.createElement('div');
                  legend.classList.add('chart-legend');
                  for (let i = 0; i < labels.length; i++) {
                    const item = document.createElement('div');
                    item.classList.add('chart-legend-item');
                    const colorBox = document.createElement('div');
                    colorBox.classList.add('chart-legend-color-box');
                    colorBox.style.backgroundColor = chart.data.datasets[0].backgroundColor[i];
                    const label = document.createElement('div');
                    label.classList.add('chart-legend-label');
                    label.textContent = `${labels[i]}: ${percentages[i]}% ~ Rs ${expenses[i]}`;
                    item.appendChild(colorBox);
                    item.appendChild(label);
                    legend.appendChild(item);
                  }
                  chart.canvas.parentNode.insertBefore(legend, chart.canvas.nextSibling);
                }
              }]
            });
          });
      </script>



      <style>
        .chart-container {
          position: relative;
          width: 70%;
          height: 400px;
          margin-right: 20px;


        }

        .chart-legend {
          position: absolute;
          top: 224px;
          right: 68px;
          display: flex;
          flex-direction: column;
          align-items: flex-end;
        }

        .chart-legend-item {
          display: flex;
          align-items: center;
          margin-bottom: 5px;
        }

        .chart-legend-color-box {
          width: 10px;
          height: 10px;
          margin-right: 5px;
        }

        .chart-legend-label {
          font-size: 12px;
        }

        .expense-details {
          width: 30%;
        }

        .expense-details h3 {
          font-size: 24px;
          margin-top: 0;
        }

        #expense-list {
          list-style: none;
          padding: 0;
          margin: 0;
        }

        #expense-list li {
          display: flex;
          justify-content: space-between;
          margin-bottom: 10px;
        }

        #expense-list li span:first-child {
          font-weight: bold;
          margin-right: 10px;
        }

        #total-expense {
          font-size: 24px;
          font-weight: bold;
          margin-top: 20px;
        }
      </style>








      <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
          sidebar.classList.toggle("active");
          if (sidebar.classList.contains("active")) {
            sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
          } else
            sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
      </script>

  </body>

  </html>

<?php } ?>