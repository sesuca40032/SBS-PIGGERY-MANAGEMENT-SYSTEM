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
$stmt = $db->prepare("SELECT * FROM sow_gilt_records WHERE id = ?");
$stmt->execute([$id]);
$sow = $stmt->fetch(PDO::FETCH_OBJ);

if (!$sow) {
    echo "<div class='alert alert-danger'>Sow/Gilt not found.</div>";
    exit;
}

$event_types = [
    'heat' => 'Sign of Heat',
    'mating' => 'Mating',
    'pregnancy_start' => 'Pregnancy Start',
    'pregnancy_cancel' => 'Cancel Pregnancy',
    'farrowing' => 'Farrowing'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_type = $_POST['event_type'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (!isset($event_types[$event_type])) {
        $error = "Invalid event type.";
    } elseif (!$event_date) {
        $error = "Event date is required.";
    } else {
        // Special logic: if pregnancy_cancel, you may want to clear mating/labor date
        if ($event_type === 'pregnancy_cancel') {
            $update = $db->prepare("UPDATE sow_gilt_records SET mating_date=NULL, labor_date=NULL WHERE id=?");
            $update->execute([$id]);
        }
        // If mating, update mating_date and labor_date
        if ($event_type === 'mating') {
            $update = $db->prepare("UPDATE sow_gilt_records SET mating_date=?, labor_date=? WHERE id=?");
            // Labor date is 114 days after mating
            $labor_date = date('Y-m-d', strtotime($event_date . ' +114 days'));
            $update->execute([$event_date, $labor_date, $id]);
        }
        // If farrowing, increment parity
        if ($event_type === 'farrowing') {
            $db->prepare("UPDATE sow_gilt_records SET parity = parity + 1 WHERE id=?")->execute([$id]);
        }

        $insert = $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes) VALUES (?, ?, ?, ?)");
        $insert->execute([$id, $event_type, $event_date, $notes]);

        $_SESSION['message'] = "Reproductive event logged successfully!";
        header("Location: view-repro-history.php?id=$id");
        exit;
    }
}
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5>
            <b><i class="fa fa-plus"></i> Log Reproductive Event for Sow/Gilt ID: <?php echo htmlspecialchars($id); ?></b>
        </h5>
    </header>
    <div class="w3-container" style="padding-top:22px">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select name="event_type" id="event_type" class="form-control" required>
                    <option value="">-- Select Event --</option>
                    <?php foreach ($event_types as $val => $label): ?>
                        <option value="<?php echo $val; ?>" <?php if (isset($_POST['event_type']) && $_POST['event_type'] == $val) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" name="event_date" id="event_date" class="form-control" max="<?php echo date('Y-m-d'); ?>" required value="<?php echo isset($_POST['event_date']) ? htmlspecialchars($_POST['event_date']) : date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label for="notes">Notes (optional)</label>
                <input type="text" name="notes" id="notes" class="form-control" maxlength="255" value="<?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Log Event</button>
            <a href="view-repro-history.php?id=<?php echo $id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'theme/foot.php'; ?>