<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Get source type from URL and log the access
$source = isset($_GET['source']) ? $_GET['source'] : 'farm';
$page_title = $source == 'farm' ? 'Farm Production Batch' : 'External Purchase Batch';

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip === '::1' ? '127.0.0.1' : $ip;
}

// Log page access
$stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, log_time) 
                     VALUES (:user_id, :action, :details, :ip_address, NOW())");
$stmt->execute([
    ':user_id' => $_SESSION['id'],
    ':action' => 'page_access',
    ':details' => "Accessed Add New Batch page - $page_title",
    ':ip_address' => getClientIP()
]);
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
.form-group label {
  font-weight: 600;
  color: #38598b;
  font-size: 1.05rem;
}
.form-control, .w3-input, .w3-select {
  border-radius: 7px !important;
  border: 1px solid #b4c7e7 !important;
  box-shadow: 0 1px 4px -2px #38598b18 !important;
  font-size: 1.12rem !important;
  padding: 10px 14px !important;
}
.w3-card-4 {
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
}
.photo-qrcode-flex {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}
.batch-photo-frame {
  width: 150px;
  height: 150px;
  display: inline-block;
  border: 2px solid #dedede;
  background: #f0f0f0;
  overflow: hidden;
  box-sizing: border-box;
}
.batch-photo-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
}
.batch-qrcode-frame {
  width: 150px;
  height: 150px;
  display: inline-block;
  background: #fff;
  border: 2px solid #dedede;
  box-sizing: border-box;
  overflow: hidden;
  margin-left: 16px;
  position: relative;
}
#batch-qr-container canvas, #batch-qr-container img {
  width: 100% !important;
  height: 100% !important;
  object-fit: contain;
}
.batch-qr-btn-wrap {
  margin-top: 8px;
  text-align: center;
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
  .dashboard-header {
    padding: 21px 8px 14px 8px;
  }
  .photo-qrcode-flex {
    flex-direction: column;
    gap: 10px;
  }
}
</style>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2><b><i class="fa fa-plus-circle"></i> Add New Batch - <?php echo $page_title; ?></b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
    <!-- Search and QR scan bar -->
    <div style="display:flex;align-items:center;gap:8px;margin-top:10px;">
      <form method="get" action="pig_batches.php" style="margin:0;">
        <input class="w3-input w3-border" type="text" name="search" placeholder="Search batch..." style="height:35px;width:160px;display:inline-block;">
      </form>
      <button id="scan-qr-btn-header" title="Scan QR code" class="btn btn-success" style="padding:7px 12px;"><i class="fa fa-qrcode"></i></button>
      <div id="qr-reader-header" style="width: 250px; display:none; position:absolute; z-index:9999; background:#fff; border-radius:6px; box-shadow:0 2px 10px rgba(0,0,0,0.2);"></div>
    </div>
  </header>

  <div class="dashboard-card" style="margin:38px 38px 0 38px;">
    <form method="post" action="save-batch.php" enctype="multipart/form-data" onsubmit="return validatePigCounts()">
      <input type="hidden" name="source" value="<?php echo $source; ?>">
      <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">

      <div class="w3-row-padding">
        <div class="w3-col m6">
          <label>Batch ID (Auto-generated)</label>
          <input class="w3-input w3-border" type="text" name="batch_id" id="batch_id" value="BATCH-<?php echo date('YmdHis'); ?>" readonly>
        </div>
        <div class="w3-col m6">
          <label>Batch Photo</label>
          <input class="w3-input w3-border" type="file" name="photo" accept="image/*" onchange="logFileUploadAttempt()">
        </div>
      </div>

      <!-- display image and qr code side by side in flex -->
      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="photo-qrcode-flex">
          <span class="batch-photo-frame">
            <img id="batch-photo-preview" src="#" alt="(No Image Yet)" style="display:none;">
          </span>
          <span class="batch-qrcode-frame">
            <div id="batch-qr-container"></div>
            <div class="batch-qr-btn-wrap">
              <button type="button" class="btn btn-info" onclick="generateBatchQr()">Generate QR Code</button>
            </div>
          </span>
        </div>
      </div>

      <?php if($source == 'farm'): ?>
        <div class="w3-row-padding" style="margin-top:15px;">
          <div class="w3-col m6">
            <label>Sow ID</label>
            <select class="w3-select w3-border" name="sow_id" required onchange="logSowSelection(this.value)">
              <option value="">Select Sow</option>
              <?php
              $sows = $db->query("SELECT * FROM sow_gilt_records WHERE status = 'active'");
              while($sow = $sows->fetch(PDO::FETCH_OBJ)) {
                echo '<option value="'.$sow->id.'">'.$sow->id.'</option>';
              }
              ?>
            </select>
          </div>
          <div class="w3-col m6">
            <label>Farrowing Date</label>
            <input class="w3-input w3-border" type="date" name="birth_date" required>
          </div>
        </div>
      <?php else: ?>
        <div class="w3-row-padding" style="margin-top:15px;">
          <div class="w3-col m6">
            <label>Purchase Date</label>
            <input class="w3-input w3-border" type="date" name="birth_date" required>
          </div>
          <div class="w3-col m6">
            <label>Supplier Name</label>
            <input class="w3-input w3-border" type="text" name="supplier_name" onblur="logSupplierInput(this.value)">
          </div>
        </div>
      <?php endif; ?>

      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m4">
          <label>Breed</label>
          <select class="w3-select w3-border" name="breed_id" required onchange="logBreedSelection(this.options[this.selectedIndex].text)">
            <option value="">Select Breed</option>
            <?php
            $breeds = $db->query("SELECT * FROM breed");
            while($breed = $breeds->fetch(PDO::FETCH_OBJ)) {
              echo '<option value="'.$breed->id.'">'.$breed->name.'</option>';
            }
            ?>
          </select>
        </div>
        <div class="w3-col m4">
          <label>Total Pigs</label>
          <input class="w3-input w3-border" type="number" name="total_pigs" id="total_pigs" min="1" required onchange="validateCounts(); logPigCountChange('total', this.value)">
        </div>
        <div class="w3-col m4">
          <label>Average Weight (kg)</label>
          <input class="w3-input w3-border" type="number" step="0.01" name="weight_avg" onblur="logWeightInput(this.value)">
        </div>
      </div>

      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m6">
          <label>Male Count</label>
          <input class="w3-input w3-border" type="number" name="male_count" id="male_count" min="0" onchange="validateCounts(); logPigCountChange('male', this.value)">
        </div>
        <div class="w3-col m6">
          <label>Female Count</label>
          <input class="w3-input w3-border" type="number" name="female_count" id="female_count" min="0" onchange="validateCounts(); logPigCountChange('female', this.value)">
        </div>
      </div>

      <div class="w3-col m12" style="margin-top:15px;">
        <label>Assign Pigs to Pens (click pens to assign)</label>
        <div>
          <canvas id="floorCanvas" width="800" height="500" style="border:1px solid #ccc;"></canvas>
        </div>
        <input type="hidden" name="pen_assignments" id="penAssignments">
        <div id="penAssignInfo" class="w3-small w3-padding"></div>
      </div>

      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m12">
          <label>Notes/Remarks</label>
          <textarea class="w3-input w3-border" name="remark" rows="3" onblur="if(this.value) logNoteEntry(this.value)"></textarea>
        </div>
      </div>

      <div id="countError" class="w3-panel w3-red w3-display-container" style="display:none; margin-top:15px;">
        <span onclick="this.parentElement.style.display='none'"
        class="w3-button w3-red w3-large w3-display-topright">&times;</span>
        <p>Error: The sum of male and female counts cannot exceed the total number of pigs.</p>
      </div>

      <div class="w3-row-padding" style="margin-top:20px;">
        <div class="w3-col m12 w3-center">
          <button type="submit" name="save_batch" class="w3-button w3-blue w3-round">
            <i class="fa fa-save"></i> Save Batch
          </button>
          <a href="pig_batches.php" class="w3-button w3-gray w3-round" onclick="logAction('batch_creation_canceled', 'Canceled new batch creation')">
            <i class="fa fa-times"></i> Cancel
          </a>
        </div>
      </div>
    </form>
    <!-- QR Code Scanner Section -->
    <hr>
    <div style="margin-top:25px;">
      <button id="scan-qr-btn" class="btn btn-success"><i class="fa fa-qrcode"></i> Scan QR Code</button>
      <div id="qr-reader" style="width: 300px; margin-top:10px;"></div>
      <div id="scan-batch-info" style="margin-top:10px;"></div>
    </div>
    <!-- End QR Section -->
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let penData = [];
let penPigCounts = {};
let assignedPigs = {};
let totalToAssign = 0;

function drawFloorPlan() {
  const canvas = document.getElementById('floorCanvas');
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  penData.forEach(pen => {
    ctx.save();
    let current = penPigCounts[pen.id] || 0;
    let assigned = assignedPigs[pen.id] || 0;
    let free = pen.capacity - current - assigned;

    ctx.strokeStyle = assigned ? "#ffa500" : "#008000";
    ctx.lineWidth = 2;
    ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);

    ctx.font = "13px Arial";
    ctx.fillStyle = "black";
    ctx.fillText(pen.label, pen.x + 5, pen.y + 15);
    ctx.font = "12px Arial";
    ctx.fillText("Cap:" + pen.capacity, pen.x + 5, pen.y + 32);
    ctx.fillText("Curr:" + current, pen.x + 5, pen.y + 48);
    if (assigned) {
      ctx.fillStyle = "#ffa500";
      ctx.fillText("Assigned: " + assigned, pen.x + 5, pen.y + 64);
    }
    ctx.restore();
  });
}

function updatePenAssignInfo() {
  let sum = Object.values(assignedPigs).reduce((a, b) => a + b, 0);
  let info = `Assigned pigs: ${sum} / ${totalToAssign}`;
  document.getElementById('penAssignInfo').innerText = info;
  document.getElementById('penAssignments').value = JSON.stringify(assignedPigs);
}

function setupFloorPlanAssign() {
  const canvas = document.getElementById('floorCanvas');
  canvas.onclick = function(e) {
    const rect = canvas.getBoundingClientRect();
    const mx = e.clientX - rect.left, my = e.clientY - rect.top;
    for (let pen of penData) {
      if (mx >= pen.x && mx <= pen.x + pen.width && my >= pen.y && my <= pen.y + pen.height) {
        let current = penPigCounts[pen.id] || 0;
        let assigned = assignedPigs[pen.id] || 0;
        let free = pen.capacity - current - assigned;
        let remaining = totalToAssign - Object.values(assignedPigs).reduce((a, b) => a + b, 0);

        if (free <= 0 || remaining <= 0) {
          alert('No space left here or all pigs assigned');
          return;
        }
        let maxAssign = Math.min(free, remaining);
        let toAssign = parseInt(prompt(`Assign how many pigs to "${pen.label}"? (max ${maxAssign})`, maxAssign));
        if (!toAssign || isNaN(toAssign) || toAssign < 1 || toAssign > maxAssign) return;
        assignedPigs[pen.id] = (assignedPigs[pen.id] || 0) + toAssign;
        drawFloorPlan();
        updatePenAssignInfo();
        return;
      }
    }
  };
}

function fetchPensAndCounts() {
  fetch('load_pens.php')
    .then(res => res.json())
    .then(pens => {
      penData = pens;
      return fetch('pen_pig_counts_by_id.php');
    })
    .then(res => res.json())
    .then(counts => {
      penPigCounts = counts;
      drawFloorPlan();
    });
}

function validateCounts() {
  var totalPigs = parseInt(document.getElementById('total_pigs').value) || 0;
  var maleCount = parseInt(document.getElementById('male_count').value) || 0;
  var femaleCount = parseInt(document.getElementById('female_count').value) || 0;
  var sum = maleCount + femaleCount;
  if (sum > totalPigs) {
    document.getElementById('countError').style.display = 'block';
    logAction('count_validation_failed', `Count validation failed: Total ${totalPigs} vs Male ${maleCount} + Female ${femaleCount}`);
    return false;
  } else {
    document.getElementById('countError').style.display = 'none';
    return true;
  }
}

function validatePigCounts() {
  if (!validateCounts()) {
    alert("Please correct the counts: Male + Female cannot exceed Total Pigs.");
    return false;
  }
  let total = parseInt(document.getElementById('total_pigs').value) || 0;
  let sum = Object.values(assignedPigs).reduce((a,b)=>a+b,0);
  if (sum < total) {
    alert("Not enough pen space assigned for all pigs!");
    return false;
  }
  logAction('batch_submission_attempt', 'Attempting to submit batch data');
  return true;
}

// Logging functions (unchanged)
function logPenSelection(penLabel) { /* (optional: not used in canvas mode) */ }
function logSowSelection(sowId) {
  if(sowId) {
    fetch('log_audit.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `user_id=<?php echo $_SESSION['id']; ?>&action=sow_selection&details=Selected sow ID: ${sowId}`
    });
  }
}
function logBreedSelection(breedName) {
  if(breedName) {
    fetch('log_audit.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `user_id=<?php echo $_SESSION['id']; ?>&action=breed_selection&details=Selected breed: ${breedName}`
    });
  }
}
function logPigCountChange(type, count) {
  fetch('log_audit.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `user_id=<?php echo $_SESSION['id']; ?>&action=pig_count_change&details=Updated ${type} count to ${count}`
  });
}
function logWeightInput(weight) {
  if(weight) {
    fetch('log_audit.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `user_id=<?php echo $_SESSION['id']; ?>&action=weight_input&details=Entered average weight: ${weight} kg`
    });
  }
}
function logSupplierInput(supplier) {
  if(supplier) {
    fetch('log_audit.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `user_id=<?php echo $_SESSION['id']; ?>&action=supplier_input&details=Entered supplier: ${supplier}`
    });
  }
}
function logFileUploadAttempt() {
  fetch('log_audit.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `user_id=<?php echo $_SESSION['id']; ?>&action=file_upload_attempt&details=Attempted to upload batch photo`
  });
}
function logNoteEntry(note) {
  fetch('log_audit.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `user_id=<?php echo $_SESSION['id']; ?>&action=note_entry&details=Added note: ${note.substring(0, 50)}...`
  });
}
function logAction(action, details) {
  fetch('log_audit.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `user_id=<?php echo $_SESSION['id']; ?>&action=${action}&details=${details}`
  });
}

document.getElementById('total_pigs').addEventListener('input', function() {
  totalToAssign = parseInt(this.value) || 0;
  assignedPigs = {};
  drawFloorPlan();
  updatePenAssignInfo();
});

window.onload = () => {
  fetchPensAndCounts();
  setupFloorPlanAssign();
  totalToAssign = parseInt(document.getElementById('total_pigs').value) || 0;
  updatePenAssignInfo();
  generateBatchQr(); // auto show QR code on load
};

// === Batch Photo Preview ===
document.querySelector('input[name="photo"]').addEventListener('change', function(e){
  var reader = new FileReader();
  reader.onload = function(e2){
    var img = document.getElementById('batch-photo-preview');
    img.src = e2.target.result;
    img.style.display = "block";
  };
  if(this.files && this.files[0]) reader.readAsDataURL(this.files[0]);
});

// === QR Code Generator ===
function generateBatchQr() {
  var batchId = document.getElementById('batch_id').value;
  var qrContainer = document.getElementById('batch-qr-container');
  qrContainer.innerHTML = "";
  var url = window.location.origin + '/view-batch.php?batch_id=' + encodeURIComponent(batchId);
  new QRCode(qrContainer, {
    text: url,
    width: 150,
    height: 150
  });
}

// === QR Code Scanner (main form) ===
let qrScannerInstance = null;
document.getElementById("scan-qr-btn").addEventListener("click", function () {
    if (qrScannerInstance) {
      qrScannerInstance.clear();
    }
    qrScannerInstance = new Html5QrcodeScanner("qr-reader", {
        fps: 10,
        qrbox: 250
    });
    qrScannerInstance.render(
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
                fetch('scan-batch-info.php?data=' + encodeURIComponent(decodedText))
                  .then(res => res.json())
                  .then(obj => {
                    if(obj.status === 'ok') {
                      document.getElementById('scan-batch-info').innerHTML =
                        `<div class="w3-panel w3-green">Batch: <b>${obj.batch_id}</b><br>Breed: ${obj.breed}<br>Location: ${obj.location}</div>`;
                    } else {
                      document.getElementById('scan-batch-info').innerHTML =
                        `<div class="w3-panel w3-red">Batch not found.</div>`;
                    }
                  });
            }
            qrScannerInstance.clear();
        },
        (errorMessage) => {}
    );
});

// === QR Code Scanner (header, beside search bar) ===
let qrScannerHeader = null;
const headerScanBtn = document.getElementById("scan-qr-btn-header");
const qrReaderHeader = document.getElementById("qr-reader-header");
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
</script>

<?php include 'theme/foot.php'; ?>