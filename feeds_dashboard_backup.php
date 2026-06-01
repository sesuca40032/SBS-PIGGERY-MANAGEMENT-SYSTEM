<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>


<!-- Modern Feeds Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-warehouse"></i>
                    Feed & Supplies Management
                </h1>
                <p class="hero-subtitle">Track inventory, monitor usage, and manage feed & supply operations</p>
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
        // Feed and supply stats with error handling
        try {
            $totalFeed = $db->query("SELECT SUM(quantity_kg) FROM feed_inventory WHERE quantity_kg > 0")->fetchColumn() ?: 0;
            $totalSupplies = $db->query("SELECT COUNT(*) FROM supply_inventory WHERE quantity > 0")->fetchColumn() ?: 0;
            $recentFeedUsed = $db->query("SELECT SUM(amount_kg) FROM feed_usage WHERE usage_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn() ?: 0;
            $recentSupplyUsed = $db->query("SELECT SUM(amount) FROM supply_usage WHERE usage_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn() ?: 0;
            $lowStockFeed = $db->query("SELECT COUNT(*) FROM feed_inventory WHERE quantity_kg < 50")->fetchColumn() ?: 0;
            $expiringSoon = $db->query("SELECT COUNT(*) FROM feed_inventory WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND expiry_date > CURDATE()")->fetchColumn() ?: 0;
        } catch (Exception $e) {
            $totalFeed = 0;
            $totalSupplies = 0;
            $recentFeedUsed = 0;
            $recentSupplyUsed = 0;
            $lowStockFeed = 0;
            $expiringSoon = 0;
        }
        ?>
        
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-boxes"></i>
                </div>
                <div class="card-title">Feed Stock</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalFeed, 1); ?> kg</div>
                <div class="card-subtitle">Total in inventory</div>
      </div>
    </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="card-title">Supplies</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalSupplies); ?></div>
                <div class="card-subtitle">Items in stock</div>
      </div>
    </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-chart-line"></i>
                </div>
                <div class="card-title">Weekly Usage</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($recentFeedUsed, 1); ?> kg</div>
                <div class="card-subtitle">Feed used (7 days)</div>
      </div>
    </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="card-title">Low Stock</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($lowStockFeed); ?></div>
                <div class="card-subtitle">Feed items < 50kg</div>
      </div>
    </div>
  </div>

    <!-- Main Content Container -->
    <div class="content-container">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-warehouse"></i>
                    Inventory Management
                </h2>
                <p class="card-subtitle">Manage feed and supply inventory with real-time tracking</p>
            </div>

            <!-- Modern Tab Navigation -->
            <div class="modern-tabs">
                <button class="tab-btn active" onclick="showTab('feed', this)">
                    <i class="fa fa-box"></i>
                    <span>Feed Inventory</span>
                </button>
                <button class="tab-btn" onclick="showTab('supply', this)">
                    <i class="fa fa-cube"></i>
                    <span>Supplies</span>
                </button>
                <button class="tab-btn" onclick="showTab('logs', this)">
                    <i class="fa fa-history"></i>
                    <span>Usage Logs</span>
                </button>
                <button class="tab-btn tab-btn-primary" onclick="showTab('add', this)">
                    <i class="fa fa-plus"></i>
                    <span>Add Stock</span>
                </button>
        </div>

        <!-- Feed Inventory Table -->
            <div id="tab-feed" class="tab-content active">
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="modern-table" id="feedTable">
              <thead>
                <tr>
                                    <th>Feed Name</th>
                  <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Expiry Date</th>
                  <th>Supplier</th>
                                    <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                                try {
                $qf = $db->query("SELECT * FROM feed_inventory ORDER BY name");
                                    foreach($qf as $feed): 
                                ?>
                                <tr>
                                    <td>
                                        <div class="feed-name-cell">
                                            <i class="fa fa-box"></i>
                                            <span class="feed-name"><?php echo htmlspecialchars($feed['name']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="feed-type-badge"><?php echo htmlspecialchars($feed['type']); ?></span>
                                    </td>
                                    <td>
                                        <div class="quantity-cell">
                                            <span class="quantity-value"><?php echo number_format($feed['quantity_kg'], 1); ?></span>
                                            <span class="quantity-unit">kg</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="expiry-cell">
                    <?php
                    $exp = $feed['expiry_date'];
                    if ($exp && strtotime($exp) < strtotime(date('Y-m-d'))) {
                                                echo "<span class='expiry-badge expired'>";
                                                echo "<i class='fa fa-exclamation-triangle'></i>";
                                                echo "Expired " . date('M d, Y', strtotime($exp));
                                                echo "</span>";
                                            } elseif ($exp && strtotime($exp) <= strtotime(date('Y-m-d', strtotime('+30 days')))) {
                                                echo "<span class='expiry-badge expiring-soon'>";
                                                echo "<i class='fa fa-clock'></i>";
                                                echo date('M d, Y', strtotime($exp));
                                                echo "</span>";
                    } else {
                                                echo $exp ? "<span class='expiry-badge valid'>" . date('M d, Y', strtotime($exp)) . "</span>" : '<span class="no-expiry">-</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="supplier-cell">
                                            <i class="fa fa-truck"></i>
                                            <span><?php echo htmlspecialchars($feed['supplier']); ?></span>
                                        </div>
                  </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_feed.php?id=<?php echo $feed['id']; ?>" class="btn-edit" title="Edit Feed">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="remove_feed.php?id=<?php echo $feed['id']; ?>" class="btn-delete" title="Remove Feed" onclick="return confirm('Remove this feed?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                  </td>
                </tr>
                                <?php 
                                    endforeach; 
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="6" class="error-message">Error loading feed data: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                }
                                ?>
              </tbody>
            </table>
                    </div>
          </div>
        </div>
        <!-- Supplies Inventory Table -->
            <div id="tab-supply" class="tab-content">
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="modern-table" id="supplyTable">
              <thead>
                <tr>
                                    <th>Supply Name</th>
                  <th>Category</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                  <th>Supplier</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                                try {
                $qs = $db->query("SELECT * FROM supply_inventory ORDER BY name");
                                    foreach($qs as $sup): 
                                ?>
                                <tr>
                                    <td>
                                        <div class="supply-name-cell">
                                            <i class="fa fa-cube"></i>
                                            <span class="supply-name"><?php echo htmlspecialchars($sup['name']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="category-badge"><?php echo htmlspecialchars($sup['category']); ?></span>
                                    </td>
                                    <td>
                                        <div class="quantity-cell">
                                            <span class="quantity-value"><?php echo number_format($sup['quantity'], 1); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="unit-badge"><?php echo htmlspecialchars($sup['unit']); ?></span>
                                    </td>
                                    <td>
                                        <div class="supplier-cell">
                                            <i class="fa fa-truck"></i>
                                            <span><?php echo htmlspecialchars($sup['supplier']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="expiry-cell">
                    <?php
                    $exp = $sup['expiry_date'];
                    if ($exp && strtotime($exp) < strtotime(date('Y-m-d'))) {
                                                echo "<span class='expiry-badge expired'>";
                                                echo "<i class='fa fa-exclamation-triangle'></i>";
                                                echo "Expired " . date('M d, Y', strtotime($exp));
                                                echo "</span>";
                                            } elseif ($exp && strtotime($exp) <= strtotime(date('Y-m-d', strtotime('+30 days')))) {
                                                echo "<span class='expiry-badge expiring-soon'>";
                                                echo "<i class='fa fa-clock'></i>";
                                                echo date('M d, Y', strtotime($exp));
                                                echo "</span>";
                    } else {
                                                echo $exp ? "<span class='expiry-badge valid'>" . date('M d, Y', strtotime($exp)) . "</span>" : '<span class="no-expiry">-</span>';
                    }
                    ?>
                                        </div>
                  </td>
                  <td>
                                        <div class="action-buttons">
                                            <a href="edit_supply.php?id=<?php echo $sup['id']; ?>" class="btn-edit" title="Edit Supply">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="remove_supply.php?id=<?php echo $sup['id']; ?>" class="btn-delete" title="Remove Supply" onclick="return confirm('Remove this supply?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                  </td>
                </tr>
                                <?php 
                                    endforeach; 
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="7" class="error-message">Error loading supply data: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                }
                                ?>
              </tbody>
            </table>
                    </div>
          </div>
        </div>
        <!-- Usage Logs Table -->
            <div id="tab-logs" class="tab-content">
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="modern-table" id="logsTable">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Type</th>
                                    <th>Item Name</th>
                  <th>Amount Used</th>
                  <th>Used For</th>
                  <th>Notes</th>
                  <th>User</th>
                </tr>
              </thead>
              <tbody>
                <?php
                                try {
                $logs = $db->query("
                  SELECT 'Feed' as t, f.name, u.amount_kg as amount, f.type as category, u.usage_date, u.used_for, u.notes, u.user
                  FROM feed_usage u LEFT JOIN feed_inventory f ON u.feed_id=f.id
                  UNION ALL
                  SELECT 'Supply' as t, s.name, u.amount, s.category, u.usage_date, u.used_for, u.notes, u.user
                  FROM supply_usage u LEFT JOIN supply_inventory s ON u.supply_id=s.id
                  ORDER BY usage_date DESC LIMIT 80
                ");
                                    foreach($logs as $log): 
                                ?>
                                <tr>
                                    <td>
                                        <div class="date-cell">
                                            <i class="fa fa-calendar"></i>
                                            <span><?php echo date('M d, Y', strtotime($log['usage_date'])); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="type-badge type-<?php echo strtolower($log['t']); ?>">
                                            <i class="fa fa-<?php echo strtolower($log['t']) == 'feed' ? 'box' : 'cube'; ?>"></i>
                      <?php echo htmlspecialchars($log['t']); ?>
                    </span>
                  </td>
                                    <td>
                                        <div class="item-name-cell">
                                            <span class="item-name"><?php echo htmlspecialchars($log['name']); ?></span>
                                            <span class="item-category"><?php echo htmlspecialchars($log['category']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="amount-cell">
                                            <span class="amount-value"><?php echo number_format($log['amount'], 1); ?></span>
                                            <span class="amount-unit"><?php echo ($log['t']=='Feed'?'kg':''); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="used-for"><?php echo htmlspecialchars($log['used_for']); ?></span>
                                    </td>
                                    <td>
                                        <div class="notes-cell">
                                            <?php if (!empty($log['notes'])): ?>
                                                <span class="notes-text" title="<?php echo htmlspecialchars($log['notes']); ?>">
                                                    <?php echo strlen($log['notes']) > 30 ? substr(htmlspecialchars($log['notes']), 0, 30) . '...' : htmlspecialchars($log['notes']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="no-notes">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-cell">
                                            <i class="fa fa-user"></i>
                                            <span><?php echo htmlspecialchars($log['user']); ?></span>
                                        </div>
                                    </td>
                </tr>
                                <?php 
                                    endforeach; 
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="7" class="error-message">Error loading usage logs: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                }
                                ?>
              </tbody>
            </table>
                    </div>
          </div>
        </div>
        <!-- Add Stock/Usage Form -->
            <div id="tab-add" class="tab-content">
                <div class="form-container">
                    <div class="form-header">
                        <h3 class="form-title">
                            <i class="fa fa-plus"></i>
                            Add Stock or Record Usage
                        </h3>
                        <p class="form-subtitle">Add new inventory items or record usage of existing items</p>
                    </div>

                    <form method="post" action="process_feed_supply.php" class="modern-form">
                        <div class="form-group">
                            <label class="form-label" for="addAction">
                                <i class="fa fa-tasks"></i>
                                Action Type
                            </label>
                            <select class="form-select" name="action" id="addAction" required>
                                <option value="">Select an action...</option>
                <option value="add_feed">Add Feed Stock</option>
                <option value="add_supply">Add Supply Stock</option>
                <option value="use_feed">Record Feed Usage</option>
                <option value="use_supply">Record Supply Usage</option>
              </select>
            </div>
                        <!-- Feed Fields -->
                        <div id="feedFields" class="form-section" style="display:none;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="feed_name">
                                        <i class="fa fa-box"></i>
                                        Feed Name
                                    </label>
                                    <input class="form-input" name="feed_name" id="feed_name" placeholder="Enter feed name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="feed_type">
                                        <i class="fa fa-tag"></i>
                                        Feed Type
                                    </label>
                                    <input class="form-input" name="feed_type" id="feed_type" placeholder="e.g., Starter, Grower, Finisher">
                                </div>
              </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="feed_qty">
                                        <i class="fa fa-weight"></i>
                                        Quantity (kg)
                                    </label>
                                    <input class="form-input" type="number" min="0" step="0.01" name="feed_qty" id="feed_qty" placeholder="0.00">
              </div>
                                <div class="form-group">
                                    <label class="form-label" for="feed_expiry">
                                        <i class="fa fa-calendar"></i>
                                        Expiry Date
                                    </label>
                                    <input class="form-input" type="date" name="feed_expiry" id="feed_expiry">
              </div>
              </div>
                            <div class="form-group">
                                <label class="form-label" for="feed_supplier">
                                    <i class="fa fa-truck"></i>
                                    Supplier
                                </label>
                                <input class="form-input" name="feed_supplier" id="feed_supplier" placeholder="Enter supplier name">
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
                        <div class="form-actions">
                            <button class="btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                Save
                            </button>
                            <button class="btn-secondary" type="button" onclick="showTab('feed', document.querySelector('.tab-btn'))">
                                <i class="fa fa-arrow-left"></i>
                                Cancel
                            </button>
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
  document.querySelectorAll('.tab-content').forEach(function(sec) {
    sec.classList.remove('active');
  });
  document.getElementById('tab-' + tab).classList.add('active');
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
}

// Show fields depending on action
document.getElementById('addAction').addEventListener('change', function() {
  document.getElementById('feedFields').style.display = this.value==='add_feed' ? 'block' : 'none';
  document.getElementById('supplyFields').style.display = this.value==='add_supply' ? 'block' : 'none';
  document.getElementById('feedUsageFields').style.display = this.value==='use_feed' ? 'block' : 'none';
  document.getElementById('supplyUsageFields').style.display = this.value==='use_supply' ? 'block' : 'none';
});

// Initialize with feed tab active
showTab('feed', document.querySelector('.tab-btn'));
</script>

<style>
/* Modern Feeds Dashboard Styling - Agricultural Green Theme */
.modern-dashboard {
  font-family: 'Segoe UI', 'Roboto', 'Inter', Arial, sans-serif;
  min-height: 100vh;
  background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
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
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.1;
  pointer-events: none;
}

/* Hero Section */
.hero-section {
  background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
  backdrop-filter: blur(10px);
  border-radius: 0 0 30px 30px;
  margin-bottom: 40px;
  padding: 40px 50px 30px 50px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  border: 1px solid rgba(255,255,255,0.2);
}

.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  color: #fff;
  margin: 0;
  text-shadow: 0 2px 10px rgba(0,0,0,0.3);
  letter-spacing: -0.5px;
}

.hero-title i {
  margin-right: 15px;
  color: #ffd700;
}

.hero-subtitle {
  font-size: 1.2rem;
  color: rgba(255,255,255,0.9);
  margin: 10px 0 0 0;
  font-weight: 400;
}

.hero-stats {
  display: flex;
  align-items: center;
}

.stat-badge {
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 20px;
  padding: 15px 25px;
  color: #fff;
  font-weight: 600;
  font-size: 1.1rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-badge i {
  margin-right: 10px;
  color: #ffd700;
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