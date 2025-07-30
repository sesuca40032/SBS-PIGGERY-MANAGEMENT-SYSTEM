<?php
include 'setting/system.php'; // this should include $db (PDO instance)

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['pens'])) {
    echo json_encode(['status' => 'error', 'message' => 'No pens data received']);
    exit;
}

try {
    $stmt = $db->prepare("INSERT INTO pens (x, y, width, height, label) VALUES (:x, :y, :width, :height, :label)");

    foreach ($data['pens'] as $pen) {
        $stmt->execute([
            ':x' => $pen['x'],
            ':y' => $pen['y'],
            ':width' => $pen['width'],
            ':height' => $pen['height'],
            ':label' => $pen['label']
        ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Pens saved']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
