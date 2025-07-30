<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: feeds_dashboard.php');
    exit;
}
$id = (int)$_GET['id'];

$stmt = $db->prepare("SELECT * FROM supply_inventory WHERE id=?");
$stmt->execute([$id]);
$supply = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supply) {
    echo '<div class="w3-panel w3-red">Supply record not found.</div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $quantity = floatval($_POST['quantity']);
    $unit = trim($_POST['unit']);
    $expiry_date = $_POST['expiry_date'] ?: null;
    $supplier = trim($_POST['supplier']);
    $db->prepare("UPDATE supply_inventory SET name=?, category=?, quantity=?, unit=?, expiry_date=?, supplier=? WHERE id=?")
        ->execute([$name, $category, $quantity, $unit, $expiry_date, $supplier, $id]);
    $_SESSION['success'] = "Supply updated!";
    header('Location: feeds_dashboard.php');
    exit;
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header"><h2><b><i class="fa fa-edit"></i> Edit Supply</b></h2></header>
    <div class="dashboard-card" style="max-width:430px;margin:30px auto;">
        <form method="post">
            <div class="w3-section">
                <label>Name</label>
                <input class="w3-input w3-border" type="text" name="name" value="<?php echo htmlspecialchars($supply['name']); ?>" required>
            </div>
            <div class="w3-section">
                <label>Category</label>
                <input class="w3-input w3-border" type="text" name="category" value="<?php echo htmlspecialchars($supply['category']); ?>">
            </div>
            <div class="w3-section">
                <label>Quantity</label>
                <input class="w3-input w3-border" type="number" name="quantity" min="0" step="0.01" value="<?php echo (float)$supply['quantity']; ?>" required>
            </div>
            <div class="w3-section">
                <label>Unit</label>
                <input class="w3-input w3-border" type="text" name="unit" value="<?php echo htmlspecialchars($supply['unit']); ?>">
            </div>
            <div class="w3-section">
                <label>Expiry Date</label>
                <input class="w3-input w3-border" type="date" name="expiry_date" value="<?php echo htmlspecialchars($supply['expiry_date']); ?>">
            </div>
            <div class="w3-section">
                <label>Supplier</label>
                <input class="w3-input w3-border" type="text" name="supplier" value="<?php echo htmlspecialchars($supply['supplier']); ?>">
            </div>
            <button class="w3-button w3-blue" type="submit"><i class="fa fa-save"></i> Save</button>
            <a href="feeds_dashboard.php" class="w3-button w3-gray"><i class="fa fa-arrow-left"></i> Cancel</a>
        </form>
    </div>
</div>
<?php include 'theme/foot.php'; ?>