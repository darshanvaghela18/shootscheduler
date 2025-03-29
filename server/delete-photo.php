<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (isset($_GET['photo_id'])) {
    $photo_id = $_GET['photo_id'];

    $sql = "SELECT file_name, project_id FROM photos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $file_path = "../uploads/photos/" . $row["file_name"];
        unlink($file_path);

        $delete_sql = "DELETE FROM photos WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $photo_id);
        if ($delete_stmt->execute()) {
            header("Location: ../pages/document-management.php?project_id=" . $row['project_id']);
        } else {
            echo "Error deleting record.";
        }
    } else {
        echo "Photo not found.";
    }
}
?>
