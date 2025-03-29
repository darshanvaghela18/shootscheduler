<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $document_name = isset($_POST['document_name']) ? trim($_POST['document_name']) : '';
    $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;

    if (empty($document_name)) {
        die("Error: Document name cannot be empty!");
    }

    if (isset($_FILES["document"]) && $_FILES["document"]["error"] == 0) {
        $file_ext = pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION);
        $unique_file_name = time() . "_" . rand(1000, 9999) . "." . $file_ext;

        $target_dir = "../uploads/documents/";
        $target_file = $target_dir . $unique_file_name;

        // Ensure the directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create folder if not exists
        }

        if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
            // Insert into Database
            $sql = "INSERT INTO documents (project_id, document_name, file_name) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $project_id, $document_name, $unique_file_name);

            if ($stmt->execute()) {
                header("Location: ../pages/document-management.php?project_id=" . $project_id);
                exit();
            } else {
                die("Database error: " . $stmt->error);
            }
        } else {
            die("Error uploading file: " . $_FILES["document"]["error"]);
        }
    } else {
        die("No file uploaded or file upload error: " . $_FILES["document"]["error"]);
    }
}
?>
