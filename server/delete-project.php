<?php
include '../server/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $project_id = $_POST["id"];

    // Delete project
    $delete_sql = "DELETE FROM projects WHERE id = $project_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
}
?>
