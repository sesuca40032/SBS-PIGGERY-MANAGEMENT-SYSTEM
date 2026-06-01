<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Handle nursery transfer action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nursery_transfer'])) {
    $batch_id = $_POST['batch_id'];
    $transfer_type = $_POST['transfer_type'];
    $transfer_date = $_POST['transfer_date'];
    $stmt = $db->prepare("UPDATE pig_batches SET nursery_transfer_type = ?, nursery_transfer_date = ? WHERE id = ?");
    $stmt->execute([$transfer_type, $transfer_date, $batch_id]);
}

// Handle piglet health record form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['health_record'])) {
    $batch_id = $_POST['batch_id'];
    $record_date = $_POST['record_date'];
    $history = $_POST['history'];
    $deceased_count = $_POST['deceased_count'];
    $mortality_rate = $_POST['mortality_rate'];
    $deformities = $_POST['deformities'];
    $deformity_kind = $_POST['deformity_kind'];
    $unhealthy_pigs = $_POST['unhealthy_pigs'];
    $symptoms = $_POST['symptoms'];
    $cured = isset($_POST['cured']) ? 1 : 0;
    $cure_date = $_POST['cure_date'];

    // Create table if not exist (demo only, use SQL migration in production)
    $db->query("CREATE TABLE IF NOT EXISTS pig_batch_health_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        batch_id INT,
        record_date DATE,
        history TEXT,
        deceased_count INT,
        mortality_rate FLOAT,
        deformities INT,
        deformity_kind VARCHAR(255),
        unhealthy_pigs INT,
        symptoms TEXT,
        cured TINYINT(1),
        cure_date DATE
    )");

    // Server-side validation: don't allow deceased_count > total_pigs
    $batch_total_pigs = $db->query("SELECT total_pigs FROM pig_batches WHERE id = $batch_id")->fetch(PDO::FETCH_OBJ);
    if ($deceased_count > $batch_total_pigs->total_pigs) {
        echo "<script>alert('ERROR: Number of piglets died cannot exceed total piglets in the batch.');</script>";
    } else {
        $stmt = $db->prepare("INSERT INTO pig_batch_health_records 
            (batch_id, record_date, history, deceased_count, mortality_rate, deformities, deformity_kind, unhealthy_pigs, symptoms, cured, cure_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$batch_id, $record_date, $history, $deceased_count, $mortality_rate, $deformities, $deformity_kind, $unhealthy_pigs, $symptoms, $cured, $cure_date]);
    }
}
?>

<style>
/* Modern Pig Batches Styling - Blue Color Scheme */
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

/* Action Container */
.action-container {
  margin: 0 50px 40px 50px;
}

.action-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.action-header {
  margin-bottom: 30px;
}

.action-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.action-title i {
  color: #38598b;
}

.action-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

.action-buttons {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
  padding: 12px 30px;
  border: none;
  border-radius: 25px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn-primary {
  background: #38598b;
  color: #fff;
  box-shadow: 0 4px 15px #38598b30;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px #38598b40;
}

.btn-secondary {
  background: #6c757d;
  color: #fff;
  box-shadow: 0 4px 15px #6c757d30;
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px #6c757d40;
}

/* Dropdown Modern */
.dropdown-modern {
  position: relative;
  display: inline-block;
}

.dropdown-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
}

.dropdown-menu-modern {
  position: absolute;
  top: 100%;
  left: 0;
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
  padding: 10px 0;
  min-width: 200px;
  z-index: 1000;
  display: none;
}

.dropdown-modern:hover .dropdown-menu-modern {
  display: block;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 20px;
  color: #38598b;
  text-decoration: none;
  transition: background 0.3s ease;
}

.dropdown-item:hover {
  background: #f8f9fa;
  color: #38598b;
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

/* Table Actions */
.table-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  flex-wrap: wrap;
  gap: 15px;
}

.search-section {
  display: flex;
  align-items: center;
}

.search-input-group {
  display: flex;
  align-items: center;
  background: #fff;
  border: 2px solid #e9ecef;
  border-radius: 25px;
  overflow: hidden;
  transition: border-color 0.3s ease;
}

.search-input-group:focus-within {
  border-color: #38598b;
}

.search-input {
  border: none;
  padding: 10px 15px;
  font-size: 0.9rem;
  outline: none;
  width: 250px;
}

.search-btn {
  background: #38598b;
  color: #fff;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.search-btn:hover {
  background: #2c406b;
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

/* Batch Photo and QR */
.batch-photo-qrcode-flex {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: flex-start;
}

.batch-photo-frame, .batch-qrcode-frame {
  width: 50px;
  height: 50px;
  display: inline-block;
  border: 2px solid #e9ecef;
  background: #f8f9fa;
  overflow: hidden;
  box-sizing: border-box;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.batch-photo-frame:hover, .batch-qrcode-frame:hover {
  border-color: #38598b;
  transform: scale(1.05);
}

.batch-photo-frame img, .batch-qrcode-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.batch-qrcode-frame img {
  object-fit: contain;
}

/* Progress Bar */
.progress-bar-flex {
  display: flex;
  align-items: center;
  gap: 2px;
  border-radius: 8px;
  overflow: hidden;
  background: #f1f1f1;
  min-width: 100px;
  height: 24px;
}

.progress-male {
  background: #38598b;
  color: #fff;
  font-size: 0.8rem;
  text-align: center;
  padding: 4px 6px;
  border-radius: 0;
  font-weight: 600;
}

.progress-female {
  background: #e91e63;
  color: #fff;
  font-size: 0.8rem;
  text-align: center;
  padding: 4px 6px;
  border-radius: 0;
  font-weight: 600;
}

.progress-stage {
  background: #2c406b;
  color: #fff;
  font-size: 0.8rem;
  text-align: center;
  padding: 4px 6px;
  border-radius: 0;
  font-weight: 600;
}

/* Status Badges */
.w3-tag {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.w3-tag.w3-green { background: #4caf50 !important; color: #fff !important; }
.w3-tag.w3-orange { background: #ff9800 !important; color: #fff !important; }
.w3-tag.w3-grey { background: #6c757d !important; color: #fff !important; }
.w3-tag.w3-purple { background: #2c406b !important; color: #fff !important; }
.w3-tag.w3-blue { background: #38598b !important; color: #fff !important; }
.w3-tag.w3-teal { background: #20c997 !important; color: #fff !important; }
.w3-tag.w3-pink { background: #e91e63 !important; color: #fff !important; }
.w3-tag.w3-red { background: #dc3545 !important; color: #fff !important; }

/* Action Buttons */
.w3-dropdown-content {
  min-width: 170px;
  border-radius: 15px;
  box-shadow: 0 8px 32px #38598b18;
  padding: 10px 0;
  background: #fff;
  border: 1px solid #b4c7e7;
}

.w3-bar-item.w3-button {
  padding: 12px 20px;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
}

.w3-bar-item.w3-button:hover {
  background: #f8f9fa !important;
  color: #38598b !important;
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
  
  .action-container, .table-container {
    margin-left: 30px;
    margin-right: 30px;
  }
  
  .action-buttons {
    flex-direction: column;
    align-items: stretch;
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
  
  .action-container, .table-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .table-card {
    padding: 20px;
  }
  
  .modern-table {
    font-size: 0.8rem;
  }
  
  .modern-table th,
  .modern-table td {
    padding: 10px 8px;
  }
  
  .search-input {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .action-card {
    padding: 20px;
  }
  
  .batch-photo-qrcode-flex {
    flex-direction: column;
    gap: 8px;
  }
}
</style>

<!-- Modern Pig Batches Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-paw"></i>
                    Pig Batch Management
                </h1>
                <p class="hero-subtitle">Track and manage your pig batches from birth to market</p>
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
        // Get batch statistics
        $totalBatches = $db->query("SELECT COUNT(*) FROM pig_batches")->fetchColumn();
        $activeBatches = $db->query("SELECT COUNT(*) FROM pig_batches WHERE status != 'sold' AND status != 'deceased'")->fetchColumn();
        $totalPigs = $db->query("SELECT SUM(total_pigs) FROM pig_batches")->fetchColumn() ?: 0;
        $farrowingBatches = $db->query("SELECT COUNT(*) FROM pig_batches WHERE status = 'farrowing'")->fetchColumn();
        ?>
        
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-list-alt"></i>
                </div>
                <div class="card-title">Total Batches</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalBatches); ?></div>
                <div class="card-subtitle">All time batches</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="card-title">Active Batches</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($activeBatches); ?></div>
                <div class="card-subtitle">Currently managed</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-paw"></i>
                </div>
                <div class="card-title">Total Pigs</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalPigs); ?></div>
                <div class="card-subtitle">All pigs in system</div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-baby"></i>
                </div>
                <div class="card-title">Farrowing</div>
      </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($farrowingBatches); ?></div>
                <div class="card-subtitle">Newborn batches</div>
            </div>
        </div>
    </div>

    <!-- Action Controls -->
    <div class="action-container">
        <div class="action-card">
            <div class="action-header">
                <h3 class="action-title">
                    <i class="fa fa-cogs"></i>
                    Batch Management
                </h3>
                <p class="action-subtitle">Manage your pig batches and operations</p>
            </div>
            
            <div class="action-buttons">
                <button id="scan-qr-btn-header" title="Scan QR code" class="btn-primary">
                    <i class="fa fa-qrcode"></i>
                    <span>Scan Batch QR</span>
                </button>
                
                <div class="dropdown-modern">
                    <button class="btn-secondary dropdown-toggle">
                        <i class="fa fa-plus"></i>
                        <span>Add New Batch</span>
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-modern">
                        <a href="add-batch.php?source=farm" class="dropdown-item">
                            <i class="fa fa-home"></i>
                            <span>Farm Production</span>
                        </a>
                        <a href="add-batch.php?source=external" class="dropdown-item">
                            <i class="fa fa-truck"></i>
                            <span>External Purchase</span>
                        </a>
                    </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Batch Table -->
    <div class="table-container">
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fa fa-list"></i>
                    Current Batches
                </h2>
                <p class="table-subtitle">Manage and monitor your pig batches</p>
            </div>

            <div class="table-actions">
                <div class="search-section">
                    <div class="search-input-group">
                        <input type="text" id="searchInput" placeholder="Search batches..." class="search-input">
                        <button class="search-btn">
                            <i class="fa fa-search"></i>
                        </button>
      </div>
      </div>
    </div>

            <div class="table-wrapper">
                <table class="modern-table" id="batchTable">
        <thead>
          <tr>
            <th>Batch ID</th>
            <th>Photo / QR</th>
            <th>Source</th>
            <th>Sow ID</th>
            <th>Total Pigs</th>
            <th>Gender Ratio</th>
            <th>Age</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $all_batches = $db->query("SELECT * FROM pig_batches ORDER BY batch_date DESC");
          $batches = $all_batches->fetchAll(PDO::FETCH_OBJ);
          foreach($batches as $batch) {
            $birthDate = new DateTime($batch->birth_date);
            $today = new DateTime();
            $age = $today->diff($birthDate)->days;

            $farrowing_to_nursery_eligible = ($age >= 6 && $age <= 21 && empty($batch->nursery_transfer_date));

            if (!empty($batch->nursery_transfer_date)) {
                $nurseryDate = new DateTime($batch->nursery_transfer_date);
                $age_in_nursery = $today->diff($nurseryDate)->days;
                $stage = "Nursery";
                $progress = ($age_in_nursery / 21) * 100;
                if ($progress > 100) $progress = 100;
            } else if ($age <= 21) {
                $stage = "Farrowing";
                $progress = ($age / 21) * 100;
            } elseif ($age <= 42) {
                $stage = "Nursery";
                $progress = (($age - 21) / 21) * 100;
            } elseif ($age <= 70) {
                $stage = "Grower";
                $progress = (($age - 42) / 28) * 100;
            } else {
                $stage = "Finisher";
                $progress = min(100, (($age - 70) / 110) * 100);
            }

            if ($batch->total_pigs > 0) {
                $male_percentage = ($batch->male_count / $batch->total_pigs) * 100;
                $female_percentage = ($batch->female_count / $batch->total_pigs) * 100;
            } else {
                $male_percentage = 0;
                $female_percentage = 0;
            }
          ?>
          <tr>
            <td><?php echo $batch->batch_id; ?></td>
            <td>
              <div class="batch-photo-qrcode-flex">
                <span class="batch-photo-frame">
                  <img src="<?php echo $batch->photo ?: 'assets/default_batch.jpg'; ?>" alt="Batch Photo">
                </span>
                <?php if (!empty($batch->qr_code) && file_exists($batch->qr_code)): ?>
                <span class="batch-qrcode-frame">
                  <a href="view-batch.php?batch_id=<?php echo $batch->batch_id?>" title="View Batch QR">
                    <img src="<?php echo $batch->qr_code; ?>" alt="QR Code">
                  </a>
                </span>
                <?php else: ?>
                  <span class="batch-qrcode-frame" style="display:flex;align-items:center;justify-content:center;color:#bbb;font-size:1.5em;">
                    <i class="fa fa-qrcode"></i>
                  </span>
                <?php endif; ?>
              </div>
            </td>
            <td><?php echo ucfirst($batch->source); ?></td>
            <td><?php echo $batch->sow_id ?: 'N/A'; ?></td>
            <td>
              <?php echo $batch->total_pigs; ?>
              <?php if ($batch->deceased_count > 0): ?>
                <span class="w3-tag w3-red w3-round w3-small">-<?php echo $batch->deceased_count; ?></span>
              <?php endif; ?>
            </td>
            <td>
              <div class="progress-bar-flex">
                <div class="progress-male" style="width:<?php echo $male_percentage; ?>%">
                  <?php echo $batch->male_count; ?>M
                </div>
                <div class="progress-female" style="width:<?php echo $female_percentage; ?>%">
                  <?php echo $batch->female_count; ?>F
                </div>
              </div>
            </td>
            <td>
              <?php echo $age; ?> days
              <div class="w3-small"><?php echo $birthDate->format('M d, Y'); ?></div>
            </td>
            <td>
              <span class="w3-tag w3-<?php 
                echo $batch->status == 'active' ? 'green' : 
                     ($batch->status == 'quarantined' ? 'orange' : 'grey'); 
              ?> w3-round">
                <?php echo ucfirst($batch->status); ?>
              </span>
            </td>
            <td>
              <div class="progress-bar-flex">
                <div class="w3-tag w3-<?php 
                  echo $stage == 'Farrowing' ? 'purple' : 
                       ($stage == 'Nursery' ? 'blue' : 
                       ($stage == 'Grower' ? 'teal' : 'green')); 
                ?> w3-round" style="width:<?php echo $progress; ?>%">
                  <?php echo $stage; ?> (<?php echo round($progress); ?>%)
                  <?php
                  if (!empty($batch->nursery_transfer_date)) {
                    echo '<span class="w3-small" style="margin-left:6px;">Transferred ' . date('M d, Y', strtotime($batch->nursery_transfer_date)) . '</span>';
                    if ($batch->nursery_transfer_type == 'early') {
                      echo '<span class="w3-tag w3-orange w3-small" style="margin-left:6px;">Early</span>';
                    } elseif ($batch->nursery_transfer_type == 'late') {
                      echo '<span class="w3-tag w3-red w3-small" style="margin-left:6px;">Late</span>';
                    } else {
                      echo '<span class="w3-tag w3-green w3-small" style="margin-left:6px;">On Schedule</span>';
                    }
                  }
                  ?>
                </div>
              </div>
            </td>
            <td>
              <div class="w3-dropdown-click" style="position:relative;">
                <button class="w3-button w3-small w3-light-grey w3-round" onclick="toggleDropdown(event, 'batch<?php echo $batch->id; ?>')" aria-expanded="false">
                  <i class="fa fa-cog"></i> <i class="fa fa-caret-down"></i>
                </button>
                <div id="batch<?php echo $batch->id; ?>" class="w3-dropdown-content w3-bar-block w3-card-4" style="position:absolute;right:0;top:100%;z-index:999;display:none;">
                  <a href="view-batch.php?id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-eye"></i> View</a>
                  <a href="edit-batch.php?id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit</a>
                  <a href="medication.php?batch_id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-medkit"></i> Medication</a>
                  <a href="sale.php?batch_id=<?php echo $batch->batch_id; ?>" class="w3-bar-item w3-button">
                    <i class="fa fa-dollar"></i> Mark for Sale
                  </a>
                  <a href="#" class="w3-bar-item w3-button"
                     onclick="openQuarantineModal({
                       batch_id: '<?php echo $batch->batch_id; ?>',
                       total_pigs: <?php echo $batch->total_pigs; ?>,
                       male_count: <?php echo $batch->male_count; ?>,
                       female_count: <?php echo $batch->female_count; ?>
                     }); return false;">
                     <i class="fa fa-ambulance"></i> Move to Quarantine
                  </a>
                  <?php if ($farrowing_to_nursery_eligible): ?>
                  <button type="button" class="w3-bar-item w3-button" style="background:#2196f3;color:#fff;margin-top:5px;" onclick="openNurseryTransferModal(<?php echo $batch->id; ?>);">
                    <i class="fa fa-arrow-right"></i> Move Batch to Nursery
                  </button>
                  <?php endif; ?>
                  <button type="button" class="w3-bar-item w3-button" style="background:#e91e63;color:#fff;margin-top:5px;" onclick="openHealthRecordModal(<?php echo $batch->id; ?>);">
                    <i class="fa fa-notes-medical"></i> Record Piglet Health Info
                  </button>
                </div>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
            </div>
        </div>
    </div>
    </div>
    <!-- Piglet Health Record Modal -->
    <div id="healthRecordModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(30,40,60,.25);z-index:99999;overflow:auto;">
      <div style="background:#fff;border-radius:14px;max-width:520px;margin:70px auto;box-shadow:0 4px 24px rgba(30,40,60,.12);padding:28px 24px;position:relative;max-height:90vh;overflow-y:auto;">
        <form method="POST" id="healthRecordForm">
          <input type="hidden" name="batch_id" id="healthRecordBatchId">
          <input type="hidden" name="health_record" value="1">
          <h3 style="margin-bottom:18px;color:#e91e63;"><i class="fa fa-notes-medical"></i> Piglet Health Record</h3>
          <div style="margin-bottom:14px;">
            <label for="record_date"><b>Date Recorded:</b></label>
            <input type="date" name="record_date" id="healthRecordDate" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div style="margin-bottom:14px;">
            <label for="history"><b>Piglet History / Notes:</b></label>
            <textarea name="history" id="healthRecordHistory" class="form-control" rows="2" placeholder="Notes, background, etc."></textarea>
          </div>
          <div style="margin-bottom:14px;">
            <label for="deceased_count"><b>Piglets Died:</b></label>
            <input type="number" name="deceased_count" id="healthRecordDeceased" class="form-control" min="0" value="0" required>
            <span id="healthRecordDeceasedError" style="color:red;display:none;font-size:0.96em;"></span>
          </div>
          <div style="margin-bottom:14px;">
            <label for="mortality_rate"><b>Mortality Rate (%):</b></label>
            <input type="number" step="0.01" name="mortality_rate" id="healthRecordMortality" class="form-control" placeholder="Automatically calculated" readonly>
          </div>
          <div style="margin-bottom:14px;">
            <label for="deformities"><b>Number of Piglets with Deformities:</b></label>
            <input type="number" name="deformities" id="healthRecordDeformities" class="form-control" min="0">
          </div>
          <div style="margin-bottom:14px;">
            <label for="deformity_kind"><b>Deformity Kind(s):</b></label>
            <input type="text" name="deformity_kind" id="healthRecordDeformityKind" class="form-control" placeholder="e.g. cleft palate, hernia">
          </div>
          <div style="margin-bottom:14px;">
            <label for="unhealthy_pigs"><b>Unhealthy Piglets:</b></label>
            <input type="number" name="unhealthy_pigs" id="healthRecordUnhealthy" class="form-control" min="0">
          </div>
          <div style="margin-bottom:14px;">
            <label for="symptoms"><b>Symptoms / Observations:</b></label>
            <textarea name="symptoms" id="healthRecordSymptoms" class="form-control" rows="2" placeholder="Describe symptoms, behavior, etc."></textarea>
          </div>
          <div style="margin-bottom:14px;">
            <label for="cured"><input type="checkbox" name="cured" id="healthRecordCured"> <b>Cured?</b></label>
          </div>
          <div style="margin-bottom:20px;">
            <label for="cure_date"><b>Date Cured (if applicable):</b></label>
            <input type="date" name="cure_date" id="healthRecordCureDate" class="form-control">
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <button type="button" class="btn btn-secondary" onclick="closeHealthRecordModal()">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Record</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Nursery Transfer Modal -->
    <div id="nurseryTransferModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(30,40,60,.25);z-index:99999;">
      <div style="background:#fff;border-radius:14px;max-width:420px;margin:80px auto;box-shadow:0 4px 24px rgba(30,40,60,.12);padding:28px 24px;position:relative;">
        <form method="POST" id="nurseryTransferForm">
          <input type="hidden" name="batch_id" id="nurseryTransferBatchId">
          <input type="hidden" name="nursery_transfer" value="1">
          <h3 style="margin-bottom:18px;color:#38598b;"><i class="fa fa-arrow-right"></i> Move Batch to Nursery</h3>
          <div style="margin-bottom:14px;">
            <label for="transfer_type"><b>Schedule:</b></label>
            <select name="transfer_type" id="nurseryTransferType" class="form-control" required>
              <option value="scheduled">On schedule</option>
              <option value="early">Early</option>
              <option value="late">Late</option>
            </select>
          </div>
          <div style="margin-bottom:20px;">
            <label for="transfer_date"><b>Date of Transfer:</b></label>
            <input type="date" name="transfer_date" id="nurseryTransferDate" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <button type="button" class="btn btn-secondary" onclick="closeNurseryTransferModal()">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
    <!-- ...pagination and summary cards... -->
    <!-- ...summary cards... -->
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let qrScannerHeader = null;
const headerScanBtn = document.getElementById("scan-qr-btn-header");
const qrReaderHeader = document.createElement('div');
qrReaderHeader.id = "qr-reader-header";
qrReaderHeader.style = "width:250px;display:none;position:absolute;z-index:9999;background:#fff;border-radius:6px;box-shadow:0 2px 10px rgba(0,0,0,0.2);";
document.body.appendChild(qrReaderHeader);
headerScanBtn.addEventListener("click", function(e){
  e.preventDefault();
  if (qrScannerHeader) {
    qrScannerHeader.clear();
    qrReaderHeader.style.display = qrReaderHeader.style.display === 'none' ? 'block' : 'none';
    return;
  }
  qrReaderHeader.style.display = 'block';
  qrScannerHeader = new Html5QrcodeScanner("qr-reader-header", {
      fps: 10,
      qrbox: 180
  });
  qrScannerHeader.render(
      (decodedText) => {
        let batchId = null;
        try {
          let url = new URL(decodedText, window.location.origin);
          let params = new URLSearchParams(url.search);
          if (params.has('batch_id')) {
            batchId = params.get('batch_id');
          }
        } catch (e) {
          let match = decodedText.match(/batch_id=([A-Za-z0-9\-_]+)/);
          if (match) batchId = match[1];
        }
        if (batchId) {
          window.location.href = 'view-batch.php?batch_id=' + encodeURIComponent(batchId);
        } else {
          alert("QR not recognized for batch.");
        }
        qrScannerHeader.clear();
        qrReaderHeader.style.display = 'none';
      },
      (errorMessage) => {}
  );
});
document.addEventListener('mousedown', function(e){
  if (qrReaderHeader.style.display === 'block' && !qrReaderHeader.contains(e.target) && e.target !== headerScanBtn) {
    qrReaderHeader.style.display = 'none';
    if (qrScannerHeader) qrScannerHeader.clear();
  }
});
function toggleDropdown(event, id) {
  event.stopPropagation();
  document.querySelectorAll('.w3-dropdown-content').forEach(function(el) {
    el.style.display = 'none';
  });
  var x = document.getElementById(id);
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
document.addEventListener('click', function(e){
  document.querySelectorAll('.w3-dropdown-content').forEach(function(el) {
    el.style.display = 'none';
  });
});
document.getElementById('searchInput').addEventListener('keyup', function() {
  const input = this.value.toLowerCase();
  const rows = document.querySelectorAll('#batchTable tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(input) ? '' : 'none';
  });
});

// Mortality rate calculation and validation for Piglet Health Record
function openHealthRecordModal(batchId) {
  document.getElementById('healthRecordBatchId').value = batchId;
  document.getElementById('healthRecordModal').style.display = 'block';
  document.getElementById('healthRecordDate').value = (new Date()).toISOString().substring(0,10);

  <?php
  $batchTotalPigs = [];
  foreach ($batches as $batch) {
    $batchTotalPigs[$batch->id] = $batch->total_pigs;
  }
  ?>
  window.batchTotalPigs = <?php echo json_encode($batchTotalPigs); ?>;

  document.getElementById('healthRecordDeceased').value = 0;
  document.getElementById('healthRecordMortality').value = "0.00";
  document.getElementById('healthRecordDeceasedError').style.display = "none";
}

document.getElementById('healthRecordDeceased').addEventListener('input', function() {
  var deceased = parseInt(this.value) || 0;
  var batchId = document.getElementById('healthRecordBatchId').value;
  var totalPigs = window.batchTotalPigs[batchId] ? parseInt(window.batchTotalPigs[batchId]) : 0;
  var errorSpan = document.getElementById('healthRecordDeceasedError');
  if (deceased < 0) {
    errorSpan.textContent = "Number cannot be negative.";
    errorSpan.style.display = "inline";
    this.value = 0;
    deceased = 0;
  } else if (deceased > totalPigs) {
    errorSpan.textContent = "Cannot exceed total piglets in batch (" + totalPigs + ").";
    errorSpan.style.display = "inline";
    this.value = totalPigs;
    deceased = totalPigs;
  } else {
    errorSpan.textContent = "";
    errorSpan.style.display = "none";
  }
  var mortality = (totalPigs > 0) ? ((deceased / totalPigs) * 100) : 0;
  document.getElementById('healthRecordMortality').value = mortality.toFixed(2);
});

function closeHealthRecordModal() {
  document.getElementById('healthRecordModal').style.display = 'none';
}

document.getElementById('healthRecordForm').addEventListener('submit', function(e) {
  var deceased = parseInt(document.getElementById('healthRecordDeceased').value) || 0;
  var batchId = document.getElementById('healthRecordBatchId').value;
  var totalPigs = window.batchTotalPigs[batchId] ? parseInt(window.batchTotalPigs[batchId]) : 0;
  var errorSpan = document.getElementById('healthRecordDeceasedError');
  if (deceased > totalPigs) {
    errorSpan.textContent = "Cannot exceed total piglets in batch (" + totalPigs + ").";
    errorSpan.style.display = "inline";
    e.preventDefault();
    return false;
  }
  closeHealthRecordModal();
});

// Nursery Transfer Modal logic
function openNurseryTransferModal(batchId) {
  document.getElementById('nurseryTransferBatchId').value = batchId;
  document.getElementById('nurseryTransferModal').style.display = 'block';
  document.getElementById('nurseryTransferDate').value = (new Date()).toISOString().substring(0,10);
}
function closeNurseryTransferModal() {
  document.getElementById('nurseryTransferModal').style.display = 'none';
}
document.getElementById('nurseryTransferForm').addEventListener('submit', function() {
  closeNurseryTransferModal();
});
</script>

<?php include 'theme/foot.php'; ?>

<?php
// ---- MIGRATION NOTE ----
// You must add this table for comprehensive piglet health records:
// CREATE TABLE pig_batch_health_records (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   batch_id INT,
//   record_date DATE,
//   history TEXT,
//   deceased_count INT,
//   mortality_rate FLOAT,
//   deformities INT,
//   deformity_kind VARCHAR(255),
//   unhealthy_pigs INT,
//   symptoms TEXT,
//   cured TINYINT(1),
//   cure_date DATE
// );
?>