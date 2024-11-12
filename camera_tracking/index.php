<?php
// index.php
session_start();
require 'config/db.php';

// Fetch cameras from the database
$stmt = $pdo->query("SELECT * FROM cameras");
$cameras = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Intelligent Camera Tracking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tracking-box {
            position: relative;
            display: inline-block;
        }
        .tracking-box div {
            position: absolute;
            border: 2px solid red;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Intelligent Camera Tracking System</h1>
        
        <!-- Camera Selection -->
        <div class="card mb-4">
            <div class="card-header">
                Select Camera
            </div>
            <div class="card-body">
                <form id="camera-form">
                    <div class="mb-3">
                        <label for="camera" class="form-label">Choose a Camera:</label>
                        <select class="form-select" id="camera" name="camera" required>
                            <option value="" selected disabled>Select a camera</option>
                            <?php foreach($cameras as $camera): ?>
                                <option value="<?= $camera['id'] ?>"><?= htmlspecialchars($camera['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Load Camera</button>
                </form>
            </div>
        </div>
        
        <!-- Upload Image -->
        <div class="card mb-4">
            <div class="card-header">
                Upload Image
            </div>
            <div class="card-body">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="camera_id" id="camera_id" value="">
                    <div class="mb-3">
                        <label for="image" class="form-label">Select Image:</label>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success">Upload Image</button>
                </form>
            </div>
        </div>
        
        <!-- Display Tracking Data -->
        <div class="card">
            <div class="card-header">
                Tracking Data
            </div>
            <div class="card-body">
                <div id="tracking-data">
                    <p>Select a camera and upload an image to view tracking data.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript for handling camera selection and AJAX -->
    <script>
        document.getElementById('camera-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const cameraId = document.getElementById('camera').value;
            if (cameraId) {
                document.getElementById('camera_id').value = cameraId;
                // Optionally, you can load camera-specific data or images here
                alert('Camera selected: ' + cameraId);
            }
        });

        // Function to fetch tracking data periodically
        function fetchTrackingData() {
            fetch('fetch_tracking.php')
                .then(response => response.json())
                .then(data => {
                    const trackingDiv = document.getElementById('tracking-data');
                    if (data.length > 0) {
                        let html = '<table class="table table-bordered">';
                        html += '<thead><tr><th>Image</th><th>X</th><th>Y</th><th>Width</th><th>Height</th><th>Tracked At</th></tr></thead><tbody>';
                        data.forEach(item => {
                            html += `<tr>
                                        <td><img src="${item.image_path}" alt="Image" width="100"></td>
                                        <td>${item.x}</td>
                                        <td>${item.y}</td>
                                        <td>${item.width}</td>
                                        <td>${item.height}</td>
                                        <td>${item.tracked_at}</td>
                                     </tr>`;
                        });
                        html += '</tbody></table>';
                        trackingDiv.innerHTML = html;
                    } else {
                        trackingDiv.innerHTML = '<p>No tracking data available.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching tracking data:', error);
                });
        }

        // Fetch tracking data every 5 seconds
        setInterval(fetchTrackingData, 5000);
    </script>
</body>
</html>
