<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Get batch ID if specified
$batch_id = isset($_GET['batch_id']) ? $_GET['batch_id'] : null;
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <div class="w3-col m8">
        <h5><b><i class="fa fa-medkit"></i> Medication & Vaccination Management</b></h5>
      </div>
      <div class="w3-col m4 w3-right-align">
        <button onclick="document.getElementById('addMedModal').style.display='block'" 
                class="w3-button w3-blue w3-round">
          <i class="fa fa-plus"></i> Add Medication
        </button>
      </div>
    </div>
  </header>

  <div class="w3-container">
    <!-- Batch Selection -->
    <div class="w3-card w3-white w3-padding w3-margin-bottom">
      <form method="get" action="medication.php">
        <div class="w3-row-padding">
          <div class="w3-col m8">
            <label>Select Batch</label>
            <select class="w3-select w3-border" name="batch_id" onchange="this.form.submit()">
              <option value="">All Batches</option>
              <?php
              $batches = $db->query("SELECT batch_id, birth_date FROM pig_batches ORDER BY birth_date DESC");
              while($batch = $batches->fetch(PDO::FETCH_OBJ)) {
                $selected = $batch_id == $batch->batch_id ? 'selected' : '';
                $age = floor((time() - strtotime($batch->birth_date)) / (60 * 60 * 24));
                echo "<option value='$batch->batch_id' $selected>$batch->batch_id (Age: $age days)</option>";
              }
              ?>
            </select>
          </div>
          <div class="w3-col m4">
            <label>View</label>
            <select class="w3-select w3-border" id="viewSelector" onchange="changeView()">
              <option value="calendar">Calendar View</option>
              <option value="list">List View</option>
              <option value="timeline">Timeline View</option>
            </select>
          </div>
        </div>
      </form>
    </div>

    <!-- Calendar View -->
    <div id="calendarView" class="w3-card w3-white w3-padding">
      <h4>Medication Calendar</h4>
      <div id="medCalendar" style="width: 100%; height: 600px;"></div>
    </div>

    <!-- List View (Hidden by default) -->
    <div id="listView" class="w3-card w3-white w3-padding" style="display:none;">
      <h4>Medication Records</h4>
      <table class="w3-table-all w3-hoverable">
        <thead>
          <tr class="w3-light-grey">
            <th>Date</th>
            <th>Batch</th>
            <th>Medication</th>
            <th>Category</th>
            <th>Dosage</th>
            <th>Administered By</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT m.*, b.batch_id 
                    FROM medication m 
                    LEFT JOIN pig_batches b ON m.batch_id = b.batch_id";
          
          if($batch_id) {
            $query .= " WHERE m.batch_id = :batch_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':batch_id', $batch_id);
            $stmt->execute();
          } else {
            $stmt = $db->query($query);
          }
          
          while($med = $stmt->fetch(PDO::FETCH_OBJ)):
          ?>
          <tr>
            <td><?php echo date('M d, Y', strtotime($med->admin_date)); ?></td>
            <td><?php echo $med->batch_id; ?></td>
            <td><?php echo $med->name; ?></td>
            <td>
              <span class="w3-tag w3-<?php 
                echo $med->med_type == 'vaccine' ? 'blue' : 
                     ($med->med_type == 'antibiotic' ? 'red' : 
                     ($med->med_type == 'vitamin' ? 'green' : 'orange'));
              ?> w3-round">
                <?php echo ucfirst($med->med_type); ?>
              </span>
            </td>
            <td><?php echo $med->dosage; ?></td>
            <td><?php echo $med->administered_by; ?></td>
            <td>
              <button onclick="editMedication(<?php echo $med->id; ?>)" 
                      class="w3-button w3-blue w3-tiny w3-round">
                <i class="fa fa-edit"></i>
              </button>
              <button onclick="confirmDelete(<?php echo $med->id; ?>)" 
                      class="w3-button w3-red w3-tiny w3-round">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Timeline View (Hidden by default) -->
    <div id="timelineView" class="w3-card w3-white w3-padding" style="display:none;">
      <h4>Medication Timeline</h4>
      <div class="w3-container" style="position:relative;margin-top:50px;">
        <?php
        if($batch_id) {
          $batch_info = $db->prepare("SELECT * FROM pig_batches WHERE batch_id = ?");
          $batch_info->execute([$batch_id]);
          $batch = $batch_info->fetch(PDO::FETCH_OBJ);
          
          $birth_date = new DateTime($batch->birth_date);
          $today = new DateTime();
          $age = $today->diff($birth_date)->days;
          
          echo '<div class="w3-light-grey" style="height:30px;position:relative;margin-bottom:50px;">';
          echo '<div class="w3-container w3-center w3-padding w3-blue" style="width:100%;">Batch Age: '.$age.' days</div>';
          
          // Get all medications for this batch
          $meds = $db->prepare("SELECT * FROM medication WHERE batch_id = ? ORDER BY admin_date");
          $meds->execute([$batch_id]);
          
          while($med = $meds->fetch(PDO::FETCH_OBJ)) {
            $med_date = new DateTime($med->admin_date);
            $days_old = $birth_date->diff($med_date)->days;
            $position = min(100, ($days_old / 180) * 100); // Cap at 180 days
            
            echo '<div class="w3-tooltip" style="position:absolute;left:'.$position.'%;top:40px;">';
            echo '<span class="w3-tag w3-';
            echo $med->med_type == 'vaccine' ? 'blue' : 
                 ($med->med_type == 'antibiotic' ? 'red' : 
                 ($med->med_type == 'vitamin' ? 'green' : 'orange'));
            echo ' w3-round" style="cursor:pointer;">'.substr($med->name, 0, 1).'</span>';
            echo '<span class="w3-text w3-tag w3-white w3-border" style="position:absolute;left:-50px;bottom:30px;width:150px;">';
            echo '<b>'.$med->name.'</b><br>';
            echo date('M d', strtotime($med->admin_date)).' (Day '.$days_old.')<br>';
            echo $med->dosage.'<br>';
            echo 'By: '.$med->administered_by;
            echo '</span>';
            echo '</div>';
          }
          echo '</div>';
          
          // Growth stage markers
          $stages = [
            ['name' => 'Farrowing', 'end' => 21],
            ['name' => 'Nursery', 'end' => 42],
            ['name' => 'Grower', 'end' => 70],
            ['name' => 'Finisher', 'end' => 180]
          ];
          
          echo '<div class="w3-light-grey" style="height:20px;position:relative;">';
          foreach($stages as $stage) {
            $position = min(100, ($stage['end'] / 180) * 100);
            echo '<div style="position:absolute;left:'.$position.'%;top:0;border-left:1px dashed #000;height:20px;"></div>';
            echo '<div style="position:absolute;left:'.($position-5).'%;top:25px;">'.$stage['name'].'</div>';
          }
          echo '</div>';
        } else {
          echo '<p class="w3-panel w3-yellow">Please select a batch to view timeline</p>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Medication Modal -->
<div id="addMedModal" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
    <header class="w3-container w3-blue"> 
      <span onclick="document.getElementById('addMedModal').style.display='none'" 
            class="w3-button w3-display-topright">&times;</span>
      <h2>Add Medication Record</h2>
    </header>
    
    <form class="w3-container" method="post" action="save-medication.php">
      <input type="hidden" name="batch_id" value="<?php echo $batch_id; ?>">
      
      <div class="w3-section">
        <label><b>Medication Name</b></label>
        <input class="w3-input w3-border" type="text" name="name" required>
        
        <label><b>Category</b></label>
        <select class="w3-select w3-border" name="med_type" required>
          <option value="vaccine">Vaccine</option>
          <option value="antibiotic">Antibiotic</option>
          <option value="vitamin">Vitamin</option>
          <option value="deworming">Deworming</option>
          <option value="other">Other</option>
        </select>
        
        <div class="w3-row-padding">
          <div class="w3-half">
            <label><b>Dosage</b></label>
            <input class="w3-input w3-border" type="text" name="dosage" required>
          </div>
          <div class="w3-half">
            <label><b>Date Administered</b></label>
            <input class="w3-input w3-border" type="date" name="admin_date" required value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
        
        <div class="w3-row-padding">
          <div class="w3-half">
            <label><b>Next Dose Date (if applicable)</b></label>
            <input class="w3-input w3-border" type="date" name="next_date">
          </div>
          <div class="w3-half">
            <label><b>Administered By</b></label>
            <input class="w3-input w3-border" type="text" name="administered_by" required value="<?php echo $_SESSION['full_name'] ?? ''; ?>">
          </div>
        </div>
        
        <label><b>Notes</b></label>
        <textarea class="w3-input w3-border" name="notes" rows="3"></textarea>
        
        <button class="w3-button w3-blue w3-block w3-section w3-padding" type="submit" name="save_med">
          <i class="fa fa-save"></i> Save Medication
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Medication Modal (Will be populated by JavaScript) -->
<div id="editMedModal" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
    <header class="w3-container w3-blue"> 
      <span onclick="document.getElementById('editMedModal').style.display='none'" 
            class="w3-button w3-display-topright">&times;</span>
      <h2>Edit Medication Record</h2>
    </header>
    
    <form class="w3-container" method="post" action="update-medication.php">
      <input type="hidden" name="id" id="editMedId">
      
      <div class="w3-section">
        <label><b>Medication Name</b></label>
        <input class="w3-input w3-border" type="text" name="name" id="editMedName" required>
        
        <label><b>Category</b></label>
        <select class="w3-select w3-border" name="med_type" id="editMedType" required>
          <option value="vaccine">Vaccine</option>
          <option value="antibiotic">Antibiotic</option>
          <option value="vitamin">Vitamin</option>
          <option value="deworming">Deworming</option>
          <option value="other">Other</option>
        </select>
        
        <div class="w3-row-padding">
          <div class="w3-half">
            <label><b>Dosage</b></label>
            <input class="w3-input w3-border" type="text" name="dosage" id="editMedDosage" required>
          </div>
          <div class="w3-half">
            <label><b>Date Administered</b></label>
            <input class="w3-input w3-border" type="date" name="admin_date" id="editMedDate" required>
          </div>
        </div>
        
        <div class="w3-row-padding">
          <div class="w3-half">
            <label><b>Next Dose Date (if applicable)</b></label>
            <input class="w3-input w3-border" type="date" name="next_date" id="editMedNextDate">
          </div>
          <div class="w3-half">
            <label><b>Administered By</b></label>
            <input class="w3-input w3-border" type="text" name="administered_by" id="editMedAdminBy" required>
          </div>
        </div>
        
        <label><b>Notes</b></label>
        <textarea class="w3-input w3-border" name="notes" id="editMedNotes" rows="3"></textarea>
        
        <button class="w3-button w3-blue w3-block w3-section w3-padding" type="submit" name="update_med">
          <i class="fa fa-save"></i> Update Medication
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:500px">
    <header class="w3-container w3-red"> 
      <span onclick="document.getElementById('deleteModal').style.display='none'" 
            class="w3-button w3-display-topright">&times;</span>
      <h2>Confirm Delete</h2>
    </header>
    
    <div class="w3-container">
      <p>Are you sure you want to delete this medication record?</p>
      <div class="w3-section">
        <button class="w3-button w3-red" onclick="deleteMedication()">Delete</button>
        <button class="w3-button w3-gray" onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Include FullCalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
// Global variable to store medication ID for deletion
let medToDelete = null;

// Change view between calendar, list and timeline
function changeView() {
  const view = document.getElementById('viewSelector').value;
  document.getElementById('calendarView').style.display = view === 'calendar' ? 'block' : 'none';
  document.getElementById('listView').style.display = view === 'list' ? 'block' : 'none';
  document.getElementById('timelineView').style.display = view === 'timeline' ? 'block' : 'none';
  
  if(view === 'calendar') {
    calendar.render();
  }
}

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
  calendarEl = document.getElementById('medCalendar');
  
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: 'fetch-medications.php<?php echo $batch_id ? "?batch_id=".$batch_id : ""; ?>',
    eventClick: function(info) {
      editMedication(info.event.id);
    }
  });
  
  calendar.render();
});

// Edit medication record
function editMedication(id) {
  fetch('get-medication.php?id=' + id)
    .then(response => response.json())
    .then(data => {
      document.getElementById('editMedId').value = data.id;
      document.getElementById('editMedName').value = data.name;
      document.getElementById('editMedType').value = data.med_type;
      document.getElementById('editMedDosage').value = data.dosage;
      document.getElementById('editMedDate').value = data.admin_date;
      document.getElementById('editMedNextDate').value = data.next_date;
      document.getElementById('editMedAdminBy').value = data.administered_by;
      document.getElementById('editMedNotes').value = data.notes;
      
      document.getElementById('editMedModal').style.display = 'block';
    });
}

// Confirm delete
function confirmDelete(id) {
  medToDelete = id;
  document.getElementById('deleteModal').style.display = 'block';
}

// Delete medication record
function deleteMedication() {
  window.location.href = 'delete-medication.php?id=' + medToDelete;
}
</script>

<?php include 'theme/foot.php'; ?>