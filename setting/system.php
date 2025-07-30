
<?php 
include 'db.php';

function audit_log($user_id, $action, $details = '') {
    global $db; // use the $db PDO connection from db.php
    $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, details, log_time) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $action, $details]);
}   
//System Settings
define('NAME_', 'SESUCA FARM');
define('NAME_X', 'PIGGERY MANAGEMENT SYSTEM');


ob_start();
session_start();