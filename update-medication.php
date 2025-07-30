<?php
include 'setting/system.php';
include 'session.php';

if(isset($_POST['update_med'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $med_type = $_POST['med_type'];
    $dosage = $_POST['dosage'];
    $admin_date = $_POST['admin_date'];
    $next_date = $_POST['next_date'] ?? null;
    $administered_by = $_POST['administered_by'];
    $notes = $_POST['notes'] ?? null;

    try {
        $update = $db->prepare("UPDATE medication SET 
                              name = ?, med_type = ?, dosage = ?, admin_date = ?, 
                              next_date = ?, administered_by = ?, notes = ?
                              WHERE id = ?");
        
        $update->execute([
            $name, $med_type, $dosage, $admin_date, $next_date, $administered_by, $notes, $id
        ]);

        $_SESSION['message'] = "Medication record updated successfully!";
        $_SESSION['msg_type'] = "success";
        
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
}

header("location: medication.php");
exit();