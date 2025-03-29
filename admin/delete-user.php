<?php
session_start();
require_once '../server/connection.php';

// Admin authentication check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user ID is provided
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Delete user from database
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($delete_query)) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user!";
    }
}

// Redirect back to admin dashboard
header("Location: dashboard.php");
exit();
?>
