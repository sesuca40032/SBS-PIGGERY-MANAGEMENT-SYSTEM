<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-venus"></i> Add New Sow</b></h5>
  </header>

  <div class="w3-container">
    <div class="w3-card-4 w3-white" style="padding:20px;">
      <form method="post" action="save-sow.php">
        <div class="w3-row-padding">
          <div class="w3-col m6">
            <label>Sow ID</label>
            <input class="w3-input w3-border" type="text" name="sow_id" required 
                   placeholder="SOW-001" pattern="[A-Za-z0-9-]+" title="Alphanumeric and hyphens only">
          </div>
          <div class="w3-col m6">
            <label>Breed</label>
            <select class="w3-select w3-border" name="breed_id" required>
              <option value="">Select Breed</option>
              <?php
              $breeds = $db->query("SELECT * FROM breed");
              while($breed = $breeds->fetch(PDO::FETCH_OBJ)) {
                echo '<option value="'.$breed->id.'">'.$breed->name.'</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="w3-row-padding" style="margin-top:15px;">
          <div class="w3-col m6">
            <label>Birth Date</label>
            <input class="w3-input w3-border" type="date" name="birth_date" required>
          </div>
          <div class="w3-col m6">
            <label>Acquisition Date</label>
            <input class="w3-input w3-border" type="date" name="acquisition_date" required>
          </div>
        </div>

        <div class="w3-row-padding" style="margin-top:15px;">
          <div class="w3-col m6">
            <label>Status</label>
            <select class="w3-select w3-border" name="status" required>
              <option value="active" selected>Active</option>
              <option value="retired">Retired</option>
              <option value="deceased">Deceased</option>
            </select>
          </div>
          <div class="w3-col m6">
            <label>Notes</label>
            <textarea class="w3-input w3-border" name="notes" rows="1"></textarea>
          </div>
        </div>

        <div class="w3-row-padding" style="margin-top:20px;">
          <div class="w3-col m12 w3-center">
            <button type="submit" name="save_sow" class="w3-button w3-blue w3-round">
              <i class="fa fa-save"></i> Save Sow
            </button>
            <a href="sows.php" class="w3-button w3-gray w3-round">
              <i class="fa fa-times"></i> Cancel
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>