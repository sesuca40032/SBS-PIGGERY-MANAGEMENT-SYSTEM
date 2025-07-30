<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: quarantine_dashboard.php");
    exit;
}
$id = (int)$_GET['id'];

// Get quarantine record
$stmt = $db->prepare("SELECT * FROM quarantine_batches WHERE id=?");
$stmt->execute([$id]);
$q = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$q) {
    echo '<div class="w3-panel w3-red">Quarantine record not found.</div>';
    exit;
}

// Get pens for dropdown/floorplan
$pens = $db->query("SELECT * FROM pens ORDER BY label")->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_quarantined = (int)$_POST['num_quarantined'];
    $num_male = (int)$_POST['num_male'];
    $num_female = (int)$_POST['num_female'];
    $pen_id = (int)$_POST['pen_id'];
    $symptoms = trim($_POST['symptoms']);
    $notes = trim($_POST['notes']);

    $upd = $db->prepare("UPDATE quarantine_batches SET num_quarantined=?, num_male=?, num_female=?, pen_id=?, symptoms=?, notes=? WHERE id=?");
    $upd->execute([$num_quarantined, $num_male, $num_female, $pen_id, $symptoms, $notes, $id]);
    $_SESSION['success'] = "Quarantine record updated!";
    header("Location: quarantine_dashboard.php");
    exit;
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header"><h2><b><i class="fa fa-edit"></i> Edit Quarantine Record</b></h2></header>
    <div class="dashboard-card" style="max-width:560px;margin:30px auto;">
        <form method="post">
            <div class="w3-section">
                <label>Date Quarantined</label>
                <input class="w3-input w3-border" type="text" value="<?php echo htmlspecialchars($q['date_quarantined']); ?>" disabled>
            </div>
            <div class="w3-section">
                <label>Batch ID</label>
                <input class="w3-input w3-border" type="text" value="<?php echo htmlspecialchars($q['batch_id']); ?>" disabled>
            </div>
            <div class="w3-section">
                <label>Number of Pigs</label>
                <input class="w3-input w3-border" type="number" name="num_quarantined" value="<?php echo (int)$q['num_quarantined']; ?>" min="0" required>
            </div>
            <div class="w3-section" style="display: flex; gap: 10px;">
                <div style="flex:1;">
                    <label>Male</label>
                    <input class="w3-input w3-border" type="number" name="num_male" value="<?php echo (int)$q['num_male']; ?>" min="0" required>
                </div>
                <div style="flex:1;">
                    <label>Female</label>
                    <input class="w3-input w3-border" type="number" name="num_female" value="<?php echo (int)$q['num_female']; ?>" min="0" required>
                </div>
            </div>
            <div class="w3-section">
                <label>Pen</label>
                <select class="w3-input w3-border" name="pen_id" required>
                    <option value="">Select pen</option>
                    <?php foreach($pens as $pen): ?>
                        <option value="<?php echo $pen->id; ?>" <?php if($q['pen_id']==$pen->id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($pen->label); ?> (Capacity: <?php echo $pen->capacity; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w3-section">
                <label>Symptoms</label>
                <textarea class="w3-input w3-border" name="symptoms"><?php echo htmlspecialchars($q['symptoms']); ?></textarea>
            </div>
            <div class="w3-section">
                <label>Notes</label>
                <textarea class="w3-input w3-border" name="notes"><?php echo htmlspecialchars($q['notes']); ?></textarea>
            </div>
            <div class="w3-section" style="display:flex;gap:10px;">
                <button class="w3-button w3-blue" type="submit"><i class="fa fa-save"></i> Save</button>
                <a href="quarantine_dashboard.php" class="w3-button w3-gray"><i class="fa fa-arrow-left"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php include 'theme/foot.php'; ?>