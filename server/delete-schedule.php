<?php
session_start();
include '../server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

// Check if ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $schedule_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the schedule belongs to the logged-in user
    $check_sql = "SELECT id FROM schedules WHERE id = ? AND created_by = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $schedule_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Delete schedule
        $delete_sql = "DELETE FROM schedules WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $schedule_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Schedule not found or unauthorized!";
    }
} else {
    echo "Invalid request!";
}
?>
