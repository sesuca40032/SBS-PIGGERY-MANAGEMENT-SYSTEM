<?php
include 'setting/system.php';
include 'session.php';

if(isset($_POST['save_batch'])) {
    // Get form data
    $source = $_POST['source'];
    $batch_id = $_POST['batch_id'];
    $breed_id = $_POST['breed_id'];
    $birth_date = $_POST['birth_date'];
    $total_pigs = $_POST['total_pigs'];
    $male_count = $_POST['male_count'] ?? 0;
    $female_count = $_POST['female_count'] ?? 0;
    $weight_avg = $_POST['weight_avg'] ?? null;
    $location = $_POST['location'] ?? null;
    $remark = $_POST['remark'] ?? null;
    $sow_id = ($source == 'farm') ? $_POST['sow_id'] : null;

    // Handle file upload
    $photo = 'assets/default_batch.jpg';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/batches/";
        if(!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $new_filename = $batch_id . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if($check !== false) {
            if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $photo = $target_file;
            }
        }
    }

    // Insert into database
    try {
        $insert = $db->prepare("INSERT INTO pig_batches (
            batch_id, photo, source, sow_id, breed_id, birth_date, 
            total_pigs, male_count, female_count, weight_avg, location, remark
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $insert->execute([
            $batch_id, $photo, $source, $sow_id, $breed_id, $birth_date,
            $total_pigs, $male_count, $female_count, $weight_avg, $location, $remark
        ]);

        // If external purchase, record financial transaction
        if($source == 'external') {
            $supplier_name = $_POST['supplier_name'] ?? 'Unknown Supplier';
            $cost = $total_pigs * ($weight_avg * 5); // Example cost calculation ($5 per kg)
            
            $financial = $db->prepare("INSERT INTO financial_records (
                record_date, type, category, amount, description, related_batch, recorded_by
            ) VALUES (CURDATE(), 'expense', 'pig_purchase', ?, ?, ?, ?)");
            
            $financial->execute([
                $cost, 
                "Purchase of $total_pigs pigs from $supplier_name",
                $batch_id,
                $_SESSION['username']
            ]);
        }

        $_SESSION['message'] = "Batch $batch_id added successfully!";
        $_SESSION['msg_type'] = "success";
        header("location: pig_batches.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("location: add-batch.php?source=$source");
        exit();
    }
} else {
    header("location: pig_batches.php");
    exit();
}