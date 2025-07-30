<?php
include 'setting/system.php';
include 'session.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $delete = $db->prepare("DELETE FROM medication WHERE id = ?");
        $delete->execute([$id]);

        $_SESSION['message'] = "Medication record deleted successfully!";
        $_SESSION['msg_type'] = "success";
        
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
}

header("location: medication.php");
exit();