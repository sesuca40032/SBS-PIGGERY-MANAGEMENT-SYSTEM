<?php
session_start();
include 'setting/system.php';

// Prevent caching of logout and any pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Function to get client IP address with IPv4 preference
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if ($ip === '::1') return '127.0.0.1';
    $ip = trim(explode(',', $ip)[0]);
    return $ip;
}

// Save user details before session destruction
$user_id = $_SESSION['id'] ?? 0;
$username = $_SESSION['user'] ?? 'Unknown';
$ip_address = getClientIP();

// Log the logout action with IP address
if ($user_id) {
    $log_action = 'logout';
    $log_details = "User $username logged out from $ip_address";
    $log_status = 'success';
} else {
    $log_action = 'logout_attempt';
    $log_details = "Anonymous session termination from $ip_address";
    $log_status = 'info';
}

// Insert into audit logs
$stmt = $db->prepare("INSERT INTO audit_logs 
                     (user_id, action, details, ip_address, status, log_time) 
                     VALUES (:user_id, :action, :details, :ip_address, :status, NOW())");
$stmt->execute(array(
    ':user_id' => $user_id,
    ':action' => $log_action,
    ':details' => $log_details,
    ':ip_address' => $ip_address,
    ':status' => $log_status
));

// Completely destroy session
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header('Location: index.php');
exit;