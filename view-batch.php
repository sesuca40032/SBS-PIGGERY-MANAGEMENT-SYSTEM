<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

$is_create = isset($_GET['create']) ? true : false;

// Accept either ?id=... (numeric primary key) or ?batch_id=...
$id = isset($_GET['id']) ? $_GET['id'] : null;
$batch_id = isset($_GET['batch_id']) ? $_GET['batch_id'] : null;
$batch = null;

if (!$is_create && ($id || $batch_id)) {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM pig_batches WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        $stmt = $db->prepare("SELECT * FROM pig_batches WHERE batch_id = ?");
        $stmt->execute([$batch_id]);
    }
    $batch = $stmt->fetch(PDO::FETCH_OBJ);
}


// For create action, handle POST submission
if ($is_create && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'batch_id', 'photo', 'source', 'sow_id', 'total_pigs', 'male_count', 'female_count', 'birth_date', 'status'
    ];
    $values = [];
    foreach ($fields as $field) {
        $values[$field] = isset($_POST[$field]) ? $_POST[$field] : '';
    }
    $q = $db->prepare("INSERT INTO pig_batches (batch_id, photo, source, sow_id, total_pigs, male_count, female_count, birth_date, status) VALUES (:batch_id, :photo, :source, :sow_id, :total_pigs, :male_count, :female_count, :birth_date, :status)");
    $success = $q->execute($values);
    if ($success) {
        $_SESSION['message'] = "Batch created successfully!";
        header("Location: batch-view-create.php?batch_id=" . $_POST['batch_id']);
        exit;
    } else {
        $error = "Error saving batch.";
    }
}
?>

<style>
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
.dashboard-title h2,
.dashboard-title h3 {
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
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
}
.btn-primary, .w3-button.w3-blue {
  background: #38598b !important;
  color: #fff !important;
  font-size: 1.09rem !important;
  font-weight: 600;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b28 !important;
}
.btn-primary:hover, .w3-button.w3-blue:hover {
  background: #2c406b !important;
}
.table thead th, .w3-table th {
  font-weight: 700;
  color: #38598b;
  background: #f3f6fb;
  border-bottom: 2px solid #b4c7e7;
}
.table tr td, .w3-table td {
  font-size: 1.07rem;
  vertical-align: middle;
}
.table-hover tbody tr:hover, .w3-table-hover tr:hover {
  background: #f7f8fa;
}
.badge-success {
  background: #4caf50;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-warning {
  background: #ff9800;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-info {
  background: #2196f3;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-danger {
  background: #d32f2f;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-secondary {
  background: #aaa;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-warning {
  background: #ffeb3b;
  color: #333 !important;
  border: 1px solid #ffd600;
}
.dropdown-menu {
  min-width: 180px;
  border-radius: 12px;
  box-shadow: 0 2px 14px -4px #38598b24;
}
.batch-photo-frame {
  width: 60px;
  height: 60px;
  display: inline-block;
  border: 2px solid #dedede;
  background: #f0f0f0;
  overflow: hidden;
  box-sizing: border-box;
  border-radius: 10px;
}
.batch-photo-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}
@media (max-width: 1100px) {
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

<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['message']);
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header">
        <div class="dashboard-row">
            <div class="dashboard-col dashboard-title">
                <h2>
                  <b>
                    <i class="fa fa-paw"></i>
                    <?php echo $is_create ? "Create Pig Batch" : "View Pig Batch"; ?>
                  </b>
                </h2>
            </div>
            <div class="dashboard-col dashboard-date">
                <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
            </div>
        </div>
    </header>

    <div class="dashboard-card" style="margin:38px 38px 0 38px; <?php echo $is_create ? 'max-width:600px;' : ''; ?>">
        <?php if ($is_create): ?>
        <form method="POST" enctype="multipart/form-data">
            <h3 style="font-weight:600;">Create New Pig Batch</h3>
            <?php if (!empty($error)): ?>
              <div class="badge badge-danger"><?= $error ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label>Batch ID</label>
                <input class="form-control" type="text" name="batch_id" required>
            </div>
            <div class="form-group">
                <label>Photo URL</label>
                <input class="form-control" type="text" name="photo">
                <small>Paste image URL or upload after creation.</small>
            </div>
            <div class="form-group">
                <label>Source</label>
                <select class="form-control" name="source" required>
                    <option value="">Select...</option>
                    <option value="farm">Farm Production</option>
                    <option value="external">External Purchase</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sow ID</label>
                <input class="form-control" type="text" name="sow_id">
            </div>
            <div class="form-group">
                <label>Total Pigs</label>
                <input class="form-control" type="number" name="total_pigs" required min="1">
            </div>
            <div class="form-group">
                <label>Male Count</label>
                <input class="form-control" type="number" name="male_count" required min="0">
            </div>
            <div class="form-group">
                <label>Female Count</label>
                <input class="form-control" type="number" name="female_count" required min="0">
            </div>
            <div class="form-group">
                <label>Birth Date</label>
                <input class="form-control" type="date" name="birth_date" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status" required>
                    <option value="active">Active</option>
                    <option value="quarantined">Quarantined</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save Batch</button>
        </form>
        <?php elseif ($batch): ?>
        <h3 style="font-weight:600;">Batch Details</h3>
        <table class="table table-striped">
            <tr>
                <th>Batch ID</th>
                <td><?= htmlspecialchars($batch->batch_id) ?></td>
            </tr>
            <tr>
                <th>Photo</th>
                <td>
                  <span class="batch-photo-frame">
                    <img src="<?= $batch->photo ?: 'assets/default_batch.jpg' ?>" alt="Batch Photo">
                  </span>
                </td>
            </tr>
            <tr>
                <th>Source</th>
                <td><?= ucfirst($batch->source) ?></td>
            </tr>
            <tr>
                <th>Sow ID</th>
                <td><?= $batch->sow_id ?: 'N/A' ?></td>
            </tr>
            <tr>
                <th>Total Pigs</th>
                <td><?= $batch->total_pigs ?></td>
            </tr>
            <tr>
                <th>Male Count</th>
                <td><?= $batch->male_count ?></td>
            </tr>
            <tr>
                <th>Female Count</th>
                <td><?= $batch->female_count ?></td>
            </tr>
            <tr>
                <th>Birth Date</th>
                <td><?= htmlspecialchars($batch->birth_date) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                  <span class="badge 
                    <?php
                      switch($batch->status) {
                        case 'active': echo 'badge-success'; break;
                        case 'quarantined': echo 'badge-warning'; break;
                        case 'sold': echo 'badge-info'; break;
                        default: echo 'badge-secondary';
                      }
                    ?>">
                    <?= ucfirst($batch->status) ?>
                  </span>
                </td>
            </tr>
        </table>
        <div style="margin-top:18px;">
            <a class="btn btn-primary" href="batch-view-create.php?create=1"><i class="fa fa-plus"></i> Create New</a>
            <a class="btn btn-primary" href="edit-batch.php?batch_id=<?= htmlspecialchars($batch->batch_id) ?>"><i class="fa fa-edit"></i> Edit</a>
        </div>
        <?php else: ?>
          <div class="badge badge-warning">Batch not found.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'theme/foot.php'; ?>