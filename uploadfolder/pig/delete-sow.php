<?php
include 'setting/system.php';
include 'session.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // First check if this sow has any batches
        $check = $db->prepare("SELECT id FROM pig_batches WHERE sow_id = (SELECT sow_id FROM sow WHERE id = ?)");
        $check->execute([$id]);
        
        if($check->rowCount() > 0) {
            $_SESSION['message'] = "Cannot delete: This sow has existing batches!";
            $_SESSION['msg_type'] = "danger";
            header("location: sows.php");
            exit();
        }

        // If no batches, proceed with deletion
        $delete = $db->prepare("DELETE FROM sow WHERE id = ?");
        $delete->execute([$id]);

        $_SESSION['message'] = "Sow deleted successfully!";
        $_SESSION['msg_type'] = "success";
        
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
}

header("location: sows.php");
exit();