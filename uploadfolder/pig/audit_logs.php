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

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-history"></i> Audit Trail</b></h5>
    </header>

    <!-- Controls -->
    <div class="w3-container w3-margin-bottom">
        <div class="w3-row">
            <div class="w3-col m6 s12">
                <input type="text" id="searchInput" onkeyup="searchTable()" 
                       placeholder="Search logs..." class="w3-input w3-border w3-padding">
            </div>
            <div class="w3-col m3 s6">
                <select id="actionFilter" onchange="filterTable()" class="w3-select w3-border">
                    <option value="">All Actions</option>
                    <?php foreach($actionColors as $action => $data): ?>`
                        <option value="<?= strtolower($action) ?>">
                            <?= ucfirst($action) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w3-col m3 s6">
                <select id="statusFilter" onchange="filterTable()" class="w3-select w3-border">
                    <option value="">All Statuses</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="w3-row w3-margin-top">
            <div class="w3-col m4 s12">
                <input type="date" id="dateFrom" class="w3-input w3-border" placeholder="From Date">
            </div>
            <div class="w3-col m4 s12">
                <input type="date" id="dateTo" class="w3-input w3-border" placeholder="To Date">
            </div>
            <div class="w3-col m4 s12">
                <button onclick="filterByDate()" class="w3-button w3-blue w3-block">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="w3-container">
        <div class="w3-responsive w3-card">
            <table class="w3-table w3-bordered w3-hoverable" id="auditTable">
                <thead>
                    <tr class="w3-light-grey">
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
            
            <!-- Pagination -->
            <div class="w3-center w3-padding-16">
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