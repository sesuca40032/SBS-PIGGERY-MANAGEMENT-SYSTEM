<?php
include 'setting/system.php';
include 'session.php';

if(isset($_POST['save_sow'])) {
    $sow_id = trim($_POST['sow_id']);
    $breed_id = $_POST['breed_id'];
    $birth_date = $_POST['birth_date'];
    $acquisition_date = $_POST['acquisition_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'] ?? null;

    try {
        // Check if sow ID already exists
        $check = $db->prepare("SELECT id FROM sow WHERE sow_id = ?");
        $check->execute([$sow_id]);
        
        if($check->rowCount() > 0) {
            $_SESSION['message'] = "Error: Sow ID already exists!";
            $_SESSION['msg_type'] = "danger";
            header("location: add-sow.php");
            exit();
        }

        // Insert new sow
        $insert = $db->prepare("INSERT INTO sow 
                              (sow_id, breed_id, birth_date, acquisition_date, status, notes) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        
        $insert->execute([
            $sow_id, $breed_id, $birth_date, $acquisition_date, $status, $notes
        ]);

        $_SESSION['message'] = "Sow $sow_id added successfully!";
        $_SESSION['msg_type'] = "success";
        header("location: sows.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("location: add-sow.php");
        exit();
    }
} else {
    header("location: sows.php");
    exit();
}