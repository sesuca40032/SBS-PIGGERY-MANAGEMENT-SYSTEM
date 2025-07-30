<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">

  <!-- Header -->
  <header class="dashboard-header feeds-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2><b><i class="fa fa-warehouse"></i> Feed & Supplies Management</b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>

  <!-- Quick Stats -->
  <div class="dashboard-stats-row">
    <?php
    $totalFeed = $db->query("SELECT SUM(quantity_kg) FROM feed_inventory WHERE quantity_kg > 0")->fetchColumn();
    $totalSupplies = $db->query("SELECT COUNT(*) FROM supply_inventory WHERE quantity > 0")->fetchColumn();
    $recentFeedUsed = $db->query("SELECT SUM(amount_kg) FROM feed_usage WHERE usage_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
    $recentSupplyUsed = $db->query("SELECT SUM(amount) FROM supply_usage WHERE usage_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
    ?>
    <div class="dashboard-stat-card stat-feed">
      <div class="stat-icon"><i class="fa fa-boxes"></i></div>
      <div>
        <div class="stat-number"><?php echo (float)$totalFeed; ?> kg</div>
        <div class="stat-label">Total Feed in Stock</div>
      </div>
    </div>
    <div class="dashboard-stat-card stat-supply">
      <div class="stat-icon"><i class="fa fa-cubes"></i></div>
      <div>
        <div class="stat-number"><?php echo (int)$totalSupplies; ?></div>
        <div class="stat-label">Other Supplies in Stock</div>
      </div>
    </div>
    <div class="dashboard-stat-card stat-feed-used">
      <div class="stat-icon"><i class="fa fa-apple-alt"></i></div>
      <div>
        <div class="stat-number"><?php echo (float)$recentFeedUsed; ?> kg</div>
        <div class="stat-label">Feed Used (7d)</div>
      </div>
    </div>
    <div class="dashboard-stat-card stat-supply-used">
      <div class="stat-icon"><i class="fa fa-medkit"></i></div>
      <div>
        <div class="stat-number"><?php echo (float)$recentSupplyUsed; ?></div>
        <div class="stat-label">Supplies Used (7d)</div>
      </div>
    </div>
  </div>

  <!-- Tabs for Inventory/Usage/Logs -->
  <div class="dashboard-charts-row" style="margin-top: 0;">
    <div class="dashboard-chart-col" style="flex: 2; min-width: 360px;">
      <div class="dashboard-card feeds-card">
        <div class="feeds-tabs">
          <button class="feeds-tab-btn active" onclick="showTab('feed', this)"><i class="fa fa-box"></i> Feed Inventory</button>
          <button class="feeds-tab-btn" onclick="showTab('supply', this)"><i class="fa fa-cube"></i> Supplies Inventory</button>
          <button class="feeds-tab-btn" onclick="showTab('logs', this)"><i class="fa fa-history"></i> Usage Logs</button>
          <button class="feeds-tab-btn feeds-tab-btn-add" onclick="showTab('add', this)"><i class="fa fa-plus"></i> Add Stock/Usage</button>
        </div>

        <!-- Feed Inventory Table -->
        <div id="tab-feed" class="tabSection fade-in">
          <h4 class="tab-title"><i class="fa fa-box"></i> Feed Inventory</h4>
          <div class="w3-responsive">
            <table class="w3-table-all w3-hoverable feeds-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Quantity (kg)</th>
                  <th>Expiry</th>
                  <th>Supplier</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $qf = $db->query("SELECT * FROM feed_inventory ORDER BY name");
                foreach($qf as $feed): ?>
                <tr>
                  <td><span class="badge badge-feed"><?php echo htmlspecialchars($feed['name']); ?></span></td>
                  <td><?php echo htmlspecialchars($feed['type']); ?></td>
                  <td><b><?php echo (float)$feed['quantity_kg']; ?></b></td>
                  <td>
                    <?php
                    $exp = $feed['expiry_date'];
                    if ($exp && strtotime($exp) < strtotime(date('Y-m-d'))) {
                      echo "<span class='expired'>Expired ($exp)</span>";
                    } else {
                      echo $exp ? "<span>$exp</span>" : '<span>-</span>';
                    }
                    ?>
                  </td>
                  <td><?php echo htmlspecialchars($feed['supplier']); ?></td>
                  <td>
                    <a href="edit_feed.php?id=<?php echo $feed['id']; ?>" class="btn-action edit"><i class="fa fa-edit"></i></a>
                    <a href="remove_feed.php?id=<?php echo $feed['id']; ?>" class="btn-action remove" onclick="return confirm('Remove this feed?');"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Supplies Inventory Table -->
        <div id="tab-supply" class="tabSection fade-in" style="display:none;">
          <h4 class="tab-title"><i class="fa fa-cube"></i> Supplies Inventory</h4>
          <div class="w3-responsive">
            <table class="w3-table-all w3-hoverable feeds-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                  <th>Supplier</th>
                  <th>Expiry</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $qs = $db->query("SELECT * FROM supply_inventory ORDER BY name");
                foreach($qs as $sup): ?>
                <tr>
                  <td><span class="badge badge-supply"><?php echo htmlspecialchars($sup['name']); ?></span></td>
                  <td><?php echo htmlspecialchars($sup['category']); ?></td>
                  <td><b><?php echo (float)$sup['quantity']; ?></b></td>
                  <td><?php echo htmlspecialchars($sup['unit']); ?></td>
                  <td><?php echo htmlspecialchars($sup['supplier']); ?></td>
                  <td>
                    <?php
                    $exp = $sup['expiry_date'];
                    if ($exp && strtotime($exp) < strtotime(date('Y-m-d'))) {
                      echo "<span class='expired'>Expired ($exp)</span>";
                    } else {
                      echo $exp ? "<span>$exp</span>" : '<span>-</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <a href="edit_supply.php?id=<?php echo $sup['id']; ?>" class="btn-action edit"><i class="fa fa-edit"></i></a>
                    <a href="remove_supply.php?id=<?php echo $sup['id']; ?>" class="btn-action remove" onclick="return confirm('Remove this supply?');"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Usage Logs Table -->
        <div id="tab-logs" class="tabSection fade-in" style="display:none;">
          <h4 class="tab-title"><i class="fa fa-history"></i> Usage Logs</h4>
          <div class="w3-responsive" style="max-height:400px;overflow:auto;">
            <table class="w3-table-all w3-hoverable feeds-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Type</th>
                  <th>Name</th>
                  <th>Amount Used</th>
                  <th>Used For</th>
                  <th>Notes</th>
                  <th>User</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $logs = $db->query("
                  SELECT 'Feed' as t, f.name, u.amount_kg as amount, f.type as category, u.usage_date, u.used_for, u.notes, u.user
                  FROM feed_usage u LEFT JOIN feed_inventory f ON u.feed_id=f.id
                  UNION ALL
                  SELECT 'Supply' as t, s.name, u.amount, s.category, u.usage_date, u.used_for, u.notes, u.user
                  FROM supply_usage u LEFT JOIN supply_inventory s ON u.supply_id=s.id
                  ORDER BY usage_date DESC LIMIT 80
                ");
                foreach($logs as $log): ?>
                <tr>
                  <td><?php echo htmlspecialchars($log['usage_date']); ?></td>
                  <td>
                    <span class="badge badge-type badge-<?php echo strtolower($log['t']); ?>">
                      <?php echo htmlspecialchars($log['t']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($log['name']); ?></td>
                  <td><b><?php echo (float)$log['amount']; ?><?php echo ($log['t']=='Feed'?' kg':''); ?></b></td>
                  <td><?php echo htmlspecialchars($log['used_for']); ?></td>
                  <td><?php echo htmlspecialchars($log['notes']); ?></td>
                  <td><?php echo htmlspecialchars($log['user']); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Add Stock/Usage Form -->
        <div id="tab-add" class="tabSection fade-in" style="display:none;">
          <h4 class="tab-title"><i class="fa fa-plus"></i> Add Feed/Supply Stock or Usage</h4>
          <form method="post" action="process_feed_supply.php" class="w3-container add-stock-form" style="max-width: 600px;">
            <div class="w3-section">
              <label>Action</label>
              <select class="w3-input w3-border" name="action" id="addAction" required>
                <option value="">Select...</option>
                <option value="add_feed">Add Feed Stock</option>
                <option value="add_supply">Add Supply Stock</option>
                <option value="use_feed">Record Feed Usage</option>
                <option value="use_supply">Record Supply Usage</option>
              </select>
            </div>
            <div id="feedFields" class="fade-in" style="display:none;">
              <div class="w3-section">
                <label>Feed Name</label>
                <input class="w3-input w3-border" name="feed_name" id="feed_name">
              </div>
              <div class="w3-section">
                <label>Feed Type</label>
                <input class="w3-input w3-border" name="feed_type" id="feed_type">
              </div>
              <div class="w3-section">
                <label>Quantity (kg)</label>
                <input class="w3-input w3-border" type="number" min="0" step="0.01" name="feed_qty" id="feed_qty">
              </div>
              <div class="w3-section">
                <label>Expiry Date</label>
                <input class="w3-input w3-border" type="date" name="feed_expiry" id="feed_expiry">
              </div>
              <div class="w3-section">
                <label>Supplier</label>
                <input class="w3-input w3-border" name="feed_supplier" id="feed_supplier">
              </div>
            </div>
            <div id="supplyFields" class="fade-in" style="display:none;">
              <div class="w3-section">
                <label>Supply Name</label>
                <input class="w3-input w3-border" name="supply_name" id="supply_name">
              </div>
              <div class="w3-section">
                <label>Category</label>
                <input class="w3-input w3-border" name="supply_category" id="supply_category">
              </div>
              <div class="w3-section">
                <label>Quantity</label>
                <input class="w3-input w3-border" type="number" min="0" step="0.01" name="supply_qty" id="supply_qty">
              </div>
              <div class="w3-section">
                <label>Unit</label>
                <input class="w3-input w3-border" name="supply_unit" id="supply_unit">
              </div>
              <div class="w3-section">
                <label>Supplier</label>
                <input class="w3-input w3-border" name="supply_supplier" id="supply_supplier">
              </div>
              <div class="w3-section">
                <label>Expiry Date</label>
                <input class="w3-input w3-border" type="date" name="supply_expiry" id="supply_expiry">
              </div>
            </div>
            <div id="feedUsageFields" class="fade-in" style="display:none;">
              <div class="w3-section">
                <label>Feed</label>
                <select class="w3-input w3-border" name="feed_id">
                  <option value="">Select Feed</option>
                  <?php
                  $feeds = $db->query("SELECT * FROM feed_inventory ORDER BY name");
                  foreach($feeds as $f) echo '<option value="'.$f['id'].'">'.htmlspecialchars($f['name']).' ('.$f['type'].')</option>';
                  ?>
                </select>
              </div>
              <div class="w3-section">
                <label>Amount Used (kg)</label>
                <input class="w3-input w3-border" type="number" min="0" step="0.01" name="feed_used_qty">
              </div>
              <div class="w3-section">
                <label>Used For</label>
                <input class="w3-input w3-border" name="feed_used_for">
              </div>
              <div class="w3-section">
                <label>Notes</label>
                <textarea class="w3-input w3-border" name="feed_used_notes"></textarea>
              </div>
            </div>
            <div id="supplyUsageFields" class="fade-in" style="display:none;">
              <div class="w3-section">
                <label>Supply</label>
                <select class="w3-input w3-border" name="supply_id">
                  <option value="">Select Supply</option>
                  <?php
                  $supplies = $db->query("SELECT * FROM supply_inventory ORDER BY name");
                  foreach($supplies as $s) echo '<option value="'.$s['id'].'">'.htmlspecialchars($s['name']).' ('.$s['category'].')</option>';
                  ?>
                </select>
              </div>
              <div class="w3-section">
                <label>Amount Used</label>
                <input class="w3-input w3-border" type="number" min="0" step="0.01" name="supply_used_qty">
              </div>
              <div class="w3-section">
                <label>Used For</label>
                <input class="w3-input w3-border" name="supply_used_for">
              </div>
              <div class="w3-section">
                <label>Notes</label>
                <textarea class="w3-input w3-border" name="supply_used_notes"></textarea>
              </div>
            </div>
            <div class="w3-section">
              <button class="w3-button w3-green" type="submit"><i class="fa fa-save"></i> Save</button>
              <button class="w3-button w3-gray" type="button" onclick="showTab('feed', document.querySelector('.feeds-tab-btn'))"><i class="fa fa-arrow-left"></i> Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<script>
function showTab(tab, btn) {
  document.querySelectorAll('.tabSection').forEach(function(sec) {
    sec.style.display = 'none';
  });
  document.getElementById('tab-' + tab).style.display = 'block';
  document.querySelectorAll('.feeds-tab-btn').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
}

// Show fields depending on action
document.getElementById('addAction').addEventListener('change', function() {
  document.getElementById('feedFields').style.display = this.value==='add_feed' ? 'block' : 'none';
  document.getElementById('supplyFields').style.display = this.value==='add_supply' ? 'block' : 'none';
  document.getElementById('feedUsageFields').style.display = this.value==='use_feed' ? 'block' : 'none';
  document.getElementById('supplyUsageFields').style.display = this.value==='use_supply' ? 'block' : 'none';
});
showTab('feed', document.querySelector('.feeds-tab-btn'));
</script>

<style>
/* Header and Cards */
.feeds-header {
  background: linear-gradient(90deg, #38598b 60%, #90caf9 100%);
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 32px 38px 20px 38px;
  box-shadow: 0 4px 28px -10px #38598b28;
}
.dashboard-stat-card {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 2px 16px -4px #38598b18;
  padding: 22px 32px;
  min-width: 180px;
  min-height: 80px;
  flex: 1;
  gap: 18px;
  display: flex;
  align-items: center;
  transition: box-shadow 0.2s;
}
.dashboard-stat-card:hover {
  box-shadow: 0 4px 28px -6px #38598b40;
}
.stat-feed .stat-icon { color: #3f51b5; background: #e3eafc; }
.stat-supply .stat-icon { color: #388e3c; background: #e8f5e9; }
.stat-feed-used .stat-icon { color: #ff9800; background: #fff3e0; }
.stat-supply-used .stat-icon { color: #f50057; background: #fce4ec; }

/* Tabbed interface */
.feeds-tabs {
  display: flex;
  gap: 0;
  margin-bottom: 18px;
  border-bottom: 2px solid #e3eafc;
  align-items: center;
}
.feeds-tab-btn {
  background: none;
  border: none;
  outline: none;
  font-size: 1.08rem;
  color: #38598b;
  padding: 12px 23px 10px 23px;
  margin: 0;
  border-radius: 17px 17px 0 0;
  cursor: pointer;
  transition: background 0.17s, color 0.17s;
  position: relative;
  font-weight: 600;
}
.feeds-tab-btn.active,
.feeds-tab-btn:focus {
  background: #e3eafc;
  color: #263659;
  z-index: 2;
}
.feeds-tab-btn-add {
  margin-left: auto;
  background: #43a047;
  color: #fff !important;
  border-radius: 16px;
  font-weight: 700;
}
.feeds-tab-btn-add.active,
.feeds-tab-btn-add:focus {
  background: #2e7d32;
  color: #fff;
}
.tab-title {
  font-size: 1.18rem;
  margin-bottom: 14px;
  color: #38598b;
  font-weight: 700;
}

.dashboard-card.feeds-card {
  min-height: 440px;
  background: #f8fafc;
  box-shadow: 0 2px 20px -8px #38598b18;
  padding-top: 6px;
}

/* Tables */
.feeds-table th, .feeds-table td {
  padding: 10px 10px;
  font-size: 1.04rem;
}
.feeds-table th {
  background: #e3eafc;
  color: #38598b;
}
.feeds-table tr:nth-child(even) {
  background: #f3f7fb;
}
.feeds-table tr:hover {
  background: #e1f5fe;
}
.badge {
  border-radius: 14px;
  padding: 3px 11px;
  font-size: 1rem;
  font-weight: 500;
  display: inline-block;
}
.badge-feed {
  background: #e3eafc;
  color: #3f51b5;
}
.badge-supply {
  background: #e8f5e9;
  color: #388e3c;
}
.badge-type {
  background: #ede7f6;
  color: #6a1b9a;
}
.badge-feed { background: #bbdefb; color: #1e88e5; }
.badge-supply { background: #e0f2f1; color: #00695c; }
.badge-feed { background: #e3eafc; color: #3f51b5; }
.badge-supply { background: #e8f5e9; color: #388e3c; }
.badge-feed { background: #bbdefb; color: #1e88e5; }
.badge-supply { background: #e0f2f1; color: #00695c; }
.badge-feed { background: #e3eafc; color: #3f51b5; }
.badge-supply { background: #e8f5e9; color: #388e3c; }
.badge-type.badge-feed { background: #e3eafc; color: #3f51b5; }
.badge-type.badge-supply { background: #e0f2f1; color: #388e3c; }
.badge-type.badge-feed { background: #e3eafc; color: #3f51b5; }
.badge-type.badge-supply { background: #e8f5e9; color: #388e3c; }

.btn-action {
  display: inline-block;
  padding: 5px 11px;
  margin: 0 2px;
  border-radius: 8px;
  font-size: 1rem;
  color: #fff;
  background: #2196f3;
  transition: background 0.17s;
  text-decoration: none;
}
.btn-action.edit { background: #1976d2; }
.btn-action.remove { background: #f50057; }
.btn-action:hover { opacity: 0.9; }

.expired {
  color: #f44336;
  font-weight: bold;
}

.fade-in {
  animation: fadeIn 0.2s;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px);}
  to { opacity: 1; transform: none;}
}

/* Add stock form */
.add-stock-form label {
  font-weight: 600;
  color: #38598b;
  margin-bottom: 3px;
}
.add-stock-form input, .add-stock-form select, .add-stock-form textarea {
  border-radius: 8px;
  margin-bottom: 12px;
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
  .feeds-tabs {
    flex-direction: column;
    gap: 8px;
    padding-bottom: 8px;
  }
}
</style>