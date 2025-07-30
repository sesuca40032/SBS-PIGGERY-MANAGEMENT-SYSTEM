<?php
include 'setting/system.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper for error handling
function sale_error($msg) {
    $_SESSION['error'] = $msg;
    header("Location: pig_batches.php");
    exit;
}

// Collect and validate POST data
$batch_id          = isset($_POST['batch_id']) ? trim($_POST['batch_id']) : '';
$sale_date         = date('Y-m-d');
$buyer_name        = isset($_POST['buyer_name']) ? trim($_POST['buyer_name']) : '';
$buyer_contact     = isset($_POST['buyer_contact']) ? trim($_POST['buyer_contact']) : '';
$payment_method    = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
$notes             = isset($_POST['notes']) ? trim($_POST['notes']) : '';

$num_pigs          = isset($_POST['num_pigs']) ? intval($_POST['num_pigs']) : 0;
$num_male          = isset($_POST['num_male']) ? intval($_POST['num_male']) : 0;
$num_female        = isset($_POST['num_female']) ? intval($_POST['num_female']) : 0;
$weight_per_head   = isset($_POST['weights']) ? trim($_POST['weights']) : '';
$live_weight_price = isset($_POST['live_weight_price']) ? floatval($_POST['live_weight_price']) : 0;
$feed_sacks        = isset($_POST['feed_sacks']) ? intval($_POST['feed_sacks']) : 0;
$feed_price        = isset($_POST['feed_price']) ? floatval($_POST['feed_price']) : 0;
$medication_price  = isset($_POST['medication_price']) ? floatval($_POST['medication_price']) : 0;
$total_price       = isset($_POST['total_sale_price']) ? floatval($_POST['total_sale_price']) : 0;

// Calculate total cost for the sale
$total_cost = ($feed_sacks * $feed_price) + $medication_price;

// Basic required fields validation
if (!$batch_id || !$buyer_name || !$buyer_contact || !$payment_method || !$num_pigs) {
    sale_error("Please fill out all required fields (Batch, Buyer Name, Buyer Contact, Payment Method, Total pigs to sell).");
}
if ($num_pigs < 1) {
    sale_error("Number of pigs to sell must be at least 1.");
}
if ($num_male < 0 || $num_female < 0) {
    sale_error("Male and Female counts cannot be negative.");
}
if (($num_male + $num_female) !== $num_pigs) {
    sale_error("Total pigs to sell must be equal to the sum of male and female pigs.");
}

// Fetch batch info
$stmt = $db->prepare("SELECT * FROM pig_batches WHERE batch_id = ?");
$stmt->execute([$batch_id]);
$batch = $stmt->fetch(PDO::FETCH_OBJ);

if (!$batch) {
    sale_error("Batch not found!");
}

// Validate sales not exceeding available pigs in batch
if ($num_pigs > $batch->total_pigs) {
    sale_error("Cannot sell more pigs than are present in the batch ({$batch->total_pigs} available).");
}
if ($num_male > $batch->male_count) {
    sale_error("Cannot sell more male pigs than are present ({$batch->male_count} available).");
}
if ($num_female > $batch->female_count) {
    sale_error("Cannot sell more female pigs than are present ({$batch->female_count} available).");
}

// Insert the sale record
$insert = $db->prepare("INSERT INTO sold_batches (
    batch_id, sale_date, buyer_name, buyer_contact, total_price, total_cost, payment_method, notes, 
    num_pigs, num_male, num_female, weight_per_head, live_weight_price, feed_sacks, feed_price, medication_price
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert->execute([
    $batch_id,
    $sale_date,
    $buyer_name,
    $buyer_contact,
    $total_price,
    $total_cost,
    $payment_method,
    $notes,
    $num_pigs,
    $num_male,
    $num_female,
    $weight_per_head,
    $live_weight_price,
    $feed_sacks,
    $feed_price,
    $medication_price
]);

// Deduct pigs from batch
$new_total = $batch->total_pigs - $num_pigs;
$new_male = $batch->male_count - $num_male;
$new_female = $batch->female_count - $num_female;
if ($new_total <= 0) {
    // All sold, mark as sold
    $db->prepare("UPDATE pig_batches SET total_pigs=0, male_count=0, female_count=0, status='sold' WHERE batch_id=?")->execute([$batch_id]);
} else {
    // Partial sale
    $db->prepare("UPDATE pig_batches SET total_pigs=?, male_count=?, female_count=? WHERE batch_id=?")
        ->execute([$new_total, $new_male, $new_female, $batch_id]);
}

$_SESSION['success'] = "Batch sale recorded!";
header("Location: pig_batches.php");
exit;