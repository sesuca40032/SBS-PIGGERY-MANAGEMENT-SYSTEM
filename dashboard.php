<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- Main Content -->
<div class="modern-dashboard" style="margin-left:280px">

  <!-- Hero Header Section -->
  <div class="hero-section">
    <div class="hero-content">
      <div class="hero-text">
        <h1 class="hero-title">
          <i class="fa fa-tachometer-alt"></i>
          Farm Dashboard
        </h1>
        <p class="hero-subtitle">Comprehensive overview of your pig farming operations</p>
      </div>
      <div class="hero-date">
        <div class="date-card">
          <i class="fa fa-calendar-alt"></i>
          <span><?php echo date('F j, Y'); ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Dashboard Layout -->
  <div class="dashboard-layout">
    <!-- Left Column - Statistics -->
    <div class="stats-column">
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
      
      <!-- Total Pigs Card -->
      <div class="stat-card primary-card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fa fa-piggy-bank"></i>
          </div>
          <div class="card-title">Total Pigs</div>
        </div>
        <div class="card-body">
          <div class="main-stat"><?php echo number_format((int)$totalPigs); ?></div>
          <div class="gender-breakdown">
            <div class="gender-item male">
              <i class="fa fa-mars"></i>
              <span><?php echo number_format($genderCounts['Male']); ?></span>
            </div>
            <div class="gender-item female">
              <i class="fa fa-venus"></i>
              <span><?php echo number_format($genderCounts['Female']); ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Batches Card -->
      <div class="stat-card secondary-card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fa fa-layer-group"></i>
          </div>
          <div class="card-title">Active Batches</div>
        </div>
        <div class="card-body">
          <div class="main-stat"><?php echo number_format((int)$totalBatches); ?></div>
          <div class="card-subtitle">Currently managed</div>
    </div>
      </div>

      <!-- Breeds Card -->
      <div class="stat-card accent-card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fa fa-dna"></i>
          </div>
          <div class="card-title">Breeds</div>
        </div>
        <div class="card-body">
          <div class="main-stat"><?php echo number_format((int)$totalBreeds); ?></div>
          <div class="card-subtitle">Different varieties</div>
    </div>
      </div>

      <!-- Quarantine Card -->
      <div class="stat-card warning-card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fa fa-shield-alt"></i>
          </div>
          <div class="card-title">Quarantine</div>
        </div>
        <div class="card-body">
          <div class="main-stat"><?php echo number_format((int)$activeCases); ?></div>
          <div class="quarantine-details">
            <div class="detail-item">
              <span class="detail-label">Active Cases:</span>
              <span class="detail-value"><?php echo number_format((int)$activeCases); ?></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Quarantined:</span>
              <span class="detail-value"><?php echo number_format((int)$totalQuarantined); ?></span>
            </div>
      </div>
    </div>
      </div>
    </div>

    <!-- Right Column - Analytics -->
    <div class="analytics-column">
      <div class="section-header">
        <h2 class="section-title">
          <i class="fa fa-chart-line"></i>
          Analytics & Insights
        </h2>
        <p class="section-subtitle">Visual representation of your farm data</p>
  </div>

      <div class="charts-container">
        <!-- Breed Distribution Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <h3 class="chart-title">
              <i class="fa fa-bar-chart"></i>
              Pigs by Breed
            </h3>
            <div class="chart-subtitle">Distribution across different breeds</div>
          </div>
          <div class="chart-body">
        <div class="chart-container">
          <canvas id="breedChart"></canvas>
        </div>
      </div>
    </div>

        <!-- Gender Distribution Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <h3 class="chart-title">
              <i class="fa fa-pie-chart"></i>
              Gender Distribution
            </h3>
            <div class="chart-subtitle">Male vs Female ratio</div>
          </div>
          <div class="chart-body">
        <div class="chart-container">
          <canvas id="genderChart"></canvas>
        </div>
            <div class="gender-summary">
              <div class="summary-item male">
                <div class="summary-icon">
                  <i class="fa fa-mars"></i>
                </div>
                <div class="summary-content">
                  <div class="summary-number"><?php echo number_format($genderCounts['Male']); ?></div>
                  <div class="summary-label">Male Pigs</div>
                </div>
              </div>
              <div class="summary-item female">
                <div class="summary-icon">
                  <i class="fa fa-venus"></i>
                </div>
                <div class="summary-content">
                  <div class="summary-number"><?php echo number_format($genderCounts['Female']); ?></div>
                  <div class="summary-label">Female Pigs</div>
                </div>
              </div>
            </div>
          </div>
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
/* Modern Dashboard Styling - Based on New Color Scheme */
.modern-dashboard {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
  position: relative;
  overflow-x: hidden;
}

.modern-dashboard::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: none;
  opacity: 0;
  pointer-events: none;
}

/* Hero Section */
.hero-section {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 30px 38px 20px 38px;
  box-shadow: 0 4px 24px -10px #38598b40;
  border: none;
}

.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.hero-title {
  font-size: 2.2rem;
  font-weight: 800;
  color: #fff;
  margin: 0;
  text-shadow: 0 2px 10px rgba(56,89,139,0.13);
  letter-spacing: -0.5px;
}

.hero-title i {
  margin-right: 15px;
  color: #fff;
}

.hero-subtitle {
  font-size: 1.1rem;
  color: #e6e9f2;
  margin: 10px 0 0 0;
  font-weight: 400;
}

.hero-date {
  display: flex;
  align-items: center;
}

.date-card {
  background: #fff;
  color: #38598b;
  font-weight: 700;
  font-size: 1.06rem;
  border-radius: 20px;
  padding: 11px 22px;
  box-shadow: 0 2px 8px -2px #00000018;
  border: none;
}

.date-card i {
  margin-right: 10px;
  color: #38598b;
}

/* Main Dashboard Layout */
.dashboard-layout {
  display: flex;
  gap: 30px;
  margin: 0 50px 50px 50px;
  align-items: flex-start;
}

/* Left Column - Statistics */
.stats-column {
  flex: 0 0 350px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Right Column - Analytics */
.analytics-column {
  flex: 1;
  min-width: 0;
}

.charts-container {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.stat-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  border: none;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  min-height: 0;
  margin-bottom: 0;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: #38598b;
}

.stat-card.primary-card::before { background: #38598b; }
.stat-card.secondary-card::before { background: #b4c7e7; }
.stat-card.accent-card::before { background: #dedede; }
.stat-card.warning-card::before { background: #ffd89b; }

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 36px -8px #38598b28;
}

.card-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.card-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.8rem;
  color: #fff;
  box-shadow: 0 4px 15px rgba(56,89,139,0.15);
}

.primary-card .card-icon { background: #38598b; }
.secondary-card .card-icon { background: #b4c7e7; color: #38598b; }
.accent-card .card-icon { background: #dedede; color: #333; }
.warning-card .card-icon { background: #ffd89b; color: #38598b; }

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #38598b;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-body {
  position: relative;
}

.main-stat {
  font-size: 2.5rem;
  font-weight: 800;
  color: #38598b;
  margin-bottom: 10px;
  line-height: 1;
}

.card-subtitle {
  font-size: 0.95rem;
  color: #7f8c8d;
  font-weight: 500;
}

.gender-breakdown {
  display: flex;
  gap: 20px;
  margin-top: 15px;
}

.gender-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 1rem;
  font-weight: 600;
}

.gender-item.male { color: #38598b; }
.gender-item.female { color: #c2185b; }

.gender-item i {
  font-size: 1.2rem;
}

.quarantine-details {
  margin-top: 15px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.detail-label {
  color: #7f8c8d;
  font-weight: 500;
}

.detail-value {
  color: #38598b;
  font-weight: 700;
}

/* Analytics Section */
.section-header {
  text-align: left;
  margin-bottom: 30px;
}

.section-title {
  font-size: 2rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  text-shadow: 0 2px 10px #b4c7e740;
}

.section-title i {
  margin-right: 12px;
  color: #38598b;
}

.section-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

.chart-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  border: none;
  transition: all 0.3s ease;
}

.chart-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 40px #38598b18;
}

.chart-header {
  margin-bottom: 25px;
  text-align: center;
}

.chart-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.chart-title i {
  color: #38598b;
}

.chart-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 500;
}

.chart-body {
  position: relative;
}

.chart-container {
  position: relative;
  height: 300px;
  margin-bottom: 20px;
}

.gender-summary {
  display: flex;
  justify-content: space-around;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 2px solid #ecf0f1;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 15px;
  border-radius: 15px;
  background: #f7f8fa;
  transition: all 0.3s ease;
}

.summary-item:hover {
  background: #e9ecef;
  transform: scale(1.05);
}

.summary-item.male {
  background: #b4c7e7;
  color: #38598b;
}

.summary-item.female {
  background: #ffd6e3;
  color: #c2185b;
}

.summary-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #fff;
}

.summary-item.male .summary-icon {
  background: #38598b;
}

.summary-item.female .summary-icon {
  background: #c2185b;
}

.summary-content {
  text-align: left;
}

.summary-number {
  font-size: 1.5rem;
  font-weight: 800;
  color: #38598b;
  line-height: 1;
  margin-bottom: 5px;
}

.summary-label {
  font-size: 0.9rem;
  color: #7f8c8d;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .dashboard-layout {
    flex-direction: column;
    gap: 25px;
    margin: 0 30px 40px 30px;
  }
  
  .stats-column {
    flex: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .analytics-column {
    flex: none;
  }
}

@media (max-width: 768px) {
  .modern-dashboard {
    margin-left: 0;
    padding: 0 0 10px 0;
  }
  
  .hero-section {
    padding: 21px 8px 14px 8px;
    margin-bottom: 24px;
  }
  
  .hero-title {
    font-size: 1.7rem;
  }
  
  .hero-content {
    flex-direction: column;
    text-align: center;
  }
  
  .dashboard-layout {
    margin: 0 10px 20px 10px;
    flex-direction: column;
  }
  
  .stats-column {
    grid-template-columns: 1fr;
    gap: 15px;
  }
  
  .section-title {
    font-size: 1.5rem;
  }
  
  .chart-card {
    padding: 15px;
  }
  
  .gender-summary {
    flex-direction: column;
    gap: 12px;
  }
  
  .summary-item {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.1rem;
  }
  
  .main-stat {
    font-size: 1.5rem;
  }
  
  .chart-container {
    height: 180px;
  }
}
</style>