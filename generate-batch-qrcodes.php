<?php
include 'setting/system.php';
include 'lib/qrlib.php'; // Point to your qrlib.php

$qrDir = __DIR__ . '/qrcodes';
if (!is_dir($qrDir)) mkdir($qrDir, 0777, true);

$all_batches = $db->query("SELECT id, batch_id FROM pig_batches");
foreach ($all_batches->fetchAll(PDO::FETCH_OBJ) as $batch) {
    $qrFile = $qrDir . "/batch_{$batch->id}.png";
    $qrData = "batch_id={$batch->batch_id}";
    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);
}
echo "Done!";
?>