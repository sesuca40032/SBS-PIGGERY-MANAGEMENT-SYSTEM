<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php
if (isset($_POST['submit'])) {
    $breed_id = $_POST['breed_id'];
    $age = $_POST['age'];
    $mating_date = $_POST['mating_date'];
    $description = $_POST['description'];

    // Validate and handle the picture upload
    $upload_directory = 'uploadfolder/';
    $file_name = uniqid() . '_' . basename($_FILES['picture']['name']); // Add unique ID to filename
    $upload_file = $upload_directory . $file_name;
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    
    // Check if file type is allowed
    if(!in_array($_FILES['picture']['type'], $allowed_types)) {
        echo "<script>alert('Only JPG, PNG, and GIF images are allowed');</script>";
    } 
    // Check if file was uploaded successfully
    elseif (move_uploaded_file($_FILES['picture']['tmp_name'], $upload_file)) {
        $picture = $upload_file; // File path where the image is stored
        
        // Calculate the labor date (114 days from mating date)
        $labor_date = date('Y-m-d', strtotime($mating_date . ' + 114 days'));

        try {
            // Insert the sow/gilt record into the database
            $query = $db->prepare("INSERT INTO sow_gilt_records (breed_id, age, mating_date, labor_date, picture, description) 
                                   VALUES (:breed_id, :age, :mating_date, :labor_date, :picture, :description)");
            $query->bindParam(':breed_id', $breed_id);
            $query->bindParam(':age', $age);
            $query->bindParam(':mating_date', $mating_date);
            $query->bindParam(':labor_date', $labor_date);
            $query->bindParam(':picture', $picture);
            $query->bindParam(':description', $description);

            if ($query->execute()) {
                // Get the inserted sow/gilt ID
                $sow_gilt_id = $db->lastInsertId();

                // Generate QR code after inserting the record
                require_once 'phpqrcode/qrlib.php';  // Include the PHP QR Code library

                // Create qrcodes directory if it doesn't exist
                if (!file_exists('qrcodes')) {
                    mkdir('qrcodes', 0777, true);
                }

                // Path for the QR code image
                $qr_code_file = "qrcodes/sow_gilt_{$sow_gilt_id}.png";

                // Generate complete URL for the QR code
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                $base_url = $protocol . $host . dirname($_SERVER['PHP_SELF']);
                $qr_content = rtrim($base_url, '/') . "/view-sow-gilt.php?id={$sow_gilt_id}";

                // Generate the QR code
                QRcode::png($qr_content, $qr_code_file, QR_ECLEVEL_L, 10);

                // Update the record with the QR code file path
                $update_query = $db->prepare("UPDATE sow_gilt_records SET qr_code = :qr_code WHERE id = :id");
                $update_query->bindParam(':qr_code', $qr_code_file);
                $update_query->bindParam(':id', $sow_gilt_id);
                $update_query->execute();

                echo "<script>alert('Sow/Gilt record added successfully'); window.location.href='Pregnancy-and-sow-gilts-record.php';</script>";
                exit;
            } else {
                // Delete the uploaded image if database insert failed
                unlink($upload_file);
                echo "<script>alert('Failed to add Sow/Gilt record');</script>";
            }
        } catch (PDOException $e) {
            // Delete the uploaded image if there was an error
            unlink($upload_file);
            echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading the picture');</script>";
    }
}
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">  
  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Add Sow/Gilt Record</b></h5>
  </header>

  <?php include 'inc/data.php'; ?>

  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <h2>Add New Sow/Gilt Record</h2>

      <form method="post" enctype="multipart/form-data">
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
          <label for="age">Age (months)</label>
          <input type="number" name="age" class="form-control" placeholder="Enter age in months" min="1" required>
        </div>

        <div class="form-group">
          <label for="mating_date">Mating Date</label>
          <input type="date" name="mating_date" class="form-control" max="<?php echo date('Y-m-d'); ?>" required>
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

        <button type="submit" name="submit" class="btn btn-primary">Add Sow/Gilt</button>
        <a href="Pregnancy-and-sow-gilts-record.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>