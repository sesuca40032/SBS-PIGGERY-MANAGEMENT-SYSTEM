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
$sow_stmt = $db->prepare("SELECT * FROM sow_gilt_records WHERE id = ?");
$sow_stmt->execute([$id]);
$sow = $sow_stmt->fetch(PDO::FETCH_OBJ);

if (!$sow) {
    echo "<div class='alert alert-danger'>Sow/Gilt not found.</div>";
    exit;
}

// Fetch repro history
$query = $db->prepare("SELECT * FROM sow_gilt_repro_history WHERE sow_gilt_id = ? ORDER BY event_date DESC, id DESC");
$query->execute([$id]);
$events = $query->fetchAll(PDO::FETCH_OBJ);
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-history"></i> Reproductive History for Sow/Gilt ID: <?php echo htmlspecialchars($id); ?></b></h5>
        <div>
            <strong>Breed:</strong> <?php
                $breed_name = '';
                if ($sow->breed_id) {
                    $br = $db->prepare("SELECT name FROM breed WHERE id=?");
                    $br->execute([$sow->breed_id]);
                    $breed = $br->fetch(PDO::FETCH_OBJ);
                    if ($breed) $breed_name = $breed->name;
                }
                echo htmlspecialchars($breed_name);
            ?><br>
            <strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($sow->status)); ?><br>
            <strong>Parity:</strong> <?php echo (int)$sow->parity; ?>
        </div>
    </header>
    <div class="w3-container" style="padding-top:22px">
        <div class="w3-row">
            <h3>Event Log</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event Type</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($events)): ?>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event->event_date); ?></td>
                        <td><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($event->event_type))); ?></td>
                        <td><?php echo htmlspecialchars($event->notes); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">No reproductive events recorded.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <a href="Pregnancy-and-sow-gilts-record.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>