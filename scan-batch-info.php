<?php
include 'setting/system.php';

$data = isset($_GET['data']) ? $_GET['data'] : '';
$batch_id = null;

// Try extracting batch_id from query string or URL
parse_str(parse_url($data, PHP_URL_QUERY), $params);
if (isset($params['batch_id'])) {
    $batch_id = $params['batch_id'];
} else {
    if (preg_match('/^BATCH-\d+$/', $data)) {
        $batch_id = $data;
    } elseif (preg_match('/batch_id=([A-Za-z0-9\-_]+)/', $data, $m)) {
        $batch_id = $m[1];
    }
}

if ($batch_id) {
    $stmt = $db->prepare("SELECT pb.batch_id, b.name as breed, bp.pen_id, p.label as pen_label
        FROM pig_batches pb
        LEFT JOIN breed b ON pb.breed_id = b.id
        LEFT JOIN batch_pens bp ON pb.batch_id = bp.batch_id
        LEFT JOIN pens p ON bp.pen_id = p.id
        WHERE pb.batch_id = ? LIMIT 1");
    $stmt->execute([$batch_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $location = $result['pen_label'] ? $result['pen_label'] : "No pen assigned";
        echo json_encode([
            'status' => 'ok',
            'batch_id' => $result['batch_id'],
            'breed' => $result['breed'],
            'location' => $location
        ]);
        exit;
    }
}

echo json_encode(['status' => 'notfound']);
exit;
?>