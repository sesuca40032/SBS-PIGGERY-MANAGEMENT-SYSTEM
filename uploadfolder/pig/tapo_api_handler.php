<?php
require_once 'tapo_api.php';
require_once 'setting/system.php'; // For database connection if needed

header('Content-Type: application/json');

// Initialize Tapo API with your credentials
$tapo = new TapoAPI('your_tapo_username', 'your_tapo_password');

$action = $_GET['action'] ?? '';
$deviceType = $_GET['deviceType'] ?? '';

// In a real app, you'd get these from a database
$deviceIds = [
    'plug' => 'YOUR_PLUG_DEVICE_ID',
    'bulb' => 'YOUR_BULB_DEVICE_ID',
    'camera' => 'YOUR_CAMERA_DEVICE_ID'
];

try {
    switch ($action) {
        case 'getStatus':
            if (!array_key_exists($deviceType, $deviceIds)) {
                throw new Exception('Invalid device type');
            }
            
            $status = $tapo->getDeviceStatus($deviceIds[$deviceType]);
            
            // For camera, also get the stream URL
            if ($deviceType === 'camera') {
                $status['stream_url'] = $tapo->getCameraFeed($deviceIds[$deviceType]);
            }
            
            echo json_encode([
                'success' => true,
                'on' => $status['device_on'] ?? false,
                'stream_url' => $status['stream_url'] ?? null
            ]);
            break;
            
        case 'toggle':
            if (!array_key_exists($deviceType, $deviceIds)) {
                throw new Exception('Invalid device type');
            }
            
            $result = $tapo->toggleDevice($deviceIds[$deviceType]);
            echo json_encode([
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? ''
            ]);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>