<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Pagination setup
$per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $per_page) - $per_page : 0;

// Get total records for pagination
$total = $db->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();
$pages = ceil($total / $per_page);

// Fetch audit logs with pagination
$stmt = $db->prepare("
    SELECT
        audit_logs.id,
        users.username,
        users.role as user_role,
        audit_logs.action,
        audit_logs.details,
        audit_logs.ip_address,
        audit_logs.status,
        audit_logs.log_time
    FROM audit_logs
    LEFT JOIN users ON audit_logs.user_id = users.id
    ORDER BY audit_logs.log_time DESC
    LIMIT $start, $per_page
");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define action colors and icons
$actionColors = [
    'login' => ['color' => 'w3-green', 'icon' => 'fa-sign-in-alt'],
    'logout' => ['color' => 'w3-blue', 'icon' => 'fa-sign-out-alt'],
    'create' => ['color' => 'w3-teal', 'icon' => 'fa-plus-circle'],
    'update' => ['color' => 'w3-orange', 'icon' => 'fa-edit'],
    'delete' => ['color' => 'w3-red', 'icon' => 'fa-trash-alt'],
    'error' => ['color' => 'w3-purple', 'icon' => 'fa-exclamation-circle'],
    'access' => ['color' => 'w3-indigo', 'icon' => 'fa-door-open'],
    'search' => ['color' => 'w3-deep-purple', 'icon' => 'fa-search'],
    'download' => ['color' => 'w3-brown', 'icon' => 'fa-download']
];

$defaultColor = ['color' => 'w3-dark-grey', 'icon' => 'fa-circle'];
?>

<!-- Modern Audit Logs Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-history"></i>
                    Audit Trail Logs
                </h1>
                <p class="hero-subtitle">Monitor system activities and user actions</p>
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
        // Get audit statistics
        $totalLogs = $db->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();
        $successLogs = $db->query("SELECT COUNT(*) FROM audit_logs WHERE status = 'success'")->fetchColumn();
        $failedLogs = $db->query("SELECT COUNT(*) FROM audit_logs WHERE status = 'failed'")->fetchColumn();
        $todayLogs = $db->query("SELECT COUNT(*) FROM audit_logs WHERE DATE(log_time) = CURDATE()")->fetchColumn();
        ?>
        
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-list-alt"></i>
                </div>
                <div class="card-title">Total Logs</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalLogs); ?></div>
                <div class="card-subtitle">All time records</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="card-title">Successful</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($successLogs); ?></div>
                <div class="card-subtitle">Completed actions</div>
            </div>
        </div>

        <div class="stat-card danger-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="card-title">Failed</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($failedLogs); ?></div>
                <div class="card-subtitle">Failed attempts</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="card-title">Today</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($todayLogs); ?></div>
                <div class="card-subtitle">Today's activities</div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="filter-container">
        <div class="filter-card">
            <div class="filter-header">
                <h3 class="filter-title">
                    <i class="fa fa-filter"></i>
                    Filter & Search
                </h3>
                <p class="filter-subtitle">Refine your audit log search</p>
            </div>
            
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fa fa-search"></i>
                        Search Logs
                    </label>
                    <input type="text" id="searchInput" onkeyup="searchTable()" 
                           placeholder="Search by user, action, or details..." class="filter-input">
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fa fa-tasks"></i>
                        Action Type
                    </label>
                    <select id="actionFilter" onchange="filterTable()" class="filter-select">
                    <option value="">All Actions</option>
                    <?php foreach($actionColors as $action => $data): ?>
                        <option value="<?= strtolower($action) ?>">
                            <?= ucfirst($action) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fa fa-flag"></i>
                        Status
                    </label>
                    <select id="statusFilter" onchange="filterTable()" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fa fa-calendar"></i>
                        From Date
                    </label>
                    <input type="date" id="dateFrom" class="filter-input" placeholder="From Date">
        </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fa fa-calendar"></i>
                        To Date
                    </label>
                    <input type="date" id="dateTo" class="filter-input" placeholder="To Date">
            </div>

                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <button onclick="filterByDate()" class="btn-primary">
                        <i class="fa fa-filter"></i>
                        <span>Apply Filters</span>
                    </button>
            </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="table-container">
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fa fa-table"></i>
                    Audit Logs
                </h2>
                <p class="table-subtitle">Detailed activity logs and system events</p>
            </div>

            <div class="table-wrapper">
                <table class="modern-table" id="auditTable">
                <thead>
                    <tr>
                            <th class="id-col">ID</th>
                            <th class="user-col">User</th>
                            <th class="role-col">Role</th>
                            <th class="action-col">Action</th>
                            <th class="details-col">Details</th>
                            <th class="ip-col">IP Address</th>
                            <th class="status-col">Status</th>
                            <th class="time-col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($logs): ?>
                        <?php foreach ($logs as $log): 
                            $actionClass = $defaultColor;
                            foreach ($actionColors as $action => $data) {
                                if (stripos($log['action'], $action) !== false) {
                                    $actionClass = $data;
                                    break;
                                }
                            }
                        ?>
                                <tr class="table-row">
                                    <td class="id-cell"><?= htmlspecialchars($log['id']) ?></td>
                                    <td class="user-cell">
                                        <div class="user-info">
                                            <div class="user-name"><?= htmlspecialchars($log['username'] ?? 'System') ?></div>
                                        </div>
                                </td>
                                    <td class="role-cell">
                                        <span class="role-badge <?= $log['user_role'] ? 'role-user' : 'role-system' ?>">
                                        <?= htmlspecialchars($log['user_role'] ?? 'system') ?>
                                    </span>
                                </td>
                                    <td class="action-cell">
                                        <span class="action-badge action-<?= strtolower($actionClass['color']) ?>">
                                        <i class="fa <?= $actionClass['icon'] ?>"></i> 
                                            <span><?= htmlspecialchars($log['action']) ?></span>
                                    </span>
                                </td>
                                    <td class="details-cell">
                                        <div class="details-content">
                                            <?= htmlspecialchars(substr($log['details'], 0, 80)) ?>
                                            <?= strlen($log['details']) > 80 ? '...' : '' ?>
                                        </div>
                                </td>
                                    <td class="ip-cell">
                                        <span class="ip-badge">
                                        <?= htmlspecialchars($log['ip_address']) ?>
                                    </span>
                                </td>
                                    <td class="status-cell">
                                    <?php if ($log['status'] === 'success'): ?>
                                            <span class="status-badge success">
                                                <i class="fa fa-check"></i>
                                                <span>Success</span>
                                            </span>
                                    <?php elseif ($log['status'] === 'failed'): ?>
                                            <span class="status-badge failed">
                                                <i class="fa fa-times"></i>
                                                <span>Failed</span>
                                            </span>
                                    <?php else: ?>
                                            <span class="status-badge unknown">
                                                <i class="fa fa-question"></i>
                                                <span><?= htmlspecialchars($log['status'] ?? 'N/A') ?></span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                    <td class="time-cell">
                                        <div class="time-info">
                                            <div class="time-date"><?= date('M d, Y', strtotime($log['log_time'])) ?></div>
                                            <div class="time-time"><?= date('h:i A', strtotime($log['log_time'])) ?></div>
                                        </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                                <td colspan="8" class="no-data">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <div>No audit logs found</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>

        <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination">
                <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>" class="pagination-btn prev">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="pagination-btn <?= ($i == $page) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $pages): ?>
                <a href="?page=<?= $page+1 ?>" class="pagination-btn next">
                    <i class="fa fa-chevron-right"></i>
                </a>
                <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Enhanced search function
function searchTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("auditTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let found = false;
        const td = tr[i].getElementsByTagName("td");
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        tr[i].style.display = found ? "" : "none";
    }
}

// Enhanced filter function
function filterTable() {
    const actionFilter = document.getElementById("actionFilter").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value.toLowerCase();
    const table = document.getElementById("auditTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const actionTd = tr[i].getElementsByTagName("td")[3];
        const statusTd = tr[i].getElementsByTagName("td")[6];
        
        let actionMatch = true;
        let statusMatch = true;
        
        if (actionFilter) {
            const actionValue = actionTd.textContent || actionTd.innerText;
            actionMatch = actionValue.toLowerCase().includes(actionFilter);
        }
        
        if (statusFilter) {
            const statusValue = statusTd.textContent || statusTd.innerText;
            statusMatch = statusValue.toLowerCase().includes(statusFilter);
        }
        
        tr[i].style.display = (actionMatch && statusMatch) ? "" : "none";
    }
}

// Date filter function
function filterByDate() {
    const dateFrom = document.getElementById("dateFrom").value;
    const dateTo = document.getElementById("dateTo").value;
    
    if (!dateFrom && !dateTo) {
        alert("Please select at least one date");
        return;
    }
    
    // Convert to proper format for comparison
    const from = dateFrom ? new Date(dateFrom) : null;
    const to = dateTo ? new Date(dateTo + "T23:59:59") : null;
    
    const table = document.getElementById("auditTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const timeTd = tr[i].getElementsByTagName("td")[7];
        const fullTime = timeTd.textContent.trim();
        const logTime = new Date(fullTime);
        
        let dateMatch = true;
        
        if (from && logTime < from) {
            dateMatch = false;
        }
        
        if (to && logTime > to) {
            dateMatch = false;
        }
        
        tr[i].style.display = dateMatch ? "" : "none";
    }
}
</script>

<?php include 'theme/foot.php'; ?>

<style>
/* Modern Audit Logs Styling - Blue Color Scheme */
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
.danger-card::before { background: #dc3545; }
.info-card::before { background: #17a2b8; }

.primary-card .card-icon { background: #38598b; }
.success-card .card-icon { background: #28a745; }
.danger-card .card-icon { background: #dc3545; }
.info-card .card-icon { background: #17a2b8; }

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

/* Filter Container */
.filter-container {
  margin: 0 50px 40px 50px;
}

.filter-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.filter-header {
  margin-bottom: 30px;
}

.filter-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.filter-title i {
  color: #38598b;
}

.filter-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  align-items: end;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #38598b;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-label i {
  color: #38598b;
  width: 16px;
}

.filter-input, .filter-select {
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fff;
}

.filter-input:focus, .filter-select:focus {
  outline: none;
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e750;
}

.btn-primary {
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
  background: #38598b;
  color: #fff;
  box-shadow: 0 4px 15px #38598b30;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px #38598b40;
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

/* Column Widths */
.id-col { width: 8%; }
.user-col { width: 15%; }
.role-col { width: 12%; }
.action-col { width: 15%; }
.details-col { width: 20%; }
.ip-col { width: 12%; }
.status-col { width: 10%; }
.time-col { width: 18%; }

/* Table Cell Styling */
.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  color: #38598b;
}

.role-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.role-user {
  background: #e3f2fd;
  color: #1976d2;
}

.role-system {
  background: #f3e5f5;
  color: #7b1fa2;
}

.action-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.action-w3-green { background: #d4edda; color: #155724; }
.action-w3-blue { background: #cce7ff; color: #004085; }
.action-w3-teal { background: #d1ecf1; color: #0c5460; }
.action-w3-orange { background: #ffeaa7; color: #d63031; }
.action-w3-red { background: #f8d7da; color: #721c24; }
.action-w3-purple { background: #e2e3f1; color: #6f42c1; }
.action-w3-indigo { background: #e0e7ff; color: #4338ca; }
.action-w3-deep-purple { background: #ede7f6; color: #512da8; }
.action-w3-brown { background: #efebe9; color: #5d4037; }
.action-w3-dark-grey { background: #e9ecef; color: #495057; }

.details-content {
  font-size: 0.85rem;
  color: #6c757d;
  line-height: 1.4;
}

.ip-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 8px;
  background: #f8f9fa;
  color: #6c757d;
  border-radius: 15px;
  font-size: 0.8rem;
  font-family: 'Courier New', monospace;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-badge.success {
  background: #d4edda;
  color: #155724;
}

.status-badge.failed {
  background: #f8d7da;
  color: #721c24;
}

.status-badge.unknown {
  background: #e2e3e5;
  color: #6c757d;
}

.time-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.time-date {
  font-weight: 600;
  color: #38598b;
  font-size: 0.85rem;
}

.time-time {
  color: #6c757d;
  font-size: 0.8rem;
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

/* Pagination */
.pagination-container {
  display: flex;
  justify-content: center;
  margin: 30px 50px 0 50px;
  position: relative;
  z-index: 10;
}

.pagination {
  display: flex;
  gap: 5px;
  background: #e6e9f2;
  padding: 8px;
  border-radius: 15px;
  border: 1px solid #b4c7e7;
}

.pagination-btn {
  padding: 8px 12px;
  background: transparent;
  color: #38598b;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  font-weight: 600;
  min-width: 40px;
  text-align: center;
}

.pagination-btn:hover {
  background: #b4c7e7;
  color: #fff;
}

.pagination-btn.active {
  background: #38598b;
  color: #fff;
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
  
  .filter-container, .table-container {
    margin-left: 30px;
    margin-right: 30px;
  }
  
  .pagination-container {
    margin-left: 30px;
    margin-right: 30px;
  }
  
  .filter-grid {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
  
  .filter-container, .table-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .pagination-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .filter-grid {
    grid-template-columns: 1fr;
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
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .filter-card {
    padding: 20px;
  }
}
</style>