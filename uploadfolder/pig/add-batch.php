<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Function to get client IP address
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // Convert IPv6 loopback to IPv4 for consistency
    return $ip === '::1' ? '127.0.0.1' : $ip;
}

// Get source type from URL and log the access
$source = isset($_GET['source']) ? $_GET['source'] : 'farm';
$page_title = $source == 'farm' ? 'Farm Production Batch' : 'External Purchase Batch';

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

<!-- Rest of your HTML/PHP code remains exactly the same -->

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-plus-circle"></i> Add New Batch - <?php echo $page_title; ?></b></h5>
  </header>

  <div class="w3-container">
    <div class="w3-card-4 w3-white" style="padding:20px;">
      <form method="post" action="save-batch.php" enctype="multipart/form-data" onsubmit="return validatePigCounts()">
        <input type="hidden" name="source" value="<?php echo $source; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">

        <!-- Batch Info Section -->
        <div class="w3-row-padding">
          <div class="w3-col m6">
            <label>Batch ID (Auto-generated)</label>
            <input class="w3-input w3-border" type="text" name="batch_id" value="BATCH-<?php echo date('YmdHis'); ?>" readonly>
          </div>
          <div class="w3-col m6">
            <label>Batch Photo</label>
            <input class="w3-input w3-border" type="file" name="photo" accept="image/*" onchange="logFileUploadAttempt()">
          </div>
        </div>

        <!-- Source-Specific Fields -->
        <?php if($source == 'farm'): ?>
          <!-- Farm Production Fields -->
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
          <!-- External Purchase Fields -->
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

        <!-- Common Fields -->
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

        <div class="w3-col m12">
          <label>Select Pen</label>
          <div style="border:1px solid #ccc; width:800px; height:500px; position:relative;" id="penArea"></div>
          <input type="hidden" name="location" id="selectedPen">
        </div>

        <script>
        fetch('load_pens.php')
          .then(res => res.json())
          .then(pens => {
            const area = document.getElementById('penArea');
            pens.forEach(pen => {
              let div = document.createElement('div');
              div.style.position = 'absolute';
              div.style.left = pen.x + 'px';
              div.style.top = pen.y + 'px';
              div.style.width = pen.width + 'px';
              div.style.height = pen.height + 'px';
              div.style.border = '2px solid blue';
              div.innerText = pen.label;
              div.style.textAlign = 'center';
              div.style.cursor = 'pointer';
              div.addEventListener('click', () => {
                document.getElementById('selectedPen').value = pen.label;
                logPenSelection(pen.label);
                alert('Selected Pen: ' + pen.label);
              });
              area.appendChild(div);
            });
          });

        // Audit logging functions
        function logPenSelection(penLabel) {
          fetch('log_audit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `user_id=<?php echo $_SESSION['id']; ?>&action=pen_selection&details=Selected pen: ${penLabel}`
          });
        }

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
        </script>

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
    </div>
  </div>
</div>

<script>
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
  logAction('batch_submission_attempt', 'Attempting to submit batch data');
  return true;
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
</script>

<?php include 'theme/foot.php'; ?>