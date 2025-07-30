<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php
$id = $_GET['id'];
$get_record = $db->query("SELECT * FROM sow_gilt_records WHERE id = '$id'");
$record = $get_record->fetch(PDO::FETCH_OBJ);

if (isset($_POST['submit'])) {
    $picture = $_FILES['picture']['name'] ? $_FILES['picture']['name'] : $record->picture;
    $breed_id = $_POST['breed_id'];
    $age = $_POST['age'];
    $mating_date = $_POST['mating_date'];
    $labor_date = $_POST['labor_date'];
    $description = $_POST['description'];

    // Upload picture
    if ($_FILES['picture']['name']) {
        move_uploaded_file($_FILES['picture']['tmp_name'], "uploads/$picture");
    }

    $query = $db->prepare("UPDATE sow_gilt_records SET picture = ?, breed_id = ?, age = ?, mating_date = ?, labor_date = ?, description = ? WHERE id = ?");
    $query->execute([$picture, $breed_id, $age, $mating_date, $labor_date, $description, $id]);

    if ($query) {
        echo "<script>alert('Sow/Gilt record updated successfully');</script>";
        header('refresh:1; url=Pregnancy-and-sow-gilts-record.php');
    } else {
        echo "<script>alert('Failed to update sow/gilt record');</script>";
    }
}
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> Edit Sow/Gilt Record</b></h5>
    </header>

    <div class="w3-container" style="padding-top:22px">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Picture</label>
                <input type="file" name="picture" class="form-control">
                <img src="uploads/<?php echo $record->picture; ?>" width="100" height="100" alt="Picture">
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
                <label>Age</label>
                <input type="number" name="age" class="form-control" value="<?php echo $record->age; ?>" required>
            </div>
            <div class="form-group">
                <label>Mating Date</label>
                <input type="date" name="mating_date" class="form-control" value="<?php echo $record->mating_date; ?>" required>
            </div>
            <div class="form-group">
                <label>Labor Date</label>
                <input type="date" name="labor_date" class="form-control" value="<?php echo $record->labor_date; ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo $record->description; ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Sow/Gilt</button>
        </form>
    </div>
</div>

<?php include 'theme/foot.php'; ?>
