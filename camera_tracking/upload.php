<?php
// upload.php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $targetDir = "uploads/";
    $imageName = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Save uploaded image to the uploads directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Generate random tracking data (x, y, width, height)
        $x = rand(10, 100);
        $y = rand(10, 100);
        $width = rand(30, 100);
        $height = rand(30, 100);

        // Save data to database
        $stmt = $conn->prepare("INSERT INTO tracking_data (image_path, x, y, width, height) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiii", $targetFile, $x, $y, $width, $height);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php"); // Redirect to the main page to show the uploaded data
        exit();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>
