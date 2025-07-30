<?php
// Example simulation (use real serial read here)
$data = [
    "temperature" => rand(20, 35),
    "humidity" => rand(40, 70),
    "time" => date("H:i:s")
];

// Output JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
