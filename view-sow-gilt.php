<?php 
include 'setting/system.php'; 
include 'session.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = $db->prepare("SELECT sg.*, b.name as breed_name FROM sow_gilt_records sg 
                          LEFT JOIN breed b ON sg.breed_id = b.id 
                          WHERE sg.id = ?");
    $query->execute([$id]);
    $data = $query->fetch(PDO::FETCH_OBJ);
    
    if(!$data) {
        echo "Record not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// Calculate age (years, months, days) from birth/acquired date
$from_date = $data->birth_date ?: $data->acquired_date;
if ($from_date) {
    $from = new DateTime($from_date);
    $to = new DateTime();
    $interval = $from->diff($to);
    $age_string = "{$interval->y} years, {$interval->m} months, {$interval->d} days";
    $age_string .= "<br><small>(" . ($data->birth_date ? "Birth: $data->birth_date" : "Acquired: $data->acquired_date") . ")</small>";
} else {
    $age_string = "{$data->age} months (unknown birth/acquired)";
}

// Pregnancy progress
$pregnancy_days = 114;
$progress = 0;
$stage = "Not pregnant";
$mating_date = $data->mating_date;
$is_pregnant = false;
if ($mating_date && $data->status == "active") {
    $mating_dt = new DateTime($mating_date);
    $today = new DateTime();
    $days_since_mating = $mating_dt->diff($today)->days;
    if ($days_since_mating <= $pregnancy_days && $days_since_mating >= 0) {
        $progress = min(100, round(($days_since_mating / $pregnancy_days) * 100));
        if ($days_since_mating < 35) $stage = "Early (Implantation)";
        elseif ($days_since_mating < 70) $stage = "Mid (Fetal Growth)";
        elseif ($days_since_mating < 114) $stage = "Late (Pre-farrow)";
        else $stage = "Due/Overdue";
        $is_pregnant = true;
    }
}

// Status badge color
$status_class = '';
switch($data->status) {
    case 'active': $status_class = 'badge-success'; break;
    case 'inactive': $status_class = 'badge-warning'; break;
    case 'sold': $status_class = 'badge-info'; break;
    case 'deceased': $status_class = 'badge-danger'; break;
    default: $status_class = 'badge-secondary';
}

// Get reproductive history
$events = [];
$event_stmt = $db->prepare("SELECT * FROM sow_gilt_repro_history WHERE sow_gilt_id = ? ORDER BY event_date DESC, id DESC");
$event_stmt->execute([$data->id]);
$events = $event_stmt->fetchAll(PDO::FETCH_OBJ);

?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-paw"></i> Sow/Gilt Details</b></h5>
    </header>

    <div class="w3-container">
        <div class="w3-row">
            <div class="w3-col m4">
                <img width="200" height="200" src="<?php echo $data->picture; ?>" class="img img-responsive thumbnail">
                <br><br>
                <img width="200" height="200" src="qrcodes/sow_gilt_<?php echo $data->id; ?>.png" class="img img-responsive thumbnail">
            </div>
            
            <div class="w3-col m8">
                <table class="w3-table w3-striped w3-bordered">
                    <tr>
                        <th>Sow/Gilt ID</th>
                        <td><?php echo $data->id ?></td>
                    </tr>
                    <tr>
                        <th>Breed</th>
                        <td><?php echo htmlspecialchars($data->breed_name); ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?php echo $age_string ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($data->status); ?></span></td>
                    </tr>
                    <tr>
                        <th>Parity</th>
                        <td><?php echo (int)$data->parity; ?></td>
                    </tr>
                    <tr>
                        <th>Pregnancy Progress</th>
                        <td>
                        <?php if ($is_pregnant) { ?>
                            <div style="width:100px;background:#eee;">
                                <div style="width:<?= $progress ?>%;background:#4caf50;color:#fff;text-align:center;">
                                    <?= $progress ?>%
                                </div>
                            </div>
                            <small><?= $stage ?></small>
                        <?php } else { ?>
                            <span>Not Pregnant</span>
                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mating Date</th>
                        <td><?php echo $data->mating_date ?></td>
                    </tr>
                    <tr>
                        <th>Labor Date</th>
                        <td><?php echo $data->labor_date ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo nl2br(htmlspecialchars($data->description)) ?></td>
                    </tr>
                </table>
                
                <br>
                <a href="edit-sow-gilt.php?id=<?php echo $data->id ?>" class="w3-button w3-blue">Edit</a>
                <a href="log-repro-event.php?id=<?php echo $data->id ?>" class="w3-button w3-green">Log Reproductive Event</a>
                <a href="Pregnancy-and-sow-gilts-record.php" class="w3-button w3-gray">Back to List</a>
            </div>
        </div>

        <div class="w3-row" style="margin-top:30px;">
            <h4>Reproductive History</h4>
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
            <a href="log-repro-event.php?id=<?php echo $data->id ?>" class="btn btn-primary">Log New Reproductive Event</a>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>