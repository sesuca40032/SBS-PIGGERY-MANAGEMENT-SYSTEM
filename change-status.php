<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo "<div class='alert alert-danger'>Invalid Sow/Gilt ID.</div>";
    exit;
}

// Fetch sow/gilt info
$stmt = $db->prepare("SELECT * FROM sow_gilt_records WHERE id=?");
$stmt->execute([$id]);
$sow = $stmt->fetch(PDO::FETCH_OBJ);

if (!$sow) {
    echo "<div class='alert alert-danger'>Record not found.</div>";
    exit;
}

$status_options = [
    'active' => 'Active',
    'inactive' => 'Inactive',
    'sold' => 'Sold',
    'deceased' => 'Deceased'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    if (!isset($status_options[$new_status])) {
        echo "<div class='alert alert-danger'>Invalid status selected.</div>";
    } else {
        $update = $db->prepare("UPDATE sow_gilt_records SET status=? WHERE id=?");
        $update->execute([$new_status, $id]);
        // Optionally log to history
        $log = $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes)
                             VALUES (?, 'status_change', CURDATE(), ?)");
        $note = "Status changed to $new_status";
        $log->execute([$id, $note]);
        $_SESSION['message'] = "Status updated successfully!";
        header("Location: Pregnancy-and-sow-gilts-record.php");
        exit;
    }
}
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-exchange-alt"></i> Change Status for Sow/Gilt ID: <?php echo htmlspecialchars($id); ?></b></h5>
    </header>
    <div class="w3-container" style="padding-top:22px">
        <form method="post">
            <div class="form-group">
                <label for="status">Select New Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <?php
                    foreach ($status_options as $val => $label) {
                        $selected = ($val === $sow->status) ? "selected" : "";
                        echo "<option value=\"$val\" $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Change Status</button>
            <a href="Pregnancy-and-sow-gilts-record.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'theme/foot.php'; ?>