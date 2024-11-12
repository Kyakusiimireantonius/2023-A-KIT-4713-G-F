<?php
// display_tracking.php
require 'config/db.php';

// Fetch tracking data (similar to fetch_tracking.php)
$stmt = $pdo->prepare("
    SELECT td.*, img.image_path 
    FROM tracking_data td 
    JOIN images img ON td.image_id = img.id 
    ORDER BY td.tracked_at DESC 
    LIMIT 10
");
$stmt->execute();
$trackingData = $stmt->fetchAll();

// Display tracking data
if (count($trackingData) > 0) {
    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>Image</th><th>X</th><th>Y</th><th>Width</th><th>Height</th><th>Tracked At</th></tr></thead><tbody>';
    foreach ($trackingData as $item) {
        echo '<tr>';
        echo '<td><img src="' . htmlspecialchars($item['image_path']) . '" alt="Image" width="100"></td>';
        echo '<td>' . htmlspecialchars($item['x']) . '</td>';
        echo '<td>' . htmlspecialchars($item['y']) . '</td>';
        echo '<td>' . htmlspecialchars($item['width']) . '</td>';
        echo '<td>' . htmlspecialchars($item['height']) . '</td>';
        echo '<td>' . htmlspecialchars($item['tracked_at']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No tracking data available.</p>';
}
?>
