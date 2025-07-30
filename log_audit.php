<?php
// log_audit.php
include 'setting/system.php';
include 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? 0;
    $action = $_POST['action'] ?? 'unknown_action';
    $details = $_POST['details'] ?? 'No details provided';
    $ip_address = $_SERVER['REMOTE_ADDR'] == '::1' ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
    
    $stmt = $db->prepare("INSERT INTO audit_logs 
                         (user_id, action, details, ip_address, log_time) 
                         VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $action, $details, $ip_address]);
    
    echo json_encode(['status' => 'success']);
}
?>