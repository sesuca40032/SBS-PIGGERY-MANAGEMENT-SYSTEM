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
        <h2><b><i class="fa fa-procedures"></i> Quarantine Overview</b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>

  <!-- Quick Stats -->
  <div class="dashboard-stats-row">
    <?php
    // Quarantine stats
    $totalQuarantined = $db->query("SELECT SUM(num_quarantined) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn();
    $activeQuarantine = $db->query("SELECT COUNT(*) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn();
    $quarantinePens = $db->query("SELECT COUNT(DISTINCT pen_id) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn();
    $recentSymptoms = $db->query("SELECT GROUP_CONCAT(DISTINCT symptoms SEPARATOR ', ') FROM quarantine_batches WHERE date_quarantined >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND symptoms != ''")->fetchColumn();
    ?>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-pig"><i class="fa fa-virus"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$totalQuarantined; ?></div>
        <div class="stat-label">Quarantined Pigs</div>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-batch"><i class="fa fa-layer-group"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$activeQuarantine; ?></div>
        <div class="stat-label">Active Cases</div>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-breed"><i class="fa fa-home"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$quarantinePens; ?></div>
        <div class="stat-label">Pens in Use</div>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="stat-icon stat-weight"><i class="fa fa-notes-medical"></i></div>
      <div>
        <div class="stat-number" style="font-size:1.1rem;"><?php echo $recentSymptoms ? htmlspecialchars($recentSymptoms) : 'None'; ?></div>
        <div class="stat-label">Recent Symptoms</div>
      </div>
    </div>
  </div>

  <!-- Quarantine Table -->
  <div class="dashboard-charts-row">
    <div class="dashboard-chart-col" style="flex: 2;">
      <div class="dashboard-card">
        <h4><i class="fa fa-procedures"></i> Quarantined Batches</h4>
        <div class="w3-responsive">
          <table class="w3-table-all w3-hoverable" id="quarantineTable">
            <thead>
              <tr class="w3-light-grey">
                <th>Date</th>
                <th>Batch ID</th>
                <th>Pen</th>
                <th>Total</th>
                <th>Male</th>
                <th>Female</th>
                <th>Symptoms</th>
                <th>Notes</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $penNames = [];
              $penRes = $db->query("SELECT id, label FROM pens");
              foreach($penRes as $pen) $penNames[$pen['id']] = $pen['label'];

              $q = $db->query("SELECT * FROM quarantine_batches WHERE num_quarantined > 0 ORDER BY date_quarantined DESC, id DESC");
              foreach($q as $row):
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['date_quarantined']); ?></td>
                <td><?php echo htmlspecialchars($row['batch_id']); ?></td>
                <td><?php echo isset($penNames[$row['pen_id']]) ? htmlspecialchars($penNames[$row['pen_id']]) : 'N/A'; ?></td>
                <td><?php echo (int)$row['num_quarantined']; ?></td>
                <td><?php echo (int)$row['num_male']; ?></td>
                <td><?php echo (int)$row['num_female']; ?></td>
                <td><?php echo htmlspecialchars($row['symptoms']); ?></td>
                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                <td>
                  <a href="view-batch.php?batch_id=<?php echo urlencode($row['batch_id']); ?>" class="w3-button w3-small w3-blue w3-round" title="View Batch"><i class="fa fa-eye"></i></a>
                  <a href="edit_quarantine.php?id=<?php echo $row['id']; ?>" class="w3-button w3-small w3-teal w3-round" title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="remove_quarantine.php?id=<?php echo $row['id']; ?>" class="w3-button w3-small w3-red w3-round" title="Remove" onclick="return confirm('Remove from quarantine?');"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Optionally, you can add a side summary chart or info card here -->
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<!-- You can reuse the dashboard.php styles for a consistent look -->
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