<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <div class="w3-col m8">
        <h5><b><i class="fa fa-paw"></i> Pig Batch Management Dashboard</b></h5>
      </div>
      <div class="w3-col m4 w3-right-align">
        <div class="w3-dropdown-hover">
          <button class="w3-button w3-blue"><i class="fa fa-plus"></i> Add New Batch</button>
          <div class="w3-dropdown-content w3-bar-block w3-card-4">
            <a href="add-batch.php?source=farm" class="w3-bar-item w3-button"><i class="fa fa-home"></i> Farm Production</a>
            <a href="add-batch.php?source=external" class="w3-bar-item w3-button"><i class="fa fa-truck"></i> External Purchase</a>
          </div>
        </div>
      </div>
    </div>
  </header>
 
  <?php include 'inc/data.php'; ?>
 
  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <div class="w3-col m12">
        <div class="w3-card-4 w3-white">
          <div class="w3-container w3-padding">
            <div class="w3-row">
              <div class="w3-col m6">
                <h3><i class="fa fa-list"></i> Current Batches</h3>
              </div>
              <div class="w3-col m6">
                <input class="w3-input w3-border w3-round" type="text" placeholder="Search batches..." id="searchInput">
              </div>
            </div>
          </div>
          
          <div class="w3-responsive">
            <table class="w3-table-all w3-hoverable" id="batchTable">
              <thead>
                <tr class="w3-light-grey">
                  <th>Batch ID</th>
                  <th>Photo</th>
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
                  // Calculate age and progress stage
                  $birthDate = new DateTime($batch->birth_date);
                  $today = new DateTime();
                  $age = $today->diff($birthDate)->days;
                  
                  if ($age <= 21) {
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
                  
                  // Gender ratio calculation
                  $male_percentage = ($batch->male_count / $batch->total_pigs) * 100;
                  $female_percentage = ($batch->female_count / $batch->total_pigs) * 100;
                ?>
                <tr>
                  <td><?php echo $batch->batch_id; ?></td>
                  <td>
                    <img src="<?php echo $batch->photo ?: 'assets/default_batch.jpg'; ?>" class="w3-round" style="width:60px;height:60px;object-fit:cover;">
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
                    <div class="w3-light-grey w3-round">
                      <div class="w3-container w3-blue w3-round" style="width:<?php echo $male_percentage; ?>%">
                        <?php echo $batch->male_count; ?>M
                      </div>
                      <div class="w3-container w3-pink w3-round" style="width:<?php echo $female_percentage; ?>%">
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
                    <div class="w3-light-grey w3-round">
                      <div class="w3-container w3-<?php 
                        echo $stage == 'Farrowing' ? 'purple' : 
                             ($stage == 'Nursery' ? 'blue' : 
                             ($stage == 'Grower' ? 'teal' : 'green')); 
                      ?> w3-round" style="width:<?php echo $progress; ?>%">
                        <?php echo $stage; ?> (<?php echo round($progress); ?>%)
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="w3-dropdown-click">
                      <button class="w3-button w3-small w3-light-grey w3-round" onclick="toggleDropdown('batch<?php echo $batch->id; ?>')">
                        <i class="fa fa-cog"></i> <i class="fa fa-caret-down"></i>
                      </button>
                      <div id="batch<?php echo $batch->id; ?>" class="w3-dropdown-content w3-bar-block w3-card-4">
                        <a href="view-batch.php?id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-eye"></i> View</a>
                        <a href="edit-batch.php?id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit</a>
                        <a href="medication.php?batch_id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-medkit"></i> Medication</a>
                        <a onclick="return confirm('Mark this batch for sale?')" href="mark-sale.php?id=<?php echo $batch->id; ?>" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Mark for Sale</a>
                        <div class="w3-bar-item w3-pale-red">
                          <a onclick="return confirm('Are you sure you want to delete this batch?')" href="delete.php?id=<?php echo $batch->id; ?>" class="w3-button"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <div class="w3-container w3-padding w3-center">
            <div class="w3-bar">
              <a href="#" class="w3-button">&laquo;</a>
              <a href="#" class="w3-button w3-blue">1</a>
              <a href="#" class="w3-button">2</a>
              <a href="#" class="w3-button">3</a>
              <a href="#" class="w3-button">&raquo;</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="w3-row-padding w3-margin-top">
      <div class="w3-quarter">
        <div class="w3-card w3-blue w3-padding-16">
          <div class="w3-left"><i class="fa fa-paw w3-xxxlarge"></i></div>
          <div class="w3-right">
            <h3>
              <?php 
                $total = $db->query("SELECT SUM(total_pigs) as total FROM pig_batches WHERE status = 'active'")->fetch(PDO::FETCH_OBJ);
                echo $total->total ?: 0;
              ?>
            </h3>
          </div>
          <div class="w3-clear"></div>
          <h4>Active Pigs</h4>
        </div>
      </div>
      
      <div class="w3-quarter">
        <div class="w3-card w3-teal w3-padding-16">
          <div class="w3-left"><i class="fa fa-venus w3-xxxlarge"></i></div>
          <div class="w3-right">
            <h3>
              <?php 
                $females = $db->query("SELECT SUM(female_count) as total FROM pig_batches WHERE status = 'active'")->fetch(PDO::FETCH_OBJ);
                echo $females->total ?: 0;
              ?>
            </h3>
          </div>
          <div class="w3-clear"></div>
          <h4>Female Pigs</h4>
        </div>
      </div>
      
      <div class="w3-quarter">
        <div class="w3-card w3-orange w3-padding-16">
          <div class="w3-left"><i class="fa fa-exclamation-triangle w3-xxxlarge"></i></div>
          <div class="w3-right">
            <h3>
              <?php 
                $quarantined = $db->query("SELECT COUNT(*) as total FROM pig_batches WHERE status = 'quarantined'")->fetch(PDO::FETCH_OBJ);
                echo $quarantined->total ?: 0;
              ?>
            </h3>
          </div>
          <div class="w3-clear"></div>
          <h4>Quarantined</h4>
        </div>
      </div>
      
      <div class="w3-quarter">
        <div class="w3-card w3-green w3-padding-16">
          <div class="w3-left"><i class="fa fa-dollar w3-xxxlarge"></i></div>
          <div class="w3-right">
            <h3>
              <?php 
                $profit = $db->query("SELECT SUM(profit) as total FROM sold_batches")->fetch(PDO::FETCH_OBJ);
                echo '$' . number_format($profit->total ?: 0, 2);
              ?>
            </h3>
          </div>
          <div class="w3-clear"></div>
          <h4>Total Profit</h4>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle dropdown
function toggleDropdown(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
  const input = this.value.toLowerCase();
  const rows = document.querySelectorAll('#batchTable tbody tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(input) ? '' : 'none';
  });
});
</script>

<?php include 'theme/foot.php'; ?>