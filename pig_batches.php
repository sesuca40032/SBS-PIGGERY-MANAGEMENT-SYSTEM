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
/* ... all existing CSS ... */
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
.dashboard-charts-row {
  display: flex;
  gap: 32px;
  margin: 0 38px;
  flex-wrap: wrap;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
  transition: box-shadow .2s;
}
.dashboard-card:hover {
  box-shadow: 0 8px 32px -4px #38598b33;
}
.btn-primary, .w3-button.w3-blue {
  background: #38598b !important;
  color: #fff !important;
  font-size: 1.09rem !important;
  font-weight: 600;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b28 !important;
  transition: background .2s;
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
  transition: background .2s;
}
.dashboard-card h3 {
  font-size: 1.22rem;
  color: #38598b;
  margin-bottom: 20px;
  font-weight: 700;
  letter-spacing: 0.2px;
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
.batch-photo-qrcode-flex {
  display: flex;
  align-items: center;
  gap: 16px;
  justify-content: flex-start;
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
.batch-qrcode-frame {
  width: 60px;
  height: 60px;
  display: inline-block;
  background: #fff;
  border: 2px solid #dedede;
  box-sizing: border-box;
  overflow: hidden;
  border-radius: 10px;
}
.batch-qrcode-frame img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 10px;
}
.progress-bar-flex {
  display: flex;
  align-items: center;
  gap: 2px;
  border-radius: 8px;
  overflow: hidden;
  background: #f1f1f1;
  min-width: 100px;
}
.progress-male {
  background: #38598b;
  color: #fff;
  font-size: .95em;
  text-align: center;
  padding: 0 4px;
  border-radius: 0;
}
.progress-female {
  background: #e91e63;
  color: #fff;
  font-size: .95em;
  text-align: center;
  padding: 0 4px;
  border-radius: 0;
}
.progress-stage {
  background: #7c43bd;
  color: #fff;
  font-size: .95em;
  text-align: center;
  border-radius: 0;
}
.w3-tag.w3-green, .w3-tag.w3-orange, .w3-tag.w3-grey {
  font-size: 1em;
  font-weight: 600;
}
.w3-tag.w3-green { background: #4caf50 !important; }
.w3-tag.w3-orange { background: #ff9800 !important; }
.w3-tag.w3-grey { background: #aaa !important; }
.w3-tag.w3-purple { background: #7c43bd !important; }
.w3-tag.w3-blue { background: #2196f3 !important; }
.w3-tag.w3-teal { background: #009688 !important; }
.w3-tag.w3-pink { background: #e91e63 !important; }
.w3-tag.w3-red { background: #d32f2f !important; }
.w3-dropdown-content {
  min-width: 170px;
  border-radius: 12px;
  box-shadow: 0 2px 14px -4px #38598b24;
  padding: 10px 0;
  background: #fff;
}
.w3-bar-item.w3-button {
  padding: 8px 20px;
  font-size: 1.03em;
  transition: background .15s;
}
.w3-bar-item.w3-button:hover {
  background: #f0f0f0 !important;
  color: #38598b !important;
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
  .dashboard-card {
    margin: 12px 7px;
    padding: 12px 8px 10px 8px;
  }
}
</style>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2><b><i class="fa fa-paw"></i> Pig Batch Management Dashboard</b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>
  <div class="dashboard-card" style="margin:38px 38px 0 38px;">
    <div class="dashboard-row" style="margin-bottom:14px;">
      <div style="display:flex;align-items:center;gap:16px;">
        <button id="scan-qr-btn-header" title="Scan QR code" class="btn btn-success" style="padding:7px 12px;"><i class="fa fa-qrcode"></i> Scan Batch QR</button>
      </div>
      <div>
        <div class="w3-dropdown-hover">
          <button class="w3-button w3-blue"><i class="fa fa-plus"></i> Add New Batch</button>
          <div class="w3-dropdown-content w3-bar-block w3-card-4">
            <a href="add-batch.php?source=farm" class="w3-bar-item w3-button"><i class="fa fa-home"></i> Farm Production</a>
            <a href="add-batch.php?source=external" class="w3-bar-item w3-button"><i class="fa fa-truck"></i> External Purchase</a>
          </div>
        </div>
      </div>
    </div>
    <div class="dashboard-row" style="margin-bottom:24px;">
      <div>
        <h3><i class="fa fa-list"></i> Current Batches</h3>
      </div>
      <div>
        <input class="form-control" type="text" placeholder="Search batches..." id="searchInput">
      </div>
    </div>
    <div class="w3-responsive">
      <table class="w3-table-all w3-hoverable" id="batchTable">
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
            <label for="deformities"><b>Number of Deformities:</b></label>
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