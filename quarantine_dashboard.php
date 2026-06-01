<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- Modern Quarantine Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-procedures"></i>
                    Quarantine Overview
                </h1>
                <p class="hero-subtitle">Monitor and manage quarantined pigs with health tracking</p>
            </div>
            <div class="hero-stats">
                <div class="stat-badge">
                    <i class="fa fa-calendar"></i>
                    <span><?php echo date('F j, Y'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <?php
        // Quarantine stats with error handling
        try {
            $totalQuarantined = $db->query("SELECT SUM(num_quarantined) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn() ?: 0;
            $activeQuarantine = $db->query("SELECT COUNT(*) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn() ?: 0;
            $quarantinePens = $db->query("SELECT COUNT(DISTINCT pen_id) FROM quarantine_batches WHERE num_quarantined > 0")->fetchColumn() ?: 0;
            $recentSymptoms = $db->query("SELECT GROUP_CONCAT(DISTINCT symptoms SEPARATOR ', ') FROM quarantine_batches WHERE date_quarantined >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND symptoms != ''")->fetchColumn();
            $recoveredCount = $db->query("SELECT COUNT(*) FROM quarantine_batches WHERE date_quarantined <= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND num_quarantined = 0")->fetchColumn() ?: 0;
        } catch (Exception $e) {
            $totalQuarantined = 0;
            $activeQuarantine = 0;
            $quarantinePens = 0;
            $recentSymptoms = '';
            $recoveredCount = 0;
        }
        ?>
        
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-virus"></i>
                </div>
                <div class="card-title">Quarantined Pigs</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalQuarantined); ?></div>
                <div class="card-subtitle">Currently isolated</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-layer-group"></i>
                </div>
                <div class="card-title">Active Cases</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($activeQuarantine); ?></div>
                <div class="card-subtitle">Ongoing quarantine</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-home"></i>
                </div>
                <div class="card-title">Pens in Use</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($quarantinePens); ?></div>
                <div class="card-subtitle">Quarantine pens</div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-heartbeat"></i>
                </div>
                <div class="card-title">Recovered</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($recoveredCount); ?></div>
                <div class="card-subtitle">Recently cleared</div>
            </div>
        </div>
    </div>

    <!-- Quarantine Table -->
    <div class="table-container">
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fa fa-procedures"></i>
                    Quarantined Batches
                </h2>
                <p class="table-subtitle">Monitor and manage all quarantined pig batches</p>
            </div>

            <div class="table-wrapper">
                <table class="modern-table" id="quarantineTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Batch ID</th>
                            <th>Pen</th>
                            <th>Total</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Symptoms</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $penNames = [];
                            $penRes = $db->query("SELECT id, label FROM pens");
                            foreach($penRes as $pen) $penNames[$pen['id']] = $pen['label'];

                            $q = $db->query("SELECT * FROM quarantine_batches WHERE num_quarantined > 0 ORDER BY date_quarantined DESC, id DESC");
                            foreach($q as $row):
                        ?>
                        <tr>
                            <td>
                                <div class="date-cell">
                                    <i class="fa fa-calendar"></i>
                                    <span><?php echo date('M d, Y', strtotime($row['date_quarantined'])); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="batch-id"><?php echo htmlspecialchars($row['batch_id']); ?></span>
                            </td>
                            <td>
                                <div class="pen-cell">
                                    <i class="fa fa-home"></i>
                                    <span><?php echo isset($penNames[$row['pen_id']]) ? htmlspecialchars($penNames[$row['pen_id']]) : 'N/A'; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="quarantine-count"><?php echo (int)$row['num_quarantined']; ?></span>
                            </td>
                            <td>
                                <span class="gender-count male"><?php echo (int)$row['num_male']; ?>M</span>
                            </td>
                            <td>
                                <span class="gender-count female"><?php echo (int)$row['num_female']; ?>F</span>
                            </td>
                            <td>
                                <div class="symptoms-cell">
                                    <?php if (!empty($row['symptoms'])): ?>
                                        <span class="symptoms-badge">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <?php echo htmlspecialchars($row['symptoms']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="no-symptoms">None</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="notes-cell">
                                    <?php if (!empty($row['notes'])): ?>
                                        <span class="notes-text" title="<?php echo htmlspecialchars($row['notes']); ?>">
                                            <?php echo strlen($row['notes']) > 30 ? substr(htmlspecialchars($row['notes']), 0, 30) . '...' : htmlspecialchars($row['notes']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="no-notes">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="view-batch.php?batch_id=<?php echo urlencode($row['batch_id']); ?>" class="btn-view" title="View Batch">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="edit_quarantine.php?id=<?php echo $row['id']; ?>" class="btn-edit" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="remove_quarantine.php?id=<?php echo $row['id']; ?>" class="btn-delete" title="Remove" onclick="return confirm('Remove from quarantine?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endforeach; 
                        } catch (Exception $e) {
                            echo '<tr><td colspan="9" class="error-message">Error loading quarantine data: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<style>
/* Modern Quarantine Dashboard Styling - Blue Theme */
.modern-dashboard {
  font-family: 'Segoe UI', 'Roboto', 'Inter', Arial, sans-serif;
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
  color: #ffd700;
}

.hero-subtitle {
  font-size: 1.1rem;
  color: #e6e9f2;
  margin: 10px 0 0 0;
  font-weight: 400;
}

.hero-stats {
  display: flex;
  align-items: center;
}

.stat-badge {
  background: #fff;
  color: #38598b;
  font-weight: 700;
  font-size: 1.06rem;
  border-radius: 20px;
  padding: 11px 22px;
  box-shadow: 0 2px 8px -2px #00000018;
  border: none;
}

.stat-badge i {
  margin-right: 10px;
  color: #38598b;
}

/* Statistics Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin: 0 50px 50px 50px;
}

.stat-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
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

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px #38598b28;
}

.primary-card::before { background: #38598b; }
.success-card::before { background: #28a745; }
.info-card::before { background: #2c406b; }
.warning-card::before { background: #b4c7e7; }

.primary-card .card-icon { background: #38598b; }
.success-card .card-icon { background: #28a745; }
.info-card .card-icon { background: #2c406b; }
.warning-card .card-icon { background: #b4c7e7; color: #38598b; }

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
  box-shadow: 0 4px 15px #38598b18;
}

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
  font-size: 3rem;
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

/* Table Container */
.table-container {
  margin: 0 50px 30px 50px;
}

.table-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.table-header {
  margin-bottom: 30px;
}

.table-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.table-title i {
  color: #38598b;
}

.table-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

/* Modern Table */
.table-wrapper {
  overflow-x: auto;
  border-radius: 15px;
  box-shadow: 0 4px 15px #38598b18;
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
}

.modern-table thead {
  background: #38598b;
  color: #fff;
}

.modern-table th {
  padding: 15px 12px;
  text-align: left;
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table td {
  padding: 15px 12px;
  border-bottom: 1px solid #f1f3f4;
  font-size: 0.9rem;
  vertical-align: middle;
}

.modern-table tbody tr {
  transition: background 0.3s ease;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
}

/* Quarantine Specific Styles */
.date-cell {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  color: #38598b;
}

.date-cell i {
  color: #38598b;
  font-size: 0.9rem;
}

.batch-id {
  font-weight: 600;
  color: #38598b;
  font-size: 0.9rem;
}

.pen-cell {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  color: #38598b;
}

.pen-cell i {
  color: #2c406b;
  font-size: 0.9rem;
}

.quarantine-count {
  font-weight: 700;
  color: #dc3545;
  font-size: 1.1rem;
}

.gender-count {
  display: inline-flex;
  align-items: center;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.gender-count.male {
  background: #e3f2fd;
  color: #1976d2;
}

.gender-count.female {
  background: #fce4ec;
  color: #c2185b;
}

.symptoms-cell {
  max-width: 200px;
}

.symptoms-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #fff3cd;
  color: #856404;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 500;
  border: 1px solid #ffeaa7;
}

.symptoms-badge i {
  color: #f39c12;
}

.no-symptoms {
  color: #6c757d;
  font-style: italic;
  font-size: 0.85rem;
}

.notes-cell {
  max-width: 150px;
}

.notes-text {
  color: #38598b;
  font-size: 0.85rem;
  line-height: 1.3;
}

.no-notes {
  color: #6c757d;
  font-style: italic;
}

.action-buttons {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-view, .btn-edit, .btn-delete {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-view {
  background: #e3f2fd;
  color: #1976d2;
}

.btn-view:hover {
  background: #bbdefb;
  transform: translateY(-1px);
}

.btn-edit {
  background: #e8f5e8;
  color: #2e7d32;
}

.btn-edit:hover {
  background: #c8e6c9;
  transform: translateY(-1px);
}

.btn-delete {
  background: #ffebee;
  color: #d32f2f;
}

.btn-delete:hover {
  background: #ffcdd2;
  transform: translateY(-1px);
}

.error-message {
  text-align: center;
  color: #dc3545;
  font-style: italic;
  padding: 20px;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 0 30px 40px 30px;
  }
  
  .hero-content {
    flex-direction: column;
    text-align: center;
  }
  
  .table-container {
    margin-left: 30px;
    margin-right: 30px;
  }
}

@media (max-width: 768px) {
  .modern-dashboard {
    margin-left: 0;
  }
  
  .hero-section {
    padding: 30px 20px 20px 20px;
    margin-bottom: 30px;
  }
  
  .hero-title {
    font-size: 2.2rem;
  }
  
  .stats-grid {
    margin: 0 20px 30px 20px;
    grid-template-columns: 1fr;
  }
  
  .table-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .table-card {
    padding: 20px;
  }
  
  .modern-table {
    font-size: 0.8rem;
  }
  
  .modern-table th, .modern-table td {
    padding: 10px 8px;
  }
  
  .action-buttons {
    flex-direction: column;
    gap: 5px;
  }
  
  .btn-view, .btn-edit, .btn-delete {
    width: 100%;
    height: 36px;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .symptoms-cell, .notes-cell {
    max-width: 100px;
  }
}
</style>
