<?php
include 'setting/system.php';
include 'session.php';

if(isset($_POST['save_med'])) {
    $batch_id = $_POST['batch_id'];
    $name = $_POST['name'];
    $med_type = $_POST['med_type'];
    $dosage = $_POST['dosage'];
    $admin_date = $_POST['admin_date'];
    $next_date = $_POST['next_date'] ?? null;
    $administered_by = $_POST['administered_by'];
    $notes = $_POST['notes'] ?? null;

    try {
        $insert = $db->prepare("INSERT INTO medication 
                              (batch_id, med_type, name, dosage, admin_date, next_date, administered_by, notes) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $insert->execute([
            $batch_id, $med_type, $name, $dosage, $admin_date, $next_date, $administered_by, $notes
        ]);

        $_SESSION['message'] = "Medication record added successfully!";
        $_SESSION['msg_type'] = "success";
        
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
}

header("location: medication.php".($batch_id ? "?batch_id=".$batch_id : ""));
exit();