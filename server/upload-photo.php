<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo_name = isset($_POST['photo_name']) ? trim($_POST['photo_name']) : '';
    $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;

    // Debugging: Check if photo name is empty
    if (empty($photo_name)) {
        die("Error: Photo name is empty!");
    }

    // File Upload Handling
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $file_name = basename($_FILES["photo"]["name"]);
        $target_dir = "../uploads/photos/";
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Insert into database
            $sql = "INSERT INTO photos (project_id, photo_name, file_name) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $project_id, $photo_name, $file_name);
            
            if ($stmt->execute()) {
                header("Location: ../pages/document-management.php?project_id=" . $project_id);
                exit();
            } else {
                die("Database error: " . $stmt->error);
            }
        } else {
            die("Error uploading file.");
        }
    } else {
        die("No file uploaded.");
    }
}
?>
