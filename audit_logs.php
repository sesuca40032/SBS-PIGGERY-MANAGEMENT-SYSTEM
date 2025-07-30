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

<!-- Dashboard style main content -->
<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="dashboard-row">
            <div class="dashboard-col dashboard-title">
                <h2><b><i class="fa fa-history"></i> Audit Trail Logs</b></h2>
            </div>
            <div class="dashboard-col dashboard-date">
                <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
            </div>
        </div>
    </header>

    <!-- Controls -->
    <div class="dashboard-card" style="margin-bottom:32px;">
        <div class="dashboard-charts-row" style="gap:22px;">
            <div style="flex:2;min-width:220px;">
                <input type="text" id="searchInput" onkeyup="searchTable()" 
                       placeholder="Search logs..." class="form-control" style="margin-bottom:14px;">
            </div>
            <div style="flex:1;min-width:140px;">
                <select id="actionFilter" onchange="filterTable()" class="form-control">
                    <option value="">All Actions</option>
                    <?php foreach($actionColors as $action => $data): ?>
                        <option value="<?= strtolower($action) ?>">
                            <?= ucfirst($action) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1;min-width:140px;">
                <select id="statusFilter" onchange="filterTable()" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="dashboard-charts-row" style="gap:22px;margin-top:12px;">
            <div style="flex:1;">
                <input type="date" id="dateFrom" class="form-control" placeholder="From Date">
            </div>
            <div style="flex:1;">
                <input type="date" id="dateTo" class="form-control" placeholder="To Date">
            </div>
            <div style="flex:1;">
                <button onclick="filterByDate()" class="btn btn-primary btn-block" style="margin-top:0;">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="dashboard-card" style="padding-top:8px;">
        <div style="overflow-x:auto;">
            <table class="table table-hover table-bordered" id="auditTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Timestamp</th>
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
                            <tr>
                                <td><?= htmlspecialchars($log['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($log['username'] ?? 'System') ?></strong>
                                </td>
                                <td>
                                    <span class="w3-tag w3-round <?= $log['user_role'] ? 'w3-blue' : 'w3-grey' ?>">
                                        <?= htmlspecialchars($log['user_role'] ?? 'system') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="w3-tag <?= $actionClass['color'] ?> w3-round">
                                        <i class="fa <?= $actionClass['icon'] ?>"></i> 
                                        <?= htmlspecialchars($log['action']) ?>
                                    </span>
                                </td>
                                <td class="w3-small">
                                    <?= htmlspecialchars(substr($log['details'], 0, 100)) ?>
                                    <?= strlen($log['details']) > 100 ? '...' : '' ?>
                                </td>
                                <td>
                                    <span class="w3-tag w3-light-grey w3-round">
                                        <?= htmlspecialchars($log['ip_address']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($log['status'] === 'success'): ?>
                                        <span class="w3-tag w3-green w3-round">Success</span>
                                    <?php elseif ($log['status'] === 'failed'): ?>
                                        <span class="w3-tag w3-red w3-round">Failed</span>
                                    <?php else: ?>
                                        <span class="w3-tag w3-grey w3-round">
                                            <?= htmlspecialchars($log['status'] ?? 'N/A') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($log['log_time']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="w3-center w3-padding-16">
                                No audit logs found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="w3-center" style="margin-top:18px;">
            <div class="w3-bar">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page-1 ?>" class="w3-button">&laquo;</a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $pages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="w3-button <?= ($i == $page) ? 'w3-blue' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $pages): ?>
                    <a href="?page=<?= $page+1 ?>" class="w3-button">&raquo;</a>
                <?php endif; ?>
            </div>
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
/* Dashboard Modern UI Styles (copied from dashboard.php for consistency) */
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
.dashboard-charts-row {
  display: flex;
  gap: 32px;
  margin: 0 0 0 0;
  flex-wrap: wrap;
}
.dashboard-chart-col {
  flex: 1 1 350px;
  min-width: 320px;
  max-width: 48vw;
  margin-bottom: 28px;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
}
.dashboard-card h4 {
  font-size: 1.22rem;
  color: #38598b;
  margin-bottom: 20px;
  font-weight: 700;
  letter-spacing: 0.2px;
}
.table thead th {
  font-weight: 700;
  color: #38598b;
  background: #f3f6fb;
  border-bottom: 2px solid #b4c7e7;
}
.table tr td {
  font-size: 1.07rem;
  vertical-align: middle;
}
.table-hover tbody tr:hover {
  background: #f7f8fa;
}
.dashboard-card form .form-group label {
  font-weight: 600;
  color: #38598b;
  font-size: 1.05rem;
}
.dashboard-card form .form-control,
.form-control {
  border-radius: 7px;
  border: 1px solid #b4c7e7;
  box-shadow: 0 1px 4px -2px #38598b18;
  font-size: 1.12rem;
  padding: 10px 14px;
}
.dashboard-card form button.btn-primary,
.btn-primary {
  background: #38598b;
  color: #fff;
  font-size: 1.09rem;
  font-weight: 600;
  border-radius: 8px;
  box-shadow: 0 2px 8px -2px #38598b28;
}
.dashboard-card form button.btn-primary:hover,
.btn-primary:hover {
  background: #2c406b;
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
@media (max-width: 768px) {
  .dashboard-main {
    margin-left: 0;
    padding: 0 0 10px 0;
  }
  .dashboard-header,
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