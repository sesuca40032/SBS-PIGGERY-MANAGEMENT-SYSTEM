<?php
include 'setting/system.php';
session_start();

$batch_id = $_POST['batch_id'];
$is_full_batch = isset($_POST['is_full_batch']) ? intval($_POST['is_full_batch']) : 0;
$num_quarantined = $is_full_batch ? null : intval($a_POST['num_quarantined']);
$num_male = $is_full_batch ? null : intval($_POST['num_male']);
$num_female = $is_full_batch ? null : intval($_POST['num_female']);
$pen_id = intval($_POST['pen_id']);
$symptoms = trim($_POST['symptoms']);
$notes = trim($_POST['notes']);
$date_quarantined = date('Y-m-d');

// Get batch
$stmt = $db->prepare("SELECT * FROM pig_batches WHERE batch_id=?");
$stmt->execute([$batch_id]);
$batch = $stmt->fetch(PDO::FETCH_OBJ);
if (!$batch) { $_SESSION['error'] = "Batch not found."; header("Location: pig_batches.php"); exit; }

if ($is_full_batch) {
  $num_quarantined = $batch->total_pigs;
  $num_male = $batch->male_count;
  $num_female = $batch->female_count;
}

// Insert into quarantine_batches
$ins = $db->prepare("INSERT INTO quarantine_batches
  (batch_id, date_quarantined, num_quarantined, num_male, num_female, symptoms, notes, pen_id, is_full_batch, reported_by)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$ins->execute([
  $batch_id, $date_quarantined, $num_quarantined, $num_male, $num_female, $symptoms, $notes, $pen_id, $is_full_batch, $_SESSION['username'] ?? null
]);

// Update batch numbers/status
if ($is_full_batch) {
  $db->prepare("UPDATE pig_batches SET total_pigs=0, male_count=0, female_count=0, status='quarantined' WHERE batch_id=?")
    ->execute([$batch_id]);
} else {
  $db->prepare("UPDATE pig_batches SET total_pigs=total_pigs-?, male_count=male_count-?, female_count=female_count-? WHERE batch_id=?")
    ->execute([$num_quarantined, $num_male, $num_female, $batch_id]);
}

$_SESSION['success'] = "Batch moved to quarantine!";
header("Location: pig_batches.php");
exit;