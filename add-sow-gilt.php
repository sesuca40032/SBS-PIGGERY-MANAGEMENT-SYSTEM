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
.form-control {
  border-radius: 7px !important;
  border: 1px solid #b4c7e7 !important;
  box-shadow: 0 1px 4px -2px #38598b18 !important;
  font-size: 1.12rem !important;
  padding: 10px 14px !important;
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
}
</style>

<?php
if (isset($_POST['submit'])) {
    $breed_id = $_POST['breed_id'];
    $birth_date = $_POST['birth_date'] ?? null;
    $acquired_date = $_POST['acquired_date'] ?? null;
    $type = $_POST['type'] ?? 'gilt';
    $mating_date = $_POST['mating_date'] ?? null;
    $description = $_POST['description'];

    // Calculate age in months from birth_date or acquired_date
    $age = null;
    if ($birth_date || $acquired_date) {
        $from = new DateTime($birth_date ?: $acquired_date);
        $to = new DateTime();
        $interval = $from->diff($to);
        $age = ($interval->y * 12) + $interval->m;
    }

    // Validate and handle the picture upload
    $upload_directory = 'uploadfolder/';
    $file_name = uniqid() . '_' . basename($_FILES['picture']['name']);
    $upload_file = $upload_directory . $file_name;
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if(!in_array($_FILES['picture']['type'], $allowed_types)) {
        echo "<script>alert('Only JPG, PNG, and GIF images are allowed');</script>";
    }
    elseif (move_uploaded_file($_FILES['picture']['tmp_name'], $upload_file)) {
        $picture = $upload_file;

        // Calculate the labor date only for sows
        $labor_date = ($type == 'sow' && $mating_date) ? date('Y-m-d', strtotime($mating_date . ' + 114 days')) : null;

        try {
            $query = $db->prepare("INSERT INTO sow_gilt_records (breed_id, birth_date, acquired_date, age, mating_date, labor_date, picture, description, parity, type)
                                   VALUES (:breed_id, :birth_date, :acquired_date, :age, :mating_date, :labor_date, :picture, :description, 0, :type)");
            $query->bindParam(':breed_id', $breed_id);
            $query->bindParam(':birth_date', $birth_date);
            $query->bindParam(':acquired_date', $acquired_date);
            $query->bindParam(':age', $age);
            $query->bindParam(':mating_date', $mating_date);
            $query->bindParam(':labor_date', $labor_date);
            $query->bindParam(':picture', $picture);
            $query->bindParam(':description', $description);
            $query->bindParam(':type', $type);

            if ($query->execute()) {
                $sow_gilt_id = $db->lastInsertId();

                require_once 'phpqrcode/qrlib.php';
                if (!file_exists('qrcodes')) {
                    mkdir('qrcodes', 0777, true);
                }
                $qr_code_file = "qrcodes/sow_gilt_{$sow_gilt_id}.png";
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                $base_url = $protocol . $host . dirname($_SERVER['PHP_SELF']);
                $qr_content = rtrim($base_url, '/') . "/view-sow-gilt.php?id={$sow_gilt_id}";
                QRcode::png($qr_content, $qr_code_file, QR_ECLEVEL_L, 10);

                $update_query = $db->prepare("UPDATE sow_gilt_records SET qr_code = :qr_code WHERE id = :id");
                $update_query->bindParam(':qr_code', $qr_code_file);
                $update_query->bindParam(':id', $sow_gilt_id);
                $update_query->execute();

                // History: Add only events relevant for sows
                $insert_history = $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes) VALUES (:id, 'added', CURDATE(), 'Initial record')");
                $insert_history->bindParam(':id', $sow_gilt_id);
                $insert_history->execute();

                if ($type == 'sow' && $mating_date) {
                    $insert_mating = $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes) VALUES (:id, 'mating', :mating_date, 'First mating recorded on add')");
                    $insert_mating->bindParam(':id', $sow_gilt_id);
                    $insert_mating->bindParam(':mating_date', $mating_date);
                    $insert_mating->execute();

                    $insert_preg = $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes) VALUES (:id, 'pregnancy_start', :preg_date, 'Pregnancy cycle started on add')");
                    $insert_preg->bindParam(':id', $sow_gilt_id);
                    $insert_preg->bindParam(':preg_date', $mating_date);
                    $insert_preg->execute();
                }

                echo "<script>alert('Sow/Gilt record added successfully'); window.location.href='Pregnancy-and-sow-gilts-record.php';</script>";
                exit;
            } else {
                unlink($upload_file);
                echo "<script>alert('Failed to add Sow/Gilt record');</script>";
            }
        } catch (PDOException $e) {
            unlink($upload_file);
            echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading the picture');</script>";
    }
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2><b><i class="fa fa-dashboard"></i> Add Sow/Gilt Record</b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>

  <div class="dashboard-card" style="margin:38px 38px 0 38px;max-width:540px;">
    <h3 style="font-weight:600;margin:0 0 10px 0;">Add New Sow/Gilt Record</h3>
    <form method="post" enctype="multipart/form-data" id="addSowGiltForm">
      <div class="form-group">
        <label for="type">Type</label>
        <select name="type" id="typeSelect" class="form-control" required>
          <option value="gilt">Gilt</option>
          <option value="sow">Sow</option>
        </select>
      </div>
      <div class="form-group">
        <label for="breed_id">Breed</label>
        <select name="breed_id" class="form-control" required>
          <option value="">Select Breed</option>
          <?php
          $query = $db->query("SELECT * FROM breed");
          $breeds = $query->fetchAll(PDO::FETCH_OBJ);
          foreach ($breeds as $breed) {
            echo "<option value='{$breed->id}'>{$breed->name}</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="birth_date">Birth Date</label>
        <input type="date" name="birth_date" class="form-control">
      </div>
      <div class="form-group">
        <label for="acquired_date">Acquired Date</label>
        <input type="date" name="acquired_date" class="form-control">
      </div>
      <div class="form-group" id="matingDateGroup">
        <label for="mating_date">Mating Date</label>
        <input type="date" name="mating_date" class="form-control" max="<?php echo date('Y-m-d'); ?>">
      </div>
      <div class="form-group">
        <label for="picture">Picture</label>
        <input type="file" name="picture" class="form-control" accept="image/*" required>
        <small class="form-text text-muted">Max size: 2MB. Allowed types: JPG, PNG, GIF</small>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control" placeholder="Enter description (health status, special notes, etc.)" rows="4" required></textarea>
      </div>
      <div style="margin-top:22px;">
        <button type="submit" name="submit" class="btn btn-primary">Add Sow/Gilt</button>
        <a href="Pregnancy-and-sow-gilts-record.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  // Show/hide mating date based on type
  document.getElementById('typeSelect').addEventListener('change', function() {
    var matingGroup = document.getElementById('matingDateGroup');
    if(this.value === "gilt") {
      matingGroup.style.display = "none";
      matingGroup.querySelector('input').value = ""; // clear value
    } else {
      matingGroup.style.display = "block";
    }
  });
  // Initial load
  if(document.getElementById('typeSelect').value === "gilt") {
    document.getElementById('matingDateGroup').style.display = "none";
  }
</script>

<?php include 'theme/foot.php'; ?>