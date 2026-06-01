<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<style>
:root {
    --primary-color: #38598b;
    --secondary-color: #b4c7e7;
    --accent-color: #ffd700;
    --dark-color: #2c3e50;
    --light-color: #f8f9fa;
    --success-color: #28a745;
    --warning-color: #ff9800;
    --info-color: #2c406b;
    --danger-color: #d32f2f;
}

.dashboard-main {
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    min-height: 100vh;
    background: #f7f8fa;
    padding: 0 0 40px 0;
    position: relative;
}

.dashboard-main::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: url('img/DSC_0054.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
    opacity: 0.1;
    z-index: -1;
    pointer-events: none;
}

.dashboard-header {
    background: #38598b;
    color: #fff;
    border-radius: 0 0 18px 18px;
    margin-bottom: 34px;
    padding: 30px 40px;
    box-shadow: 0 4px 24px -10px #38598b40;
    border: none;
}

.dashboard-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dashboard-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-header {
    max-width: 60px;
    height: auto;
}

.dashboard-title h2 {
    font-size: 2.2rem;
    font-weight: 800;
    letter-spacing: 0.2px;
    margin-bottom: 0;
    color: #fff;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    letter-spacing: -0.5px;
}

.dashboard-badge {
    background: #fff;
    color: #38598b;
    font-weight: 700;
    font-size: 1.06rem;
    border-radius: 20px;
    padding: 11px 22px;
    box-shadow: 0 2px 8px -2px #00000018;
    border: none;
}

.dashboard-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin: 0 20px;
    border: 1px solid #b4c7e7;
}

.btn-primary, .w3-button.w3-blue {
    background: #38598b !important;
    color: #fff !important;
    font-size: 1.09rem !important;
    font-weight: 600;
    border-radius: 15px !important;
    box-shadow: 0 4px 15px #38598b30 !important;
    border: none !important;
    padding: 12px 24px !important;
    transition: all 0.3s ease !important;
}

.btn-primary:hover, .w3-button.w3-blue:hover {
    background: #2c406b !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px #38598b40 !important;
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #45a049 100%) !important;
    color: #fff !important;
    font-size: 1.09rem !important;
    font-weight: 600;
    border-radius: 10px !important;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3) !important;
    border: none !important;
    padding: 12px 24px !important;
    transition: all 0.3s ease !important;
}

.btn-success:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4) !important;
}

.table {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.table thead th, .w3-table th {
    font-weight: 700;
    color: #fff;
    background: #38598b;
    border-bottom: 2px solid #2c406b;
    padding: 15px 12px;
    font-size: 0.95rem;
}

.table tr td, .w3-table td {
    font-size: 1.07rem;
    vertical-align: middle;
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.table-hover tbody tr:hover, .w3-table-hover tr:hover {
    background: #e6e9f2;
    transform: scale(1.01);
    transition: all 0.3s ease;
}

.badge {
    padding: 8px 16px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #45a049 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.badge-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, #ffb74d 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
}

.badge-info {
    background: linear-gradient(135deg, #2c406b, #38598b);
    color: #fff;
    box-shadow: 0 2px 8px #38598b30;
}

.badge-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #f44336 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(211, 47, 47, 0.3);
}

.badge-secondary {
    background: linear-gradient(135deg, #aaa 0%, #bbb 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(170, 170, 170, 0.3);
}

.dropdown-menu {
    min-width: 200px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border: 1px solid #b4c7e7;
    background: #fff;
    backdrop-filter: blur(10px);
}

.dropdown-menu li a {
    padding: 12px 20px;
    transition: all 0.3s ease;
    color: var(--dark-color);
}

.dropdown-menu li a:hover {
    background: #38598b;
    color: white;
    transform: translateX(5px);
}

.sow-photo-qrcode-flex {
    display: flex;
    align-items: center;
    gap: 14px;
    justify-content: flex-start;
}

.sow-photo-frame {
    width: 70px;
    height: 70px;
    display: inline-block;
    border: 3px solid #38598b;
    background: #f0f0f0;
    overflow: hidden;
    box-sizing: border-box;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.sow-photo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 7px;
}

.sow-qrcode-frame {
    width: 70px;
    height: 70px;
    display: inline-block;
    background: #fff;
    border: 3px solid #ffd700;
    box-sizing: border-box;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.sow-qrcode-frame img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 7px;
}

.progress-bar {
    width: 100px;
    background: #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-fill {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: #fff;
    text-align: center;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 4px 0;
    transition: width 0.3s ease;
}

#qr-reader {
    width: 300px;
    margin-bottom: 22px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

@media (max-width: 1100px) {
    .dashboard-charts-row {
        flex-direction: column;
        gap: 18px;
    }
    .dashboard-card {
        min-width: unset;
        max-width: 99vw;
    }
}

/* Modern Dashboard Styles */
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

/* ...rest of your classes remain unchanged, but update green backgrounds to blue as above ... */

.stat-badge i {
  margin-right: 10px;
  color: #ffd700;
}

/* Statistics Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin: 0 50px 50px 50px;
}

.stat-card {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  border: 1px solid rgba(255,255,255,0.2);
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

.primary-card .card-icon { background: #38598b; }
.success-card .card-icon { background: #28a745; }
.info-card .card-icon { background: #2c406b; }
.warning-card .card-icon { background: #b4c7e7; color: #38598b; }

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

/* Content Container */
.content-container {
  margin: 0 50px 30px 50px;
}

.content-card {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  border: 1px solid rgba(255,255,255,0.2);
}

.card-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-title i {
  color: #38598b;
}

.card-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0 0 30px 0;
  font-weight: 400;
}

/* Action Controls */
.action-controls {
  display: flex;
  gap: 15px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.btn-primary, .btn-success {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-primary {
  background: #38598b;
  color: #fff;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px #38598b40;
  color: #fff;
  text-decoration: none;
}

.btn-success {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: #fff;
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
  color: #fff;
}

/* QR Scanner */
.qr-scanner-container {
  margin-bottom: 30px;
  text-align: center;
}

#qr-reader {
  width: 300px;
  margin: 0 auto;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Table Container */
.table-container {
  margin-top: 20px;
}

.table-wrapper {
  overflow-x: auto;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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

/* Table Cell Styles */
.sow-photo-qrcode-flex {
  display: flex;
  align-items: center;
  gap: 10px;
}

.sow-photo-frame, .sow-qrcode-frame {
  width: 50px;
  height: 50px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.sow-photo-frame {
  border: 2px solid #38598b;
}

.sow-qrcode-frame {
  border: 2px solid #ffd700;
}

.sow-photo-frame img, .sow-qrcode-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.qr-link {
  text-decoration: none;
}

.qr-link:hover .sow-qrcode-frame {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

.sow-id-cell {
  font-weight: 700;
  color: #38598b;
  font-size: 1.1rem;
}

.type-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
  background: #e8f5e8;
  color: #2c406b;
}

.breed-cell {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  color: #38598b;
}

.breed-cell i {
  color: #38598b;
  font-size: 0.9rem;
}

.age-cell {
  font-size: 0.9rem;
  color: #38598b;
  line-height: 1.4;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-success { background: linear-gradient(135deg, #28a745, #20c997); color: #fff; }
.badge-warning { background: linear-gradient(135deg, #ff9800, #ffb74d); color: #fff; }
.badge-info { background: linear-gradient(135deg, #2c406b, #38598b); color: #fff; }
.badge-danger { background: linear-gradient(135deg, #d32f2f, #f44336); color: #fff; }
.badge-secondary { background: linear-gradient(135deg, #6c757d, #adb5bd); color: #fff; }

.parity-cell {
  font-weight: 700;
  color: #38598b;
  font-size: 1.1rem;
  text-align: center;
}

.pregnancy-cell {
  min-width: 150px;
}

.progress-container {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.progress-bar {
  width: 100px;
  background: #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.progress-fill {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: #fff;
  text-align: center;
  font-weight: 600;
  font-size: 0.8rem;
  padding: 4px 0;
  transition: width 0.3s ease;
}

.pregnancy-stage {
  color: #38598b;
  font-weight: 600;
  font-size: 0.8rem;
}

.not-pregnant {
  color: #999;
  font-style: italic;
}

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

.no-date {
  color: #999;
  font-style: italic;
}

.description-cell {
  max-width: 200px;
  word-wrap: break-word;
  font-size: 0.9rem;
  line-height: 1.4;
  color: #38598b;
}

.action-cell {
  min-width: 200px;
}

.notification-alert {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #fff3e0;
  color: #f57c00;
  padding: 8px 12px;
  border-radius: 8px;
  margin-bottom: 10px;
  font-size: 0.8rem;
  font-weight: 500;
  border: 1px solid #ffcc02;
}

.notification-alert i {
  color: #f57c00;
}

.action-buttons {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.btn-edit, .btn-status, .btn-log, .btn-history, .btn-delete {
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

.btn-edit {
  background: #e8f5e8;
  color: #2e7d32;
}

.btn-edit:hover {
  background: #c8e6c9;
  transform: translateY(-1px);
}

.btn-status {
  background: #e3f2fd;
  color: #1976d2;
}

.btn-status:hover {
  background: #bbdefb;
  transform: translateY(-1px);
}

.btn-log {
  background: #f3e5f5;
  color: #7b1fa2;
}

.btn-log:hover {
  background: #e1bee7;
  transform: translateY(-1px);
}

.btn-history {
  background: #fff3e0;
  color: #f57c00;
}

.btn-history:hover {
  background: #ffcc02;
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
  
  .content-container {
    margin-left: 30px;
    margin-right: 30px;
  }
}
</style>

<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['message']);
}

// Calculate statistics
try {
    $total_sows_gilts = $db->query("SELECT COUNT(*) FROM sow_gilt_records")->fetchColumn() ?: 0;
    $active_sows_gilts = $db->query("SELECT COUNT(*) FROM sow_gilt_records WHERE status = 'active'")->fetchColumn() ?: 0;
    $pregnant_sows = $db->query("SELECT COUNT(*) FROM sow_gilt_records WHERE status = 'active' AND mating_date IS NOT NULL AND labor_date IS NOT NULL")->fetchColumn() ?: 0;
    $total_parity = $db->query("SELECT SUM(parity) FROM sow_gilt_records")->fetchColumn() ?: 0;
} catch (Exception $e) {
    $total_sows_gilts = 0;
    $active_sows_gilts = 0;
    $pregnant_sows = 0;
    $total_parity = 0;
}
?>

<!-- Modern Pregnancy and Sow/Gilts Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-heart"></i>
                    Pregnancy & Sow/Gilts Management
                </h1>
                <p class="hero-subtitle">Track reproductive health, pregnancy progress, and manage sow and gilt records</p>
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
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-piggy-bank"></i>
            </div>
                <div class="card-title">Total Sows/Gilts</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_sows_gilts); ?></div>
                <div class="card-subtitle">All records in system</div>
        </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-check-circle"></i>
            </div>
                <div class="card-title">Active</div>
        </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($active_sows_gilts); ?></div>
                <div class="card-subtitle">Currently active</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-baby"></i>
                </div>
                <div class="card-title">Pregnant</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($pregnant_sows); ?></div>
                <div class="card-subtitle">Currently pregnant</div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-chart-line"></i>
                </div>
                <div class="card-title">Total Parity</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_parity); ?></div>
                <div class="card-subtitle">Cumulative births</div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="content-container">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-list-alt"></i>
                    Sow/Gilts Records Management
                </h2>
                <p class="card-subtitle">Manage reproductive records, track pregnancy progress, and monitor sow/gilt health</p>
            </div>

            <!-- Action Controls -->
            <div class="action-controls">
                <a href="add-sow-gilt.php" class="btn-primary">
                    <i class="fa fa-plus"></i>
                    <span>Add New Sow/Gilt Record</span>
                </a>
                <button id="scan-qr-btn" class="btn-success">
                    <i class="fa fa-qrcode"></i>
                    <span>Scan QR Code</span>
                </button>
            </div>

            <!-- QR Scanner -->
            <div class="qr-scanner-container">
                <div id="qr-reader"></div>
            </div>
            <!-- Modern Table Container -->
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="modern-table" id="table">
               <thead>
    <tr>
        <th>Photo / QR</th>
        <th>Sow/Gilt ID</th>
        <th>Type</th>
        <th>Breed</th>
        <th>Age</th>
        <th>Status</th>
        <th>Parity</th>
        <th>Pregnancy Progress</th>
        <th>Mating Date</th>
        <th>Labor Date</th>
        <th>Description</th>
                                <th>Actions</th>
    </tr>
</thead>
<tbody>
                    <?php
                    $all_sows_gilts = $db->query("SELECT * FROM sow_gilt_records ORDER BY id DESC");
                    $fetch = $all_sows_gilts->fetchAll(PDO::FETCH_OBJ);

                    foreach ($fetch as $data) {
                        $get_breed = $db->query("SELECT * FROM breed WHERE id = '$data->breed_id'");
                        $breed_result = $get_breed->fetchAll(PDO::FETCH_OBJ);
                        foreach ($breed_result as $breed) {
                            $from_date = $data->birth_date ?: $data->acquired_date;
                            if ($from_date) {
                                $from = new DateTime($from_date);
                                $to = new DateTime();
                                $interval = $from->diff($to);
                                $age_string = "{$interval->y} years, {$interval->m} months, {$interval->d} days";
                                $age_string .= "<br><small>(" . ($data->birth_date ? "Birth: $data->birth_date" : "Acquired: $data->acquired_date") . ")</small>";
                            } else {
                                $age_string = "{$data->age} months (unknown birth/acquired)";
                            }

                            $status_class = '';
                            switch($data->status) {
                                case 'active': $status_class = 'badge-success'; break;
                                case 'inactive': $status_class = 'badge-warning'; break;
                                case 'sold': $status_class = 'badge-info'; break;
                                case 'deceased': $status_class = 'badge-danger'; break;
                                default: $status_class = 'badge-secondary';
                            }

                            $pregnancy_days = 114;
                            $progress = 0;
                            $stage = "Not pregnant";
                            $mating_date = $data->mating_date;
                            $is_pregnant = false;
                            if ($mating_date && $data->status == "active") {
                                $mating_dt = new DateTime($mating_date);
                                $today = new DateTime();
                                $days_since_mating = $mating_dt->diff($today)->days;
                                if ($days_since_mating <= $pregnancy_days && $days_since_mating >= 0) {
                                    $progress = min(100, round(($days_since_mating / $pregnancy_days) * 100));
                                    if ($days_since_mating < 35) $stage = "Early (Implantation)";
                                    elseif ($days_since_mating < 70) $stage = "Mid (Fetal Growth)";
                                    elseif ($days_since_mating < 114) $stage = "Late (Pre-farrow)";
                                    else $stage = "Due/Overdue";
                                    $is_pregnant = true;
                                }
                            }

                            $notification = "";
                            if ($is_pregnant) {
                                if ($days_since_mating == 80) {
                                    $notification = "Reminder: Give medicine/vaccine (B-complex).";
                                } elseif ($days_since_mating == 90) {
                                    $notification = "Reminder: Give medicine/vaccine.";
                                } elseif ($days_since_mating == 100) {
                                    $notification = "Reminder: Give medicine/vaccine.";
                                }
                            }

                            $today_str = date('Y-m-d');
                            if ($data->labor_date && $today_str >= $data->labor_date && $is_pregnant) {
                                $farrow_check = $db->prepare("SELECT COUNT(*) FROM sow_gilt_repro_history WHERE sow_gilt_id=? AND event_type='farrowing' AND event_date=?");
                                $farrow_check->execute([$data->id, $data->labor_date]);
                                $farrowed = $farrow_check->fetchColumn();
                                if (!$farrowed) {
                                    $db->query("UPDATE sow_gilt_records SET parity = parity + 1 WHERE id = {$data->id}");
                                    $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes) VALUES (?, 'farrowing', ?, 'Automatic increment after labor date')")->execute([$data->id, $data->labor_date]);
                                }
                            }
                    ?>
                        <tr>
                            <td>
                              <div class="sow-photo-qrcode-flex">
                                        <div class="sow-photo-frame">
                                  <img src="<?php echo $data->picture; ?>" alt="Sow/Gilt Photo">
                                        </div>
                                        <a href="view-sow-gilt.php?id=<?php echo $data->id ?>" title="View QR" class="qr-link">
                                            <div class="sow-qrcode-frame">
                                    <img src="qrcodes/sow_gilt_<?php echo $data->id; ?>.png" alt="QR Code">
                                            </div>
                                </a>
                              </div>
                            </td>
                                <td>
                                    <div class="sow-id-cell">
                                        <strong><?php echo $data->id ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge"><?php echo ucfirst($data->type); ?></span>
                                </td>
                                <td>
                                    <div class="breed-cell">
                                        <i class="fa fa-tag"></i>
                                        <span><?php echo $breed->name ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="age-cell">
                                        <?php echo $age_string ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($data->status); ?></span>
                                </td>
                                <td>
                                    <div class="parity-cell">
                                        <strong><?php echo $data->parity ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="pregnancy-cell">
                                <?php if ($is_pregnant) { ?>
                                            <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width:<?= $progress ?>%;">
                                            <?= $progress ?>%
                                        </div>
                                    </div>
                                                <div class="pregnancy-stage"><?= $stage ?></div>
                                            </div>
                                <?php } else { ?>
                                            <span class="not-pregnant">Not Pregnant</span>
                                <?php } ?>
                                    </div>
                            </td>
                                <td>
                                    <div class="date-cell">
                                        <?php if ($data->mating_date): ?>
                                            <i class="fa fa-calendar"></i>
                                            <span><?php echo date('M d, Y', strtotime($data->mating_date)); ?></span>
                                        <?php else: ?>
                                            <span class="no-date">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <?php if ($data->labor_date): ?>
                                            <i class="fa fa-calendar"></i>
                                            <span><?php echo date('M d, Y', strtotime($data->labor_date)); ?></span>
                                        <?php else: ?>
                                            <span class="no-date">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="description-cell">
                                        <?php echo wordwrap($data->description, 50, '<br>'); ?>
                                    </div>
                            </td>
                            <td>
                                    <div class="action-cell">
                                <?php if ($notification) { ?>
                                            <div class="notification-alert">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                <span><?php echo $notification; ?></span>
                                            </div>
                                <?php } ?>
                                        <div class="action-buttons">
                                            <a href="edit-sow-gilt.php?id=<?php echo $data->id ?>" class="btn-edit" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="change-status.php?id=<?php echo $data->id ?>" class="btn-status" title="Change Status">
                                                <i class="fa fa-exchange-alt"></i>
                                            </a>
                                            <a href="log-repro-event.php?id=<?php echo $data->id ?>" class="btn-log" title="Log Event">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            <a href="view-repro-history.php?id=<?php echo $data->id ?>" class="btn-history" title="View History">
                                                <i class="fa fa-history"></i>
                                            </a>
                                            <a onclick="return confirm('Continue delete sow/gilt record?')" href="delete-sow-gilt.php?id=<?php echo $data->id ?>" class="btn-delete" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                </div>
                            </td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.getElementById("scan-qr-btn").addEventListener("click", function () {
        const qrReader = new Html5QrcodeScanner("qr-reader", {
            fps: 10,
            qrbox: 250
        });

        qrReader.render(
            (decodedText) => {
                if(decodedText.startsWith('http')) {
                    window.location.href = decodedText;
                } else if(decodedText.includes('id=')) {
                    window.location.href = 'view-sow-gilt.php?' + decodedText;
                } else {
                    alert("Invalid QR code format. Please scan a valid Sow/Gilt QR code.");
                }
                qrReader.clear();
            },
            (errorMessage) => {
                console.warn(errorMessage);
            }
        );
    });
</script>
</body>
</html>