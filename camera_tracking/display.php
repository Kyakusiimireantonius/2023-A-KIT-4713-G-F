<?php
// display.php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "camera_tracking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tracking_data ORDER BY timestamp DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="mb-3">';
        echo '<img src="' . $row["image_path"] . '" alt="Tracked Image" class="img-fluid mb-2">';
        echo '<p>Tracking Data:</p>';
        echo '<ul>';
        echo '<li>X: ' . $row["x"] . '</li>';
        echo '<li>Y: ' . $row["y"] . '</li>';
        echo '<li>Width: ' . $row["width"] . '</li>';
        echo '<li>Height: ' . $row["height"] . '</li>';
        echo '<li>Timestamp: ' . $row["timestamp"] . '</li>';
        echo '</ul>';
        echo '</div>';
    }
} else {
    echo "<p>No tracking data available.</p>";
}

$conn->close();
?>
