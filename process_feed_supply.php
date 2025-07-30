<?php
include 'setting/system.php';
include 'session.php';

$user = $_SESSION['username'] ?? 'Unknown';
$action = $_POST['action'] ?? '';

if ($action === 'add_feed') {
    $name = trim($_POST['feed_name']);
    $type = trim($_POST['feed_type']);
    $qty = floatval($_POST['feed_qty']);
    $expiry = $_POST['feed_expiry'] ?: null;
    $supplier = trim($_POST['feed_supplier']);
    if ($name && $qty > 0) {
        $stmt = $db->prepare("INSERT INTO feed_inventory (name, type, quantity_kg, expiry_date, supplier) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $qty, $expiry, $supplier]);
        $_SESSION['success'] = "Feed added to inventory!";
    }
    header('Location: feeds_dashboard.php');
    exit;
}

if ($action === 'add_supply') {
    $name = trim($_POST['supply_name']);
    $category = trim($_POST['supply_category']);
    $qty = floatval($_POST['supply_qty']);
    $unit = trim($_POST['supply_unit']);
    $expiry = $_POST['supply_expiry'] ?: null;
    $supplier = trim($_POST['supply_supplier']);
    if ($name && $qty > 0) {
        $stmt = $db->prepare("INSERT INTO supply_inventory (name, category, quantity, unit, expiry_date, supplier) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $qty, $unit, $expiry, $supplier]);
        $_SESSION['success'] = "Supply added to inventory!";
    }
    header('Location: feeds_dashboard.php');
    exit;
}

if ($action === 'use_feed') {
    $feed_id = intval($_POST['feed_id']);
    $amount = floatval($_POST['feed_used_qty']);
    $used_for = trim($_POST['feed_used_for']);
    $notes = trim($_POST['feed_used_notes']);
    if ($feed_id && $amount > 0) {
        $db->prepare("INSERT INTO feed_usage (feed_id, amount_kg, usage_date, used_for, notes, user) VALUES (?, ?, CURDATE(), ?, ?, ?)")
            ->execute([$feed_id, $amount, $used_for, $notes, $user]);
        $db->prepare("UPDATE feed_inventory SET quantity_kg = quantity_kg - ? WHERE id=?")->execute([$amount, $feed_id]);
        $_SESSION['success'] = "Feed usage recorded!";
    }
    header('Location: feeds_dashboard.php');
    exit;
}

if ($action === 'use_supply') {
    $supply_id = intval($_POST['supply_id']);
    $amount = floatval($_POST['supply_used_qty']);
    $used_for = trim($_POST['supply_used_for']);
    $notes = trim($_POST['supply_used_notes']);
    if ($supply_id && $amount > 0) {
        $db->prepare("INSERT INTO supply_usage (supply_id, amount, usage_date, used_for, notes, user) VALUES (?, ?, CURDATE(), ?, ?, ?)")
            ->execute([$supply_id, $amount, $used_for, $notes, $user]);
        $db->prepare("UPDATE supply_inventory SET quantity = quantity - ? WHERE id=?")->execute([$amount, $supply_id]);
        $_SESSION['success'] = "Supply usage recorded!";
    }
    header('Location: feeds_dashboard.php');
    exit;
}

$_SESSION['error'] = "Invalid action.";
header('Location: feeds_dashboard.php');
exit;