<?php
include 'setting/system.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_batch'])) {
    $id = $_POST['id'];
    $batch_id = $_POST['batch_id'];
    $source = $_POST['source'];
    $birth_date = $_POST['birth_date'];
    $breed_id = $_POST['breed_id'];
    $total_pigs = $_POST['total_pigs'];
    $weight_avg = $_POST['weight_avg'];
    $male_count = $_POST['male_count'];
    $female_count = $_POST['female_count'];
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $remark = $_POST['remark'];
    $photo = $_FILES['photo']['name'];

    // Handle optional fields
    $sow_id = $source == 'farm' ? $_POST['sow_id'] : null;
    $supplier_name = $source == 'external' ? $_POST['supplier_name'] : null;

    // Upload photo if provided
    if (!empty($photo)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

        $photo_sql = ", photo = ?";
        $photo_param = $target_file;
    } else {
        $photo_sql = "";
        $photo_param = null;
    }

    // Build SQL query dynamically
    $sql = "UPDATE pig_batches SET 
                birth_date = ?, 
                breed_id = ?, 
                total_pigs = ?, 
                weight_avg = ?, 
                male_count = ?, 
                female_count = ?, 
                location = ?, 
                remark = ?";

    $params = [$birth_date, $breed_id, $total_pigs, $weight_avg, $male_count, $female_count, $location, $remark];

    if ($source == 'farm') {
        $sql .= ", sow_id = ?";
        $params[] = $sow_id;
    } elseif ($source == 'external') {
        $sql .= ", supplier_name = ?";
        $params[] = $supplier_name;
    }

    if ($photo_param) {
        $sql .= $photo_sql;
        $params[] = $photo_param;
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;

    // Execute the update
    $stmt = $db->prepare($sql);

    $success = $stmt->execute($params);

    // ---- HANDLE PEN ASSIGNMENTS ----
    if ($success && isset($_POST['pen_assignments'])) {
        $pen_assignments = json_decode($_POST['pen_assignments'], true);

        // Remove all previous assignments for this batch
        $db->prepare("DELETE FROM batch_pens WHERE batch_id = ?")->execute([$batch_id]);

        // Insert current assignments
        if (is_array($pen_assignments)) {
            foreach ($pen_assignments as $pen_id => $assigned_count) {
                if ($assigned_count > 0) {
                    $stmt2 = $db->prepare("INSERT INTO batch_pens (batch_id, pen_id, pigs_assigned) VALUES (?, ?, ?)");
                    $stmt2->execute([$batch_id, $pen_id, $assigned_count]);
                }
            }
        }
    }

    if ($success) {
        echo "<script>alert('Batch updated successfully.'); window.location='pig_batches.php';</script>";
    } else {
        echo "<script>alert('Error updating batch.'); window.history.back();</script>";
    }
} else {
    header("Location: pig_batches.php");
    exit;
}
?>