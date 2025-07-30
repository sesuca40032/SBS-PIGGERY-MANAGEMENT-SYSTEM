<?php
include 'setting/system.php';
header('Content-Type: application/json');
$stmt = $db->query("SELECT * FROM pens");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>  