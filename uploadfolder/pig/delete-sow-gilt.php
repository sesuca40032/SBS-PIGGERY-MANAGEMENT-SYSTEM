<?php 
include 'setting/system.php'; 
include 'session.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Check if the record exists
    $get_record = $db->prepare("SELECT * FROM sow_gilt_records WHERE id = ?");
    $get_record->execute([$id]);

    if ($get_record->rowCount() > 0) {
        // Delete the record
        $query = $db->prepare("DELETE FROM sow_gilt_records WHERE id = ?");
        if ($query->execute([$id])) {
            $_SESSION['message'] = "Sow/Gilt record deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete the sow/gilt record!";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Record not found!";
        $_SESSION['message_type'] = "error";
    }
} else {
    $_SESSION['message'] = "Invalid request!";
    $_SESSION['message_type'] = "error";
}

// Redirect to the records page
header("Location: Pregnancy-and-sow-gilts-record.php");
exit();
?>
