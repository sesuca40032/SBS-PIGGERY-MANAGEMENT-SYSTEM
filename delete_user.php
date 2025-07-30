<?php
include 'setting/system.php';  // Database connection
include 'session.php';         // Session management

// Check if 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Redirect to the user list after successful deletion
    header("Location: add-users.php");
    exit();
} else {
    // If 'id' is not set, redirect to user list
    header("Location: add-users.php");
    exit();
}
?>
