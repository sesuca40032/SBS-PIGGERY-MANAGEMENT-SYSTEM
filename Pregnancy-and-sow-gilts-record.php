<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<style>
.dashboard-main {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
}
.dashboard-header {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 30px 38px 20px 38px;
  box-shadow: 0 4px 24px -10px #38598b40;
}
.dashboard-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.dashboard-title h2 {
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin-bottom: 0;
}
.dashboard-badge {
  background: #fff;
  color: #38598b;
  font-size: 1.15rem;
  font-weight: 700;
  border-radius: 20px;
  padding: 8px 26px;
  box-shadow: 0 2px 8px -2px #00000018;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
}
.btn-primary, .w3-button.w3-blue {
  background: #38598b !important;
  color: #fff !important;
  font-size: 1.09rem !important;
  font-weight: 600;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b28 !important;
}
.btn-primary:hover, .w3-button.w3-blue:hover {
  background: #2c406b !important;
}
.table thead th, .w3-table th {
  font-weight: 700;
  color: #38598b;
  background: #f3f6fb;
  border-bottom: 2px solid #b4c7e7;
}
.table tr td, .w3-table td {
  font-size: 1.07rem;
  vertical-align: middle;
}
.table-hover tbody tr:hover, .w3-table-hover tr:hover {
  background: #f7f8fa;
}
.badge-success {
  background: #4caf50;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-warning {
  background: #ff9800;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-info {
  background: #2196f3;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-danger {
  background: #d32f2f;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-secondary {
  background: #aaa;
  color: #fff;
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 600;
}
.badge-warning {
  background: #ffeb3b;
  color: #333 !important;
  border: 1px solid #ffd600;
}
.dropdown-menu {
  min-width: 180px;
  border-radius: 12px;
  box-shadow: 0 2px 14px -4px #38598b24;
}
.sow-photo-qrcode-flex {
  display: flex;
  align-items: center;
  gap: 14px;
  justify-content: flex-start;
}
.sow-photo-frame {
  width: 70px;
  height: 70px;
  display: inline-block;
  border: 2px solid #dedede;
  background: #f0f0f0;
  overflow: hidden;
  box-sizing: border-box;
}
.sow-photo-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
}
.sow-qrcode-frame {
  width: 70px;
  height: 70px;
  display: inline-block;
  background: #fff;
  border: 2px solid #dedede;
  box-sizing: border-box;
  overflow: hidden;
}
.sow-qrcode-frame img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 0;
}
@media (max-width: 1100px) {
  .dashboard-charts-row {
    flex-direction: column;
    gap: 18px;
  }
  .dashboard-card {
    min-width: unset;
    max-width: 99vw;
  }
}
@media (max-width: 768px) {
  .dashboard-main {
    margin-left: 0;
    padding: 0 0 10px 0;
  }
  .dashboard-header,
  .dashboard-charts-row {
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 7px;
    padding-right: 7px;
  }
  .dashboard-header {
    padding: 21px 8px 14px 8px;
  }
}
</style>

<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['message']);
}
?>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header">
        <div class="dashboard-row">
            <div class="dashboard-col dashboard-title">
                <h2><b><i class="fa fa-dashboard"></i> Pregnancy and Sow/Gilts Record</b></h2>
            </div>
            <div class="dashboard-col dashboard-date">
                <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
            </div>
        </div>
    </header>

    <div class="dashboard-card" style="margin:38px 38px 0 38px;">
        <div class="dashboard-row" style="margin-bottom:18px;">
            <h3 style="font-weight:600;margin:0;">Manage Sow/Gilts Records</h3>
            <div>
                <a href="add-sow-gilt.php" class="btn btn-primary" style="margin-right:12px;"><i class="fa fa-plus"></i> Add New Sow/Gilt Record</a>
                <button id="scan-qr-btn" class="btn btn-success"><i class="fa fa-qrcode"></i> Scan QR Code</button>
            </div>
        </div>
        <div id="qr-reader" style="width: 300px; margin-bottom:22px;"></div>
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="table">
               <thead>
    <tr>
        <th>Photo / QR</th>
        <th>Sow/Gilt ID</th>
        <th>Type</th> <!-- Add this line -->
        <th>Breed</th>
        <th>Age</th>
        <th>Status</th>
        <th>Parity</th>
        <th>Pregnancy Progress</th>
        <th>Mating Date</th>
        <th>Labor Date</th>
        <th>Description</th>
        <th>Options</th>
    </tr>
</thead>
<tbody>
                    <?php
                    $all_sows_gilts = $db->query("SELECT * FROM sow_gilt_records ORDER BY id DESC");
                    $fetch = $all_sows_gilts->fetchAll(PDO::FETCH_OBJ);

                    foreach ($fetch as $data) {
                        $get_breed = $db->query("SELECT * FROM breed WHERE id = '$data->breed_id'");
                        $breed_result = $get_breed->fetchAll(PDO::FETCH_OBJ);
                        foreach ($breed_result as $breed) {
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

                            $status_class = '';
                            switch($data->status) {
                                case 'active': $status_class = 'badge-success'; break;
                                case 'inactive': $status_class = 'badge-warning'; break;
                                case 'sold': $status_class = 'badge-info'; break;
                                case 'deceased': $status_class = 'badge-danger'; break;
                                default: $status_class = 'badge-secondary';
                            }

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

                            $notification = "";
                            if ($is_pregnant) {
                                if ($days_since_mating == 80) {
                                    $notification = "Reminder: Give medicine/vaccine (B-complex).";
                                } elseif ($days_since_mating == 90) {
                                    $notification = "Reminder: Give medicine/vaccine.";
                                } elseif ($days_since_mating == 100) {
                                    $notification = "Reminder: Give medicine/vaccine.";
                                }
                            }

                            $today_str = date('Y-m-d');
                            if ($data->labor_date && $today_str >= $data->labor_date && $is_pregnant) {
                                $farrow_check = $db->prepare("SELECT COUNT(*) FROM sow_gilt_repro_history WHERE sow_gilt_id=? AND event_type='farrowing' AND event_date=?");
                                $farrow_check->execute([$data->id, $data->labor_date]);
                                $farrowed = $farrow_check->fetchColumn();
                                if (!$farrowed) {
                                    $db->query("UPDATE sow_gilt_records SET parity = parity + 1 WHERE id = {$data->id}");
                                    $db->prepare("INSERT INTO sow_gilt_repro_history (sow_gilt_id, event_type, event_date, notes) VALUES (?, 'farrowing', ?, 'Automatic increment after labor date')")->execute([$data->id, $data->labor_date]);
                                }
                            }
                    ?>
                        <tr>
                            <td>
                              <div class="sow-photo-qrcode-flex">
                                <span class="sow-photo-frame">
                                  <img src="<?php echo $data->picture; ?>" alt="Sow/Gilt Photo">
                                </span>
                                <a href="view-sow-gilt.php?id=<?php echo $data->id ?>" title="View QR">
                                  <span class="sow-qrcode-frame">
                                    <img src="qrcodes/sow_gilt_<?php echo $data->id; ?>.png" alt="QR Code">
                                  </span>
                                </a>
                              </div>
                            </td>
                            <td><?php echo $data->id ?></td>
                            <td><?php echo ucfirst($data->type); ?></td> <!-- Show type -->
                            <td><?php echo $breed->name ?></td>
                            <td><?php echo $age_string ?></td>
                            <td><span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($data->status); ?></span></td>
                            <td><?php echo $data->parity ?></td>
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
                            <td><?php echo $data->mating_date ?></td>
                            <td><?php echo $data->labor_date ?></td>
                            <td><?php echo wordwrap($data->description, 300, '<br>'); ?></td>
                            <td>
                                <?php if ($notification) { ?>
                                    <span class="badge badge-warning"><?php echo $notification; ?></span>
                                <?php } ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Options
                                    <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="edit-sow-gilt.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                                        <li><a href="change-status.php?id=<?php echo $data->id ?>"><i class="fa fa-exchange-alt"></i> Change Status</a></li>
                                        <li><a href="log-repro-event.php?id=<?php echo $data->id ?>"><i class="fa fa-plus"></i> Log Reproductive Event</a></li>
                                        <li><a href="view-repro-history.php?id=<?php echo $data->id ?>"><i class="fa fa-history"></i> View Repro History</a></li>
                                        <li><a onclick="return confirm('Continue delete sow/gilt record?')" href="delete-sow-gilt.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.getElementById("scan-qr-btn").addEventListener("click", function () {
        const qrReader = new Html5QrcodeScanner("qr-reader", {
            fps: 10,
            qrbox: 250
        });

        qrReader.render(
            (decodedText) => {
                if(decodedText.startsWith('http')) {
                    window.location.href = decodedText;
                } else if(decodedText.includes('id=')) {
                    window.location.href = 'view-sow-gilt.php?' + decodedText;
                } else {
                    alert("Invalid QR code format. Please scan a valid Sow/Gilt QR code.");
                }a
                qrReader.clear();
            },
            (errorMessage) => {
                console.warn(errorMessage);
            }
        );
    });
</script>
</body>
</html>