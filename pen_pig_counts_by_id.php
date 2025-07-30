<?php
include 'setting/system.php';
header('Content-Type: application/json');

$res = [];
// If you record pig->pen in pig_batches, use this:
$q = $db->query("SELECT pen_id, SUM(pigs_assigned) as pig_count FROM batch_pens GROUP BY pen_id");
foreach($q as $row) $res[$row['pen_id']] = intval($row['pig_count']);
echo json_encode($res);
?>