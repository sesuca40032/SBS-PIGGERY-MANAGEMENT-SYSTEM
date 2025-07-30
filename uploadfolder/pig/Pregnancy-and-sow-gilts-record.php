    <?php include 'setting/system.php'; ?>
    <?php include 'theme/head.php'; ?>
    <?php include 'theme/sidebar.php'; ?>
    <?php include 'session.php'; ?>
    <?php
  
    if (isset($_SESSION['message'])) {
        echo "<script>alert('{$_SESSION['message']}');</script>";
        unset($_SESSION['message']); // Remove message after displaying
    }
    ?>
    <!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> Pregnancy and Sow/Gilts Record</b></h5>
    </header>

    <?php include 'inc/data.php'; ?>

    <div class="w3-container" style="padding-top:22px">
        <div class="w3-row">
            <h2>Manage Sow/Gilts Records</h2>
            <a href="add-sow-gilt.php" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Sow/Gilt Record</a><br><br>
            
            <button id="scan-qr-btn" class="btn btn-success">📷 Scan QR Code</button>
            <div id="qr-reader" style="width: 300px;"></div>

            <div class="table-responsive">
                <table class="table table-hover table-striped" id="table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Sow/Gilt ID</th>
                            <th>Breed</th>
                            <th>Age</th>
                            <th>Status</th>
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
                                $current_date = new DateTime();
                                $labor_date = new DateTime($data->labor_date);
                                $interval = $current_date->diff($labor_date);
                                $gestation_days = $interval->days;

                                $notification = "";
                                if ($gestation_days == 80) {
                                    $notification = "Reminder: Give medicine/vaccine (B-complex).";
                                } elseif ($gestation_days == 90) {
                                    $notification = "Reminder: Give medicine/vaccine.";
                                } elseif ($gestation_days == 100) {
                                    $notification = "Reminder: Give medicine/vaccine.";
                                }
                                
                                // Status badge colors
                                $status_class = '';
                                switch($data->status) {
                                    case 'active': $status_class = 'badge-success'; break;
                                    case 'inactive': $status_class = 'badge-warning'; break;
                                    case 'sold': $status_class = 'badge-info'; break;
                                    case 'deceased': $status_class = 'badge-danger'; break;
                                    default: $status_class = 'badge-secondary';
                                }
                        ?>
                            <tr>
                                <td>
                                    <img width="70" height="70" src="<?php echo $data->picture; ?>" class="img img-responsive thumbnail">
                                    <br>
                                    <a href="view-sow-gilt.php?id=<?php echo $data->id ?>">
                                        <img width="100" height="100" src="qrcodes/sow_gilt_<?php echo $data->id; ?>.png" class="img img-responsive thumbnail">
                                    </a>
                                </td>
                                <td><?php echo $data->id ?></td>
                                <td><?php echo $breed->name ?></td>
                                <td><?php echo $data->age ?></td>
                                <td><span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($data->status); ?></span></td>
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
</div>

<?php include 'theme/foot.php'; ?>

<!-- Add the script here -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.getElementById("scan-qr-btn").addEventListener("click", function () {
        const qrReader = new Html5QrcodeScanner("qr-reader", {
            fps: 10,
            qrbox: 250
        });

        qrReader.render(
            (decodedText) => {
                // Check if the decoded text is a URL
                if(decodedText.startsWith('http')) {
                    window.location.href = decodedText;
                } else if(decodedText.includes('id=')) {
                    // Handle old format if needed
                    window.location.href = 'view-sow-gilt.php?' + decodedText;
                } else {
                    alert("Invalid QR code format. Please scan a valid Sow/Gilt QR code.");
                }
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