_<?php
include 'setting/system.php';
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id'])) {
    $stmt = $db->prepare("DELETE FROM pens WHERE id=?");
    $stmt->execute([$data['id']]);
    echo "Deleted";
}
?>