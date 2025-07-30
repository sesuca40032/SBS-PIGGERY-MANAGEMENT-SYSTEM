<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- Main Content -->
<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">

  <!-- Header -->
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2><b><i class="fa fa-dashboard"></i> Dashboard Overview</b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>

  <!-- Quick Stats -->
  <div class="dashboard-stats-row">
    <?php
    // Use 'pig_batches' table for all stats
    $totalPigs = $db->query("SELECT SUM(total_pigs) FROM pig_batches")->fetchColumn();
    $totalBatches = $db->query("SELECT COUNT(*) FROM pig_batches")->fetchColumn();
    $totalBreeds = $db->query("SELECT COUNT(DISTINCT breed_id) FROM pig_batches")->fetchColumn();

    // Quarantine stats
    $activeCases = $db->query("SELECT COUNT(*) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn();
    $totalQuarantined = $db->query("SELECT SUM(num_quarantined) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn();

    // Gender counts
    $genderCounts = ['Male' => 0, 'Female' => 0];
    $genderQuery = $db->query("SELECT SUM(male_count) as male, SUM(female_count) as female FROM pig_batches");
    $row = $genderQuery->fetch(PDO::FETCH_OBJ);
    $genderCounts['Male'] = (int)($row->male ?? 0);
    $genderCounts['Female'] = (int)($row->female ?? 0);
    ?>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-pig"><i class="fa fa-piggy-bank"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$totalPigs; ?></div>
        <div class="stat-label">Total Pigs</div>
        <div style="font-size:1.09rem; margin-top:3px;">
          <span style="color:#2196f3;">&#9794; <?php echo $genderCounts['Male']; ?></span>
          &nbsp;|&nbsp;
          <span style="color:#e91e63;">&#9792; <?php echo $genderCounts['Female']; ?></span>
        </div>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-batch"><i class="fa fa-layer-group"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$totalBatches; ?></div>
        <div class="stat-label">Batches</div>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-breed"><i class="fa fa-dna"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$totalBreeds; ?></div>
        <div class="stat-label">Breeds</div>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-weight"><i class="fa fa-procedures"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$activeCases; ?></div>
        <div class="stat-label">Active Cases</div>
        <div style="font-size:1.05rem;margin-top:2px;">Quarantined: <b><?php echo (int)$totalQuarantined; ?></b></div>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="dashboard-charts-row">
    <div class="dashboard-chart-col">
      <div class="dashboard-card">
        <h4><i class="fa fa-bar-chart"></i> Pigs by Breed</h4>
        <div class="chart-container">
          <canvas id="breedChart"></canvas>
        </div>
        <div class="dashboard-chart-legend" id="breedLegend"></div>
      </div>
    </div>

    <div class="dashboard-chart-col">
      <div class="dashboard-card">
        <h4><i class="fa fa-pie-chart"></i> Gender Distribution</h4>
        <div class="chart-container">
          <canvas id="genderChart"></canvas>
        </div>
        <div class="dashboard-chart-legend" id="genderLegend"></div>
        <!-- Automatic computation of total male and female -->
        <div id="genderTotalCounts" style="margin-top:18px;font-size:1.11rem;text-align:center;">
          <span style="color:#2196f3;">
            &#9794; Total Male: <b><?php echo $genderCounts['Male']; ?></b>
          </span>
          &nbsp;|&nbsp;
          <span style="color:#e91e63;">
            &#9792; Total Female: <b><?php echo $genderCounts['Female']; ?></b>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  <?php
  // Pigs by breed (from pig_batches)
  $breedData = [];
  $breedLabels = [];
  $breedColors = [
    'rgba(255, 99, 132, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(153, 102, 255, 0.7)',
    'rgba(255, 159, 64, 0.7)',
    'rgba(100, 181, 246, 0.7)',
    'rgba(255, 87, 34, 0.7)'
  ];
  $breeds = $db->query("SELECT id, name FROM breed")->fetchAll(PDO::FETCH_KEY_PAIR);
  $breedQuery = $db->query("SELECT breed_id, SUM(total_pigs) as count FROM pig_batches GROUP BY breed_id");
  $i = 0;
  foreach ($breedQuery->fetchAll(PDO::FETCH_OBJ) as $row) {
    $breedLabels[] = isset($breeds[$row->breed_id]) ? $breeds[$row->breed_id] : ("Breed #" . $row->breed_id);
    $breedData[] = (int)$row->count;
    $i++;
  }

  // Gender Distribution - from pig_batches
  $genderCounts = ['Male' => 0, 'Female' => 0];
  $genderQuery = $db->query("SELECT SUM(male_count) as male, SUM(female_count) as female FROM pig_batches");
  $row = $genderQuery->fetch(PDO::FETCH_OBJ);
  $genderCounts['Male'] = (int)($row->male ?? 0);
  $genderCounts['Female'] = (int)($row->female ?? 0);
  ?>

  // Breed Distribution Chart
  const breedCtx = document.getElementById('breedChart').getContext('2d');
  const breedChart = new Chart(breedCtx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($breedLabels); ?>,
      datasets: [{
        label: 'Number of Pigs',
        data: <?php echo json_encode($breedData); ?>,
        backgroundColor: <?php echo json_encode(array_slice($breedColors, 0, count($breedLabels))); ?>,
        borderColor: <?php echo json_encode(array_map(function($c){return str_replace('0.7','1',$c);}, array_slice($breedColors, 0, count($breedLabels)))); ?>,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  // Gender Distribution Chart
  const genderCtx = document.getElementById('genderChart').getContext('2d');
  const genderChart = new Chart(genderCtx, {
    type: 'pie',
    data: {
      labels: ['Male', 'Female'],
      datasets: [{
        data: [<?php echo $genderCounts['Male']; ?>, <?php echo $genderCounts['Female']; ?>],
        backgroundColor: [
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 99, 132, 0.7)'
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255, 99, 132, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 14,
            padding: 18
          }
        }
      }
    }
  });
});
</script>

<style>
/* Dashboard Modern UI Improvements */
.dashboard-main {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
}
.dashboard-header {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 30px 38px 20px 38px;
  box-shadow: 0 4px 24px -10px #38598b40;
}
.dashboard-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.dashboard-title h2 {
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin-bottom: 0;
}
.dashboard-badge {
  background: #fff;
  color: #38598b;
  font-size: 1.15rem;
  font-weight: 700;
  border-radius: 20px;
  padding: 8px 26px;
  box-shadow: 0 2px 8px -2px #00000018;
}
.dashboard-stats-row {
  display: flex;
  gap: 28px;
  margin: 0 38px 36px 38px;
  justify-content: flex-start;
  flex-wrap: wrap;
}
.dashboard-stat-card {
  display: flex;
  align-items: center;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 2px 16px -4px #38598b18;
  padding: 22px 32px;
  min-width: 180px;
  min-height: 80px;
  flex: 1;
  gap: 18px;
}
.stat-icon {
  font-size: 2.2rem;
  margin-right: 10px;
  background: #f3f6fb;
  border-radius: 50%;
  padding: 13px;
  box-shadow: 0 2px 8px -2px #38598b18;
}
.stat-pig { color: #ff7e5f; }
.stat-batch { color: #4a6fa5; }
.stat-breed { color: #b388ff; }
.stat-weight { color: #009688; }
.stat-number {
  font-size: 2rem;
  font-weight: 800;
  color: #38598b;
}
.stat-label {
  font-size: 1.08rem;
  color: #7b8ba3;
  font-weight: 500;
  margin-top: 2px;
}
.dashboard-charts-row {
  display: flex;
  gap: 32px;
  margin: 0 38px;
  flex-wrap: wrap;
}
.dashboard-chart-col {
  flex: 1 1 400px;
  min-width: 350px;
  max-width: 48vw;
  margin-bottom: 28px;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 350px;
}
.dashboard-card h4 {
  font-size: 1.22rem;
  color: #38598b;
  margin-bottom: 20px;
  font-weight: 700;
  letter-spacing: 0.2px;
}
.chart-container {
  position: relative;
  height: 220px;
  margin-bottom: 8px;
}
.dashboard-chart-legend {
  text-align: center;
  color: #5a6a8f;
  font-size: 1.04rem;
  margin-top: 12px;
}
@media (max-width: 1100px) {
  .dashboard-charts-row, .dashboard-stats-row {
    flex-direction: column;
    gap: 18px;
  }
  .dashboard-card {
    min-width: unset;
    max-width: 99vw;
  }
}
@media (max-width: 768px) {
  .dashboard-main {
    margin-left: 0;
    padding: 0 0 10px 0;
  }
  .dashboard-header,
  .dashboard-stats-row,
  .dashboard-charts-row {
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 7px;
    padding-right: 7px;
  }
  .dashboard-header {
    padding: 21px 8px 14px 8px;
  }
}
</style>