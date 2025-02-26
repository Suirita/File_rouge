<!DOCTYPE html>
<html lang="en">

<?php
// Include the head section (meta tags, title, CSS links)
include_once '../layouts/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <?php
    // Include the navigation bar and sidebar
    include_once '../layouts/nav.php';
    include_once '../layouts/aside.php';
    ?>

    <main class="py-4">
      <div class="content-wrapper p-3">
        <div class="card card-info">

          <!-- Card Header -->
          <div class="card-header border-transparent">
            <h3 class="h3">Tableau de bord</h3>
          </div>

          <!-- Row with statistics boxes -->
          <div class="row mx-3 mt-4">
            <!-- Total Candidates -->
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>500</h3>
                  <p>Les inscrits</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>

            <!-- Accepted Candidates -->
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>40</h3>
                  <p>Acceptés</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-check"></i>
                </div>
              </div>
            </div>

            <!-- Pending Candidates -->
            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>330</h3>
                  <p>En Attente</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user"></i>
                </div>
              </div>
            </div>

            <!-- Rejected Candidates -->
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>130</h3>
                  <p>Refusés</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-slash"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Row with statistics charts -->
          <div class="row mx-3 mb-2">
            <!-- Pie Chart -->
            <div class="col-6">
              <div class="card card-danger">
                <div class="card-body">
                  <canvas id="pieChart" style="min-height: 330px; height: 330px; max-height: 330px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-6">
              <div class="card card-success">
                <div class="card-body">
                  <canvas id="barChart" style="min-height: 330px; height: 330px; max-height: 330px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </main>

    <?php
    // Include the footer
    include_once '../layouts/footer.php';
    ?>
  </div>

  <?php
  // Include JavaScript files and dependencies
  include_once '../layouts/script-link.php';
  ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Pie Chart: Distribution of candidates by status
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: ['Acceptés', 'En Attente', 'Refusés'],
        datasets: [{
          data: [40, 330, 130], // Match the totals shown in the statistics
          backgroundColor: ['#00a65a', '#f39c12', '#f56954'], // Colors
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          datalabels: {
            formatter: (value, ctx) => {
              const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
              const percentage = ((value / sum) * 100).toFixed(2) + '%';
              return percentage;
            },
            color: '#fff', // Label color
            font: {
              weight: 'bold'
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  });


  document.addEventListener('DOMContentLoaded', () => {
    // Bar Chart: Average scores by category
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Français', 'Anglais', 'Programmation', 'Soft Skills', 'Travail en Équipe'], // Categories
        datasets: [{
            label: 'Acceptés',
            data: [42, 49, 55, 40, 46], // Scores for accepted candidates
            backgroundColor: '#198754'
          },
          {
            label: 'En Attente',
            data: [65, 70, 75, 63, 72], // Scores for pending candidates
            backgroundColor: '#fd7e14'
          },
          {
            label: 'Refusés',
            data: [40, 23, 26, 31, 30], // Scores for rejected candidates
            backgroundColor: '#dc3545'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'top'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              stepSize: 10
            }
          }],
          xAxes: [{
            barPercentage: 0.6
          }]
        }
      }
    });
  });
</script>

</html>
