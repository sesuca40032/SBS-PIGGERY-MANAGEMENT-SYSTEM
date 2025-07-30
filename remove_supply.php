<?php
include 'setting/system.php';
include 'session.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: feeds_dashboard.php');
    exit;
}
$id = (int)$_GET['id'];

$db->prepare("DELETE FROM supply_inventory WHERE id=?")->execute([$id]);
$_SESSION['success'] = "Supply removed from inventory.";
header('Location: feeds_dashboard.php');
exit;