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
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-edit"></i> Edit Batch - <?php echo $page_title; ?></b></h5>
  </header>

  <div class="w3-container">
    <div class="w3-card-4 w3-white" style="padding:20px;">
      <form method="post" action="update-batch.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $batch->id; ?>">
        <input type="hidden" name="source" value="<?php echo $source; ?>">

        <div class="w3-row-padding">
          <div class="w3-col m6">
            <label>Batch ID</label>
            <input class="w3-input w3-border" type="text" name="batch_id" value="<?php echo $batch->batch_id; ?>" readonly>
          </div>
          <div class="w3-col m6">
            <label>Change Batch Photo (optional)</label>
            <input class="w3-input w3-border" type="file" name="photo" accept="image/*">
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
            <input class="w3-input w3-border" type="number" name="total_pigs" value="<?php echo $batch->total_pigs; ?>" min="1" required>
          </div>
          <div class="w3-col m4">
            <label>Average Weight (kg)</label>
            <input class="w3-input w3-border" type="number" step="0.01" name="weight_avg" value="<?php echo $batch->weight_avg; ?>">
          </div>
        </div>

        <div class="w3-row-padding" style="margin-top:15px;">
          <div class="w3-col m6">
            <label>Male Count</label>
            <input class="w3-input w3-border" type="number" name="male_count" value="<?php echo $batch->male_count; ?>" min="0">
          </div>
          <div class="w3-col m6">
            <label>Female Count</label>
            <input class="w3-input w3-border" type="number" name="female_count" value="<?php echo $batch->female_count; ?>" min="0">
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
        alert('Selected Pen: ' + pen.label);
      });
      area.appendChild(div);
    });
  });
</script>

        <div class="w3-row-padding" style="margin-top:15px;">
          <div class="w3-col m12">
            <label>Notes/Remarks</label>
            <textarea class="w3-input w3-border" name="remark" rows="3"><?php echo $batch->remark; ?></textarea>
          </div>
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
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>
