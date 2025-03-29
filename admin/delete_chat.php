<?php
session_start();
require_once '../server/connection.php'; // Database connection

// Admin authentication check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user_id is provided
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Delete all messages of the user from the messages table
    $stmt = $conn->prepare("DELETE FROM messages WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $success = "User's chat deleted successfully!";
    } else {
        $error = "Failed to delete chat.";
    }
    $stmt->close();
}

// Redirect back to the dashboard after deleting the chat
header("Location: dashboard.php");
exit();
?>
