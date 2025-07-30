<?php
include 'setting/system.php'; // this must contain the $db (PDO) connection

header('Content-Type: application/json');

try {
    $stmt = $db->query("SELECT * FROM pens");
    $pens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pens);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
