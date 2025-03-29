<?php
require_once '../server/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message_id"], $_POST["reply"])) {
    $message_id = intval($_POST["message_id"]);
    $admin_reply = trim($_POST["reply"]);

    if (!empty($admin_reply)) {
        // Ensure message_id exists
        $stmt = $conn->prepare("SELECT id FROM messages WHERE id = ?");
        $stmt->bind_param("i", $message_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update the correct message's reply
            $stmt = $conn->prepare("UPDATE messages SET admin_reply = ? WHERE id = ?");
            $stmt->bind_param("si", $admin_reply, $message_id);
            $stmt->execute();
        }
        $stmt->close();
    }
}

header("Location: dashboard.php"); // Redirect to the chat page
exit();
?>