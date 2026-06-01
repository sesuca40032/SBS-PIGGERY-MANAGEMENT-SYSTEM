
<?php 
include 'db.php';

function audit_log($user_id, $action, $details = '') {
    global $db; // use the $db PDO connection from db.php
    $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, details, log_time) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $action, $details]);
}   
//System Settings
define('NAME_', 'IHOG SBS');
define('NAME_X', 'SBS PIGGERY MANAGEMENT SYSTEM');


ob_start();
session_start();