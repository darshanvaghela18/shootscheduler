<?php
session_start();
require_once '../server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle new user message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all messages of this user
$stmt = $conn->prepare("SELECT message, admin_reply, created_at FROM messages WHERE user_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask to Admin</title>
    <link rel="stylesheet" href="../css/chat.css">
</head>
<body>

<header>
    <h1>ShootScheduler</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="../server/logout.php">Logout</a>
    </nav>
</header>

<div class="chat-container">
    <h2>Ask to Admin</h2>

    <div class="chat-box">
        <?php foreach ($messages as $msg): ?>
            <div class="user-message">
                <p><strong>You:</strong> <?= htmlspecialchars($msg['message']) ?></p>
                <p><small><?= $msg['created_at'] ?></small></p>
            </div>

            <?php if (!empty($msg['admin_reply'])): ?>
                <div class="admin-message">
                    <p><strong>Admin:</strong> <?= htmlspecialchars($msg['admin_reply']) ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <form action="" method="POST">
        <textarea name="message" placeholder="Type your message..." required></textarea>
        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
