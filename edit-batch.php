<?php 
include 'setting/system.php'; 
include 'theme/head.php'; 
include 'theme/sidebar.php'; 
include 'session.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
if(!$id) {
  header("Location: pig_batches.php");
  exit;
}

// Fetch batch details
$stmt = $db->prepare("SELECT * FROM pig_batches WHERE id = ?");
$stmt->execute([$id]);
$batch = $stmt->fetch(PDO::FETCH_OBJ);
if(!$batch) {
  echo "<script>alert('Batch not found.'); window.location='pig_batches.php';</script>";
  exit;
}

$source = $batch->source;
$page_title = $source == 'farm' ? 'Farm Production Batch' : 'External Purchase Batch';

// Fetch all pens (for floor plan and assigning)
$pens = $db->query("SELECT * FROM pens")->fetchAll(PDO::FETCH_ASSOC);

// Fetch pen assignments for this batch from batch_pens table (editable)
$batchPens = [];
$penAssignments = $db->prepare("SELECT pen_id, pigs_assigned FROM batch_pens WHERE batch_id = ?");
$penAssignments->execute([$batch->batch_id]);
foreach($penAssignments as $row) {
  $batchPens[$row['pen_id']] = $row['pigs_assigned'];
}

// Fetch pig count per pen for visualization (excluding current batch's assignments)
$penPigCounts = [];
$q = $db->prepare("SELECT pen_id, SUM(pigs_assigned) as pig_count FROM batch_pens WHERE batch_id != ? GROUP BY pen_id");
$q->execute([$batch->batch_id]);
foreach ($q as $row) {
    $penPigCounts[$row['pen_id']] = intval($row['pig_count']);
}

// ====== QR CODE GENERATION FOR BATCHES WITHOUT A QR CODE ======
if (empty($batch->qr_code) && !empty($batch->batch_id)) {
    require_once 'phpqrcode/qrlib.php';
    $qr_dir = 'qrcodes/';
    if(!is_dir($qr_dir)){
        mkdir($qr_dir, 0777, true);
    }
    $qr_filename = $qr_dir . 'batch_' . $batch->batch_id . '.png';
    $qr_full_path = __DIR__ . '/' . $qr_filename;
    $qr_data = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
               "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) .
               '/view-batch.php?batch_id=' . urlencode($batch->batch_id);
    QRcode::png($qr_data, $qr_full_path);
    // Update DB with QR code path
    $stmt = $db->prepare("UPDATE pig_batches SET qr_code=? WHERE id=?");
    $stmt->execute([$qr_filename, $id]);
    // Also update batch object so it works in the page below
    $batch->qr_code = $qr_filename;
}
?>

<style>
/* ... (your style unchanged) ... */
.dashboard-main {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
}
/* ... rest of your styles ... */
.batch-photo-frame {
  width: 150px;
  height: 150px;
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
  width: 150px;
  height: 150px;
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
</style>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2>
          <b>
            <i class="fa fa-edit"></i> Edit Batch - <?php echo htmlspecialchars($page_title); ?>
          </b>
        </h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>

  <div class="dashboard-card" style="margin:38px 38px 0 38px;">
    <form method="post" action="update-batch.php" enctype="multipart/form-data" onsubmit="return validatePigCounts()">
      <input type="hidden" name="id" value="<?php echo $batch->id; ?>">
      <input type="hidden" name="source" value="<?php echo $source; ?>">
      <input type="hidden" name="batch_id" id="batch_id" value="<?php echo $batch->batch_id; ?>">

      <div class="w3-row-padding">
        <div class="w3-col m6">
          <label>Batch ID</label>
          <input class="w3-input w3-border" type="text" value="<?php echo $batch->batch_id; ?>" readonly>
        </div>
        <div class="w3-col m6">
          <label>Change Batch Photo (optional)</label>
          <input class="w3-input w3-border" type="file" name="photo" accept="image/*">
        </div>
      </div>

      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m6" style="display: flex; align-items: flex-start;">
          <div>
            <label>Batch Photo</label><br>
            <span class="batch-photo-frame">
              <?php if (!empty($batch->photo)): ?>
                <img src="<?php echo htmlspecialchars($batch->photo); ?>" alt="Batch Photo">
              <?php else: ?>
                <span style="color: #ccc;display:inline-block;width:100%;height:100%;text-align:center;line-height:150px;">No image</span>
              <?php endif; ?>
            </span>
          </div>
          <div style="margin-left:30px;">
            <label>Batch QR Code</label>
            <span class="batch-qrcode-frame">
              <?php if (!empty($batch->qr_code) && file_exists($batch->qr_code)): ?>
                <img src="<?php echo $batch->qr_code; ?>" alt="Batch QR Code">
              <?php else: ?>
                <span style="color: #ccc;display:inline-block;width:100%;height:100%;text-align:center;line-height:150px;font-size:2em;"><i class="fa fa-qrcode"></i></span>
              <?php endif; ?>
            </span>
            <div id="batch-qr-container" style="margin-top:8px;"></div>
            <button type="button" class="btn btn-info" onclick="generateBatchQr()"><i class="fa fa-qrcode"></i> Generate QR Code</button>
          </div>
        </div>
      </div>

      <?php if($source == 'farm'): ?>
      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m6">
          <label>Sow ID</label>
          <select class="w3-select w3-border" name="sow_id" required>
            <option value="">Select Sow</option>
            <?php
            $sows = $db->query("SELECT * FROM sow_gilt_records WHERE status = 'active'");
            while($sow = $sows->fetch(PDO::FETCH_OBJ)) {
              $selected = $batch->sow_id == $sow->id ? 'selected' : '';
              echo "<option value='$sow->id' $selected>$sow->id</option>";
            }
            ?>
          </select>
        </div>
        <div class="w3-col m6">
          <label>Farrowing Date</label>
          <input class="w3-input w3-border" type="date" name="birth_date" value="<?php echo $batch->birth_date; ?>" required>
        </div>
      </div>
      <?php else: ?>
      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m6">
          <label>Purchase Date</label>
          <input class="w3-input w3-border" type="date" name="birth_date" value="<?php echo $batch->birth_date; ?>" required>
        </div>
        <div class="w3-col m6">
          <label>Supplier Name</label>
          <input class="w3-input w3-border" type="text" name="supplier_name" value="<?php echo $batch->supplier_name; ?>">
        </div>
      </div>
      <?php endif; ?>

      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m4">
          <label>Breed</label>
          <select class="w3-select w3-border" name="breed_id" required>
            <option value="">Select Breed</option>
            <?php
            $breeds = $db->query("SELECT * FROM breed");
            while($breed = $breeds->fetch(PDO::FETCH_OBJ)) {
              $selected = $batch->breed_id == $breed->id ? 'selected' : '';
              echo "<option value='$breed->id' $selected>$breed->name</option>";
            }
            ?>
          </select>
        </div>
        <div class="w3-col m4">
          <label>Total Pigs</label>
          <input class="w3-input w3-border" type="number" name="total_pigs" id="total_pigs" value="<?php echo $batch->total_pigs; ?>" min="1" required>
        </div>
        <div class="w3-col m4">
          <label>Average Weight (kg)</label>
          <input class="w3-input w3-border" type="number" step="0.01" name="weight_avg" value="<?php echo $batch->weight_avg; ?>">
        </div>
      </div>

      <div class="w3-row-padding" style="margin-top:15px;">
        <div class="w3-col m6">
          <label>Male Count</label>
          <input class="w3-input w3-border" type="number" name="male_count" id="male_count" value="<?php echo $batch->male_count; ?>" min="0">
        </div>
        <div class="w3-col m6">
          <label>Female Count</label>
          <input class="w3-input w3-border" type="number" name="female_count" id="female_count" value="<?php echo $batch->female_count; ?>" min="0">
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
          <textarea class="w3-input w3-border" name="remark" rows="3"><?php echo htmlspecialchars($batch->remark); ?></textarea>
        </div>
      </div>

      <div id="countError" class="w3-panel w3-red w3-display-container" style="display:none; margin-top:15px;">
        <span onclick="this.parentElement.style.display='none'"
        class="w3-button w3-red w3-large w3-display-topright">&times;</span>
        <p>Error: The sum of male and female counts cannot exceed the total number of pigs.</p>
      </div>

      <div class="w3-row-padding" style="margin-top:20px;">
        <div class="w3-col m12 w3-center">
          <button type="submit" name="update_batch" class="w3-button w3-green w3-round">
            <i class="fa fa-save"></i> Update Batch
          </button>
          <a href="pig_batches.php" class="w3-button w3-gray w3-round">
            <i class="fa fa-times"></i> Cancel
          </a>
        </div>
      </div>
    </form>
    <hr>
    <div style="margin-top:25px;">
      <button id="scan-qr-btn" class="btn btn-success"><i class="fa fa-qrcode"></i> Scan QR Code</button>
      <div id="qr-reader" style="width: 300px; margin-top:10px;"></div>
      <div id="scan-batch-info" style="margin-top:10px;"></div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let penData = <?php echo json_encode($pens); ?>;
let penPigCounts = <?php echo json_encode($penPigCounts); ?>;
let assignedPigs = <?php echo json_encode($batchPens); ?>;
let totalToAssign = <?php echo intval($batch->total_pigs); ?>;

function drawFloorPlan() {
  const canvas = document.getElementById('floorCanvas');
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  penData.forEach(pen => {
    ctx.save();
    let current = penPigCounts[pen.id] || 0;
    let assigned = assignedPigs[pen.id] || 0;
    let totalInPen = current + assigned;
    let free = pen.capacity - totalInPen;
    ctx.strokeStyle = assigned ? "#ffa500" : "#008000";
    ctx.lineWidth = 2;
    ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);

    ctx.font = "13px Arial";
    ctx.fillStyle = "black";
    ctx.fillText(pen.label, pen.x + 5, pen.y + 15);
    ctx.font = "12px Arial";
    ctx.fillText("Cap:" + pen.capacity, pen.x + 5, pen.y + 32);
    ctx.fillText("Curr:" + totalInPen, pen.x + 5, pen.y + 48);
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
        let totalInPen = current + assigned;
        let free = pen.capacity - totalInPen + assigned;
        let remaining = totalToAssign - Object.values(assignedPigs).reduce((a, b) => a + b, 0) + assigned;
        if (free <= 0 && !assigned) {
          alert('No space left here or all pigs assigned');
          return;
        }
        let maxAssign = Math.min(free, remaining);
        let toAssign = parseInt(prompt(`Assign how many pigs to "${pen.label}"? (max ${maxAssign})`, assigned || maxAssign));
        if (toAssign === null || isNaN(toAssign) || toAssign < 0 || toAssign > maxAssign) return;
        assignedPigs[pen.id] = toAssign;
        drawFloorPlan();
        updatePenAssignInfo();
        return;
      }
    }
  };
}

function validateCounts() {
  var totalPigs = parseInt(document.getElementById('total_pigs').value) || 0;
  var maleCount = parseInt(document.getElementById('male_count').value) || 0;
  var femaleCount = parseInt(document.getElementById('female_count').value) || 0;
  var sum = maleCount + femaleCount;
  if (sum > totalPigs) {
    document.getElementById('countError').style.display = 'block';
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
  return true;
}

document.getElementById('total_pigs').addEventListener('input', function() {
  totalToAssign = parseInt(this.value) || 0;
  assignedPigs = {};
  drawFloorPlan();
  updatePenAssignInfo();
});

window.onload = () => {
  drawFloorPlan();
  setupFloorPlanAssign();
  updatePenAssignInfo();
  generateBatchQr();
};

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

// === QR Code Scanner ===
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
        (errorMessage) => {
            // Optional: show scanning errors
        }
    );
});
</script>

<?php include 'theme/foot.php'; ?>