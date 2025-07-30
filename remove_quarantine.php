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
    $_SESSION['error'] = "Record not found.";
    header("Location: quarantine_dashboard.php");
    exit;
}

// Get batch info for updating
$batch = $db->prepare("SELECT * FROM pig_batches WHERE batch_id=?");
$batch->execute([$q['batch_id']]);
$batch = $batch->fetch(PDO::FETCH_ASSOC);

// Get pens for dropdown
$pens = $db->query("SELECT * FROM pens ORDER BY label")->fetchAll(PDO::FETCH_OBJ);

// Handle post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $move_pigs = (int)$_POST['move_pigs'];
    $move_male = (int)$_POST['move_male'];
    $move_female = (int)$_POST['move_female'];
    $normal_pen_id = (int)$_POST['normal_pen_id'];
    $notes = trim($_POST['notes']);

    // Sanity check
    if ($move_pigs < 1 || $move_pigs > $q['num_quarantined'] || $move_male+$move_female != $move_pigs) {
        $_SESSION['error'] = "Invalid number of pigs selected.";
        header("Location: remove_quarantine.php?id=".$id);
        exit;
    }

    // Decrement quarantine
    $upd = $db->prepare("UPDATE quarantine_batches SET num_quarantined=num_quarantined-?, num_male=num_male-?, num_female=num_female-?, notes=CONCAT(notes, '\nMoved $move_pigs pigs to normal pen ".intval($normal_pen_id)." on ".date('Y-m-d').".') WHERE id=?");
    $upd->execute([$move_pigs, $move_male, $move_female, $id]);

    // Add/Update batch_pens
    // If batch already has record for this pen, increment, else insert
    $bpq = $db->prepare("SELECT * FROM batch_pens WHERE batch_id=? AND pen_id=?");
    $bpq->execute([$q['batch_id'], $normal_pen_id]);
    $bp = $bpq->fetch(PDO::FETCH_ASSOC);
    if ($bp) {
        $db->prepare("UPDATE batch_pens SET pigs_assigned=pigs_assigned+? WHERE id=?")->execute([$move_pigs, $bp['id']]);
    } else {
        $db->prepare("INSERT INTO batch_pens (batch_id, pen_id, pigs_assigned) VALUES (?, ?, ?)")->execute([$q['batch_id'], $normal_pen_id, $move_pigs]);
    }

    // Optionally update pig_batches (recovered pigs)
    if ($batch) {
        $db->prepare("UPDATE pig_batches SET total_pigs=total_pigs+?, male_count=male_count+?, female_count=female_count+? WHERE batch_id=?")
            ->execute([$move_pigs, $move_male, $move_female, $q['batch_id']]);
    }

    // If all pigs removed from quarantine, optionally delete row
    $check = $db->prepare("SELECT num_quarantined FROM quarantine_batches WHERE id=?");
    $check->execute([$id]);
    $left = $check->fetchColumn();
    if ($left <= 0) {
        $db->prepare("DELETE FROM quarantine_batches WHERE id=?")->execute([$id]);
    }

    $_SESSION['success'] = "Moved $move_pigs pigs back to normal pen!";
    header("Location: quarantine_dashboard.php");
    exit;
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header"><h2><b><i class="fa fa-arrow-left"></i> Move Pigs out of Quarantine</b></h2></header>
    <div class="dashboard-card" style="max-width:540px;margin:30px auto;">
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
                <label>How many pigs to move out?</label>
                <input class="w3-input w3-border" type="number" name="move_pigs" id="move_pigs" value="<?php echo (int)$q['num_quarantined']; ?>" min="1" max="<?php echo (int)$q['num_quarantined']; ?>" required>
            </div>
            <div class="w3-section" style="display:flex;gap:10px;">
                <div style="flex:1;">
                    <label>Male</label>
                    <input class="w3-input w3-border" type="number" name="move_male" id="move_male" value="<?php echo (int)$q['num_male']; ?>" min="0" max="<?php echo (int)$q['num_male']; ?>" required>
                </div>
                <div style="flex:1;">
                    <label>Female</label>
                    <input class="w3-input w3-border" type="number" name="move_female" id="move_female" value="<?php echo (int)$q['num_female']; ?>" min="0" max="<?php echo (int)$q['num_female']; ?>" required>
                </div>
            </div>
            <div class="w3-section">
                <label>Destination Pen</label>
                <select class="w3-input w3-border" name="normal_pen_id" required>
                    <option value="">Select pen</option>
                    <?php foreach($pens as $pen): ?>
                        <option value="<?php echo $pen->id; ?>">
                            <?php echo htmlspecialchars($pen->label); ?> (Capacity: <?php echo $pen->capacity; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w3-section">
                <label>Notes/Remarks</label>
                <textarea class="w3-input w3-border" name="notes"></textarea>
            </div>
            <div class="w3-section" style="display:flex;gap:10px;">
                <button class="w3-button w3-green" type="submit"><i class="fa fa-arrow-left"></i> Move Out</button>
                <a href="quarantine_dashboard.php" class="w3-button w3-gray"><i class="fa fa-arrow-left"></i> Cancel</a>
            </div>
        </form>
        <div class="w3-panel w3-light-grey" style="margin-top:12px;">
            <b>Remaining in Quarantine after move:</b>
            <ul style="margin:0;">
                <li>Total: <span id="remain_total"><?php echo (int)$q['num_quarantined']; ?></span></li>
                <li>Male: <span id="remain_male"><?php echo (int)$q['num_male']; ?></span></li>
                <li>Female: <span id="remain_female"><?php echo (int)$q['num_female']; ?></span></li>
            </ul>
        </div>
    </div>
</div>
<script>
document.getElementById('move_pigs').addEventListener('input', function() {
    var max = <?php echo (int)$q['num_quarantined']; ?>;
    var val = Math.max(1, Math.min(max, parseInt(this.value) || 1));
    this.value = val;
});
['move_male','move_female','move_pigs'].forEach(function(id){
    document.getElementById(id).addEventListener('input', function(){
        var remain_total = <?php echo (int)$q['num_quarantined']; ?> - (parseInt(document.getElementById('move_pigs').value)||0);
        var remain_male = <?php echo (int)$q['num_male']; ?> - (parseInt(document.getElementById('move_male').value)||0);
        var remain_female = <?php echo (int)$q['num_female']; ?> - (parseInt(document.getElementById('move_female').value)||0);
        document.getElementById('remain_total').innerText = remain_total < 0 ? 0 : remain_total;
        document.getElementById('remain_male').innerText = remain_male < 0 ? 0 : remain_male;
        document.getElementById('remain_female').innerText = remain_female < 0 ? 0 : remain_female;
    });
});
</script>
<?php include 'theme/foot.php'; ?>