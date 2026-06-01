<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Handle PDF download or Print
require_once('vendor/autoload.php'); // dompdf for PDF export (composer require dompdf/dompdf)

function getBatchInfo($batch_id, $db) {
    $stmt = $db->prepare("SELECT * FROM pig_batches WHERE batch_id = ?");
    $stmt->execute([$batch_id]);
    $batch = $stmt->fetch(PDO::FETCH_OBJ);

    $health_stmt = $db->prepare("SELECT * FROM pig_batch_health_records WHERE batch_id = ? ORDER BY record_date DESC");
    $health_stmt->execute([$batch->id]);
    $health_records = $health_stmt->fetchAll(PDO::FETCH_OBJ);

    return ['batch' => $batch, 'health_records' => $health_records];
}

// PDF Download logic
if (isset($_GET['download_pdf']) && !empty($_GET['batch_id'])) {
    $batch_data = getBatchInfo($_GET['batch_id'], $db);

    ob_start();
    include('theme/pdf-batch-history.php'); // create this theme file for PDF content (see below)
    $html = ob_get_clean();

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Batch_".$_GET['batch_id']."_History.pdf", ["Attachment" => true]);
    exit;
}

// Print logic
if (isset($_GET['print']) && !empty($_GET['batch_id'])) {
    $batch_data = getBatchInfo($_GET['batch_id'], $db);
    include('theme/pdf-batch-history.php'); // reuse the PDF template for print
    echo "<script>window.onload = function() { window.print(); }</script>";
    exit;
}
?>

<style>
/* Modern Pig Batch History Styling - Blue Theme */
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

/* Batch Details Section */
.batch-details-container {
  margin: 0 50px 30px 50px;
}

.batch-details-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.batch-details-header {
  margin-bottom: 30px;
}

.batch-details-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.batch-details-title i {
  color: #38598b;
}

.batch-details-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

.details-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px #38598b18;
}

.details-table th {
  background: #38598b;
  color: #fff;
  padding: 15px 20px;
  text-align: left;
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.details-table td {
  padding: 15px 20px;
  border-bottom: 1px solid #f1f3f4;
  font-size: 0.9rem;
  vertical-align: middle;
}

.details-table tr:hover {
  background: #f8f9fa;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 15px;
  margin-top: 20px;
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

/* Health Records Section */
.health-records-container {
  margin: 0 50px 30px 50px;
}

.health-records-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.health-records-header {
  margin-bottom: 30px;
}

.health-records-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.health-records-title i {
  color: #38598b;
}

.health-records-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

.no-data {
  text-align: center;
  padding: 40px;
  color: #6c757d;
  font-style: italic;
}

.no-data i {
  font-size: 2rem;
  margin-bottom: 10px;
  display: block;
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
  
  .table-container, .batch-details-container, .health-records-container {
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
  
  .table-container, .batch-details-container, .health-records-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .table-card, .batch-details-card, .health-records-card {
    padding: 20px;
  }
  
  .modern-table, .details-table {
    font-size: 0.8rem;
  }
  
  .modern-table th, .modern-table td,
  .details-table th, .details-table td {
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
  
  .batch-photo-qrcode-flex {
    flex-direction: column;
    gap: 8px;
  }
}
</style>

<!-- Modern Pig Batch History Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-history"></i>
                    Pig Batch History
                </h1>
                <p class="hero-subtitle">Complete historical records and detailed batch information</p>
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
        // Get batch history statistics
        $totalBatches = $db->query("SELECT COUNT(*) FROM pig_batches")->fetchColumn();
        $completedBatches = $db->query("SELECT COUNT(*) FROM pig_batches WHERE status = 'sold'")->fetchColumn();
        $activeBatches = $db->query("SELECT COUNT(*) FROM pig_batches WHERE status != 'sold' AND status != 'deceased'")->fetchColumn();
        $totalPigs = $db->query("SELECT SUM(total_pigs) FROM pig_batches")->fetchColumn() ?: 0;
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
                <div class="card-title">Completed</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($completedBatches); ?></div>
                <div class="card-subtitle">Successfully sold</div>
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
                <div class="card-subtitle">All pigs in history</div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="card-title">Active</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($activeBatches); ?></div>
                <div class="card-subtitle">Currently managed</div>
            </div>
        </div>
    </div>

    <!-- Batch History Table -->
    <div class="table-container">
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fa fa-list"></i>
                    All Batches History
                </h2>
                <p class="table-subtitle">Complete historical records of all pig batches</p>
            </div>

            <div class="table-actions">
                <div class="search-section">
                    <div class="search-input-group">
                        <input type="text" id="searchInput" placeholder="Search batch history..." class="search-input">
                        <button class="search-btn">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="modern-table" id="batchHistoryTable">
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
                                    <button class="w3-button w3-small w3-light-grey w3-round" onclick="toggleDropdown(event, 'history<?php echo $batch->id; ?>')" aria-expanded="false">
                                        <i class="fa fa-cog"></i> <i class="fa fa-caret-down"></i>
                                    </button>
                                    <div id="history<?php echo $batch->id; ?>" class="w3-dropdown-content w3-bar-block w3-card-4" style="position:absolute;right:0;top:100%;z-index:999;display:none;">
                                        <a href="pig-batch-history.php?batch_id=<?php echo $batch->batch_id; ?>" class="w3-bar-item w3-button"><i class="fa fa-eye"></i> View Full History</a>
                                        <a href="pig-batch-history.php?download_pdf=1&batch_id=<?php echo $batch->batch_id; ?>" class="w3-bar-item w3-button"><i class="fa fa-file-pdf"></i> Download PDF</a>
                                        <a href="pig-batch-history.php?print=1&batch_id=<?php echo $batch->batch_id; ?>" class="w3-bar-item w3-button"><i class="fa fa-print"></i> Print</a>
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

    <!-- Batch Details Section -->
    <?php
    // If batch_id is set, display full details (not in modal, just section)
    if (isset($_GET['batch_id']) && !empty($_GET['batch_id'])) {
        $batch_data = getBatchInfo($_GET['batch_id'], $db);
        $batch = $batch_data['batch'];
        $health_records = $batch_data['health_records'];
    ?>
    <div class="batch-details-container">
        <div class="batch-details-card">
            <div class="batch-details-header">
                <h2 class="batch-details-title">
                    <i class="fa fa-info-circle"></i>
                    Batch Information
                </h2>
                <p class="batch-details-subtitle">Detailed information for batch <?php echo $batch->batch_id; ?></p>
            </div>
            
            <table class="details-table">
                <tr><th>Batch ID</th><td><?php echo $batch->batch_id; ?></td></tr>
                <tr>
                    <th>Photo / QR</th>
                    <td>
                        <div class="batch-photo-qrcode-flex">
                            <span class="batch-photo-frame">
                                <img src="<?php echo $batch->photo ?: 'assets/default_batch.jpg'; ?>" alt="Batch Photo">
                            </span>
                            <?php if (!empty($batch->qr_code) && file_exists($batch->qr_code)): ?>
                            <span class="batch-qrcode-frame">
                                <img src="<?php echo $batch->qr_code; ?>" alt="QR Code">
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <tr><th>Source</th><td><?php echo ucfirst($batch->source); ?></td></tr>
                <tr><th>Sow ID</th><td><?php echo $batch->sow_id ?: 'N/A'; ?></td></tr>
                <tr><th>Total Pigs</th><td><?php echo $batch->total_pigs; ?></td></tr>
                <tr>
                    <th>Gender Ratio</th>
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
                </tr>
                <tr><th>Age</th><td><?php echo $age; ?> days (Born: <?php echo $birthDate->format('M d, Y'); ?>)</td></tr>
                <tr><th>Status</th>
                    <td>
                        <span class="w3-tag w3-<?php 
                            echo $batch->status == 'active' ? 'green' : 
                                 ($batch->status == 'quarantined' ? 'orange' : 'grey'); 
                        ?> w3-round">
                            <?php echo ucfirst($batch->status); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Progress</th>
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
                </tr>
            </table>
            
            <div class="action-buttons">
                <a href="pig-batch-history.php?download_pdf=1&batch_id=<?php echo $batch->batch_id; ?>" class="btn-primary">
                    <i class="fa fa-file-pdf"></i>
                    <span>Download PDF</span>
                </a>
                <a href="pig-batch-history.php?print=1&batch_id=<?php echo $batch->batch_id; ?>" class="btn-secondary">
                    <i class="fa fa-print"></i>
                    <span>Print</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Health Records Section -->
    <div class="health-records-container">
        <div class="health-records-card">
            <div class="health-records-header">
                <h2 class="health-records-title">
                    <i class="fa fa-notes-medical"></i>
                    Recorded Piglet Health Info
                </h2>
                <p class="health-records-subtitle">Health records and medical history for this batch</p>
            </div>
            
            <?php if (count($health_records)): ?>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Date Recorded</th>
                        <th>History / Notes</th>
                        <th>Deceased Count</th>
                        <th>Mortality Rate</th>
                        <th>Deformities</th>
                        <th>Unhealthy Pigs</th>
                        <th>Symptoms</th>
                        <th>Cured</th>
                        <th>Cure Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($health_records as $record): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($record->record_date)); ?></td>
                        <td><?php echo htmlspecialchars($record->history); ?></td>
                        <td><?php echo $record->deceased_count; ?></td>
                        <td><?php echo number_format($record->mortality_rate, 2); ?>%</td>
                        <td><?php echo $record->deformities; ?></td>
                        <td><?php echo $record->unhealthy_pigs; ?></td>
                        <td><?php echo htmlspecialchars($record->symptoms); ?></td>
                        <td>
                            <span class="w3-tag w3-<?php echo $record->cured ? 'green' : 'red'; ?> w3-round">
                                <?php echo $record->cured ? 'Yes' : 'No'; ?>
                            </span>
                        </td>
                        <td><?php echo $record->cure_date ? date('M d, Y', strtotime($record->cure_date)) : '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fa fa-exclamation-circle"></i>
                    <div>No piglet health records found for this batch.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php } ?>
</div>

<script>
// Copy all JS from pig-batches.php for dropdown and search
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
  const rows = document.querySelectorAll('#batchHistoryTable tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(input) ? '' : 'none';
  });
});
</script>

<?php include 'theme/foot.php'; ?>
