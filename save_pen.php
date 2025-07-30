<?php
include 'setting/system.php';
$data = json_decode(file_get_contents("php://input"), true);
foreach ($data['pens'] as $pen) {
    if (isset($pen['id'])) {
        // Update pen
        $stmt = $db->prepare("UPDATE pens SET x=?, y=?, width=?, height=?, label=?, length_m=?, width_m=?, area_sqm=?, capacity=? WHERE id=?");
        $stmt->execute([$pen['x'],$pen['y'],$pen['width'],$pen['height'],$pen['label'],$pen['length'],$pen['widthM'],$pen['area'],$pen['capacity'],$pen['id']]);
    } else {
        // Insert new pen
        $stmt = $db->prepare("INSERT INTO pens (x, y, width, height, label, length_m, width_m, area_sqm, capacity) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$pen['x'],$pen['y'],$pen['width'],$pen['height'],$pen['label'],$pen['length'],$pen['widthM'],$pen['area'],$pen['capacity']]);
    }
}
echo "OK";
?>