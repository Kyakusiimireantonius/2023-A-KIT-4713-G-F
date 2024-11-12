<?php
// fetch_tracking.php
session_start();
require 'config/db.php';

// Fetch the latest tracking data
$stmt = $pdo->prepare("
    SELECT td.*, img.image_path 
    FROM tracking_data td 
    JOIN images img ON td.image_id = img.id 
    ORDER BY td.tracked_at DESC 
    LIMIT 10
");
$stmt->execute();
$trackingData = $stmt->fetchAll();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($trackingData);
?>
