<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (isset($_GET['doc_id'])) {
    $doc_id = $_GET['doc_id'];

    $sql = "SELECT file_name, project_id FROM documents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $file_path = "../uploads/documents/" . $row["file_name"];
        unlink($file_path);

        $delete_sql = "DELETE FROM documents WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $doc_id);
        if ($delete_stmt->execute()) {
            header("Location: ../pages/document-management.php?project_id=" . $row['project_id']);
        } else {
            echo "Error deleting record.";
        }
    } else {
        echo "File not found.";
    }
}
?>
