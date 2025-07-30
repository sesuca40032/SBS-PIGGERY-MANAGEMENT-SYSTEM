<?php
include 'setting/system.php';

header('Content-Type: application/json');

$batch_id = isset($_GET['batch_id']) ? $_GET['batch_id'] : null;

$query = "SELECT m.id, m.name, m.admin_date as start, m.med_type, 
          CONCAT(m.name, ' (', m.dosage, ') - ', b.batch_id) as title,
          CASE 
            WHEN m.med_type = 'vaccine' THEN 'blue'
            WHEN m.med_type = 'antibiotic' THEN 'red'
            WHEN m.med_type = 'vitamin' THEN 'green'
            ELSE 'orange'
          END as color
          FROM medication m
          LEFT JOIN pig_batches b ON m.batch_id = b.batch_id";

if($batch_id) {
    $query .= " WHERE m.batch_id = :batch_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':batch_id', $batch_id);
} else {
    $stmt = $db->prepare($query);
}

$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($events);