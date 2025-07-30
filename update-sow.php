<?php
include 'setting/system.php';
include 'session.php';

if(isset($_POST['update_sow'])) {
    $id = $_POST['id'];
    $breed_id = $_POST['breed_id'];
    $birth_date = $_POST['birth_date'];
    $acquisition_date = $_POST['acquisition_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'] ?? null;

    try {
        $update = $db->prepare("UPDATE sow SET 
                              breed_id = ?, birth_date = ?, acquisition_date = ?, 
                              status = ?, notes = ? 
                              WHERE id = ?");
        
        $update->execute([
            $breed_id, $birth_date, $acquisition_date, $status, $notes, $id
        ]);

        $_SESSION['message'] = "Sow updated successfully!";
        $_SESSION['msg_type'] = "success";
        header("location: sows.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("location: edit-sow.php?id=$id");
        exit();
    }
} else {
    header("location: sows.php");
    exit();
}