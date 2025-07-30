<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> Sow/Gilt Details</b></h5>
    </header>
    
    <div class="w3-container" style="padding-top:22px">
        <?php
        if (isset($_GET['id'])) {
            $sow_gilt_id = $_GET['id'];

            // Fetch record from the database
            $query = $db->prepare("SELECT s.*, b.name AS breed_name FROM sow_gilt_records s 
                                   JOIN breed b ON s.breed_id = b.id 
                                   WHERE s.id = :id");
            $query->bindParam(':id', $sow_gilt_id);
            $query->execute();
            $record = $query->fetch(PDO::FETCH_OBJ);

            if ($record) {
        ?>
                <h2>Sow/Gilt Information</h2>
                <img src="<?php echo $record->picture; ?>" width="200" class="img-thumbnail"><br><br>
                <strong>Sow/Gilt ID:</strong> <?php echo $record->id; ?><br>
                <strong>Breed:</strong> <?php echo $record->breed_name; ?><br>
                <strong>Age:</strong> <?php echo $record->age; ?> months<br>
                <strong>Mating Date:</strong> <?php echo $record->mating_date; ?><br>
                <strong>Labor Date (Expected):</strong> <?php echo $record->labor_date; ?><br>
                <strong>Description:</strong> <?php echo nl2br($record->description); ?><br><br>
                <a href="index.php" class="btn btn-primary">Back</a>
        <?php
            } else {
                echo "<p style='color:red;'>No record found for this Sow/Gilt ID.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid request. No ID provided.</p>";
        }
        ?>
    </div>
</div>

<?php include 'theme/foot.php'; ?>
