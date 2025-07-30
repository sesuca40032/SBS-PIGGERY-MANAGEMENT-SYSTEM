<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

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
.dashboard-title h2 {
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin-bottom: 0;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
  max-width: 540px;
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
.form-control {
  border-radius: 7px !important;
  border: 1px solid #b4c7e7 !important;
  box-shadow: 0 1px 4px -2px #38598b18 !important;
  font-size: 1.12rem !important;
  padding: 10px 14px !important;
}
img.sow-photo {
  width: 100px;
  height: 100px;
  object-fit: cover;
  margin-top: 8px;
  border: 2px solid #dedede;
  background: #f0f0f0;
  border-radius: 8px;
}
@media (max-width: 768px) {
  .dashboard-main {
    margin-left: 0;
    padding: 0 0 10px 0;
  }
  .dashboard-header {
    padding: 21px 8px 14px 8px;
  }
  .dashboard-card {
    margin: 16px 0;
    padding: 18px 8px 10px 8px;
  }
}
</style>

<?php
$id = intval($_GET['id']);
$get_record = $db->prepare("SELECT * FROM sow_gilt_records WHERE id = ?");
$get_record->execute([$id]);
$record = $get_record->fetch(PDO::FETCH_OBJ);

if (!$record) {
    echo "<div class='alert alert-danger'>Sow/Gilt record not found.</div>";
    exit;
}

if (isset($_POST['submit'])) {
    $breed_id = $_POST['breed_id'];
    $birth_date = $_POST['birth_date'] ?? null;
    $acquired_date = $_POST['acquired_date'] ?? null;
    $type = $_POST['type'] ?? 'gilt';
    $mating_date = $_POST['mating_date'] ?? null;
    $labor_date = $_POST['labor_date'] ?? null;
    $description = $_POST['description'];
    $picture = $record->picture;

    // Handle picture upload
    if (isset($_FILES['picture']['name']) && $_FILES['picture']['name']) {
        $upload_directory = 'uploadfolder/';
        $file_name = uniqid() . '_' . basename($_FILES['picture']['name']);
        $upload_file = $upload_directory . $file_name;
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($_FILES['picture']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $upload_file)) {
                $picture = $upload_file;
            } else {
                echo "<script>alert('Failed to upload picture.');</script>";
            }
        } else {
            echo "<script>alert('Only JPG, PNG, and GIF images are allowed');</script>";
        }
    }

    // Calculate age in months from birth_date or acquired_date
    $age = null;
    if ($birth_date || $acquired_date) {
        $from = new DateTime($birth_date ?: $acquired_date);
        $to = new DateTime();
        $interval = $from->diff($to);
        $age = ($interval->y * 12) + $interval->m;
    }

    // If type is gilt, remove mating/labor dates
    if ($type == "gilt") {
        $mating_date = null;
        $labor_date = null;
    }

    $query = $db->prepare("UPDATE sow_gilt_records 
        SET picture = :picture, 
            breed_id = :breed_id, 
            birth_date = :birth_date, 
            acquired_date = :acquired_date, 
            age = :age, 
            mating_date = :mating_date, 
            labor_date = :labor_date, 
            description = :description,
            type = :type
        WHERE id = :id");
    $result = $query->execute([
        ':picture' => $picture,
        ':breed_id' => $breed_id,
        ':birth_date' => $birth_date,
        ':acquired_date' => $acquired_date,
        ':age' => $age,
        ':mating_date' => $mating_date,
        ':labor_date' => $labor_date,
        ':description' => $description,
        ':type' => $type,
        ':id' => $id
    ]);

    if ($result) {
        echo "<script>alert('Sow/Gilt record updated successfully');</script>";
        header('refresh:1; url=Pregnancy-and-sow-gilts-record.php');
        exit;
    } else {
        echo "<script>alert('Failed to update sow/gilt record');</script>";
    }
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header">
        <div class="dashboard-title">
            <h2><b><i class="fa fa-dashboard"></i> Edit Sow/Gilt Record</b></h2>
        </div>
    </header>

    <div class="dashboard-card" style="margin:38px 38px 0 38px;">
        <form method="POST" enctype="multipart/form-data" id="editSowGiltForm">
            <div class="form-group">
                <label>Type</label>
                <select name="type" id="typeSelect" class="form-control" required>
                    <option value="gilt" <?= $record->type == 'gilt' ? 'selected' : '' ?>>Gilt</option>
                    <option value="sow"  <?= $record->type == 'sow'  ? 'selected' : '' ?>>Sow</option>
                </select>
            </div>
            <div class="form-group">
                <label>Picture</label>
                <input type="file" name="picture" class="form-control">
                <?php if ($record->picture): ?>
                    <img src="<?php echo $record->picture; ?>" alt="Picture" class="sow-photo">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Breed</label>
                <select name="breed_id" class="form-control" required>
                    <?php
                    $get_breeds = $db->query("SELECT * FROM breed");
                    while ($breed = $get_breeds->fetch(PDO::FETCH_OBJ)) {
                        $selected = $breed->id == $record->breed_id ? 'selected' : '';
                        echo "<option value='{$breed->id}' $selected>{$breed->name}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Birth Date</label>
                <input type="date" name="birth_date" class="form-control" value="<?php echo htmlspecialchars($record->birth_date); ?>">
            </div>
            <div class="form-group">
                <label>Acquired Date</label>
                <input type="date" name="acquired_date" class="form-control" value="<?php echo htmlspecialchars($record->acquired_date); ?>">
            </div>
            <div class="form-group" id="matingDateGroup">
                <label>Mating Date</label>
                <input type="date" name="mating_date" class="form-control" value="<?php echo htmlspecialchars($record->mating_date); ?>">
            </div>
            <div class="form-group" id="laborDateGroup">
                <label>Labor Date</label>
                <input type="date" name="labor_date" class="form-control" value="<?php echo htmlspecialchars($record->labor_date); ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($record->description); ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Sow/Gilt</button>
        </form>
    </div>
</div>

<script>
  // Show/hide mating and labor date based on type
  function toggleFields() {
    var type = document.getElementById('typeSelect').value;
    var matingGroup = document.getElementById('matingDateGroup');
    var laborGroup = document.getElementById('laborDateGroup');
    if (type === "gilt") {
      matingGroup.style.display = "none";
      laborGroup.style.display = "none";
      matingGroup.querySelector('input').value = "";
      laborGroup.querySelector('input').value = "";
    } else {
      matingGroup.style.display = "block";
      laborGroup.style.display = "block";
    }
  }
  document.getElementById('typeSelect').addEventListener('change', toggleFields);
  // Initial load
  toggleFields();
</script>

<?php include 'theme/foot.php'; ?>