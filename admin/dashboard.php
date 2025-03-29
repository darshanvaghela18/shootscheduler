<?php
session_start();
require_once '../server/connection.php'; // Database connection

// Admin authentication check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total users
$user_count_query = "SELECT COUNT(*) AS total_users FROM users";
$user_count_result = $conn->query($user_count_query);
$total_users = $user_count_result->fetch_assoc()['total_users'] ?? 0;

// Active Users (jo 10 din ke andar login kiye)
$active_users_query = $conn->query("SELECT COUNT(*) as active FROM users WHERE last_login >= NOW() - INTERVAL 5 DAY");

$active_users = $active_users_query->fetch_assoc()['active'] ?? 0;

// Inactive Users (jo 10 din se login nahi kiye)
$inactive_users_query = $conn->query("SELECT COUNT(*) as inactive FROM users WHERE last_login < NOW() - INTERVAL 5 DAY OR last_login IS NULL");

$inactive_users = $inactive_users_query->fetch_assoc()['inactive'] ?? 0;

// Fetch total projects
$project_count_query = "SELECT COUNT(*) AS total_projects FROM projects";
$project_count_result = $conn->query($project_count_query);
$total_projects = $project_count_result->fetch_assoc()['total_projects'] ?? 0;

// Fetch users
$users_query = $conn->query("SELECT id, name, last_login FROM users");

// Fetch all messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "delete-user.php?id=" + userId;
            }
        }
    </script>
</head>
<body>
    <header>
        <h2>Admin Dashboard</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    <main>
        <section class="stats">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="card">
                <h3>Active Users</h3>
                <p><?php echo $active_users; ?></p>
            </div>
            <div class="card">
                <h3>Inactive Users</h3>
                <p><?php echo $inactive_users; ?></p>
            </div>
            <div class="card">
                <h3>Total Projects</h3>
                <p><?php echo $total_projects; ?></p>
            </div>
        </section>

        <section class="user-management">
            <h3>Manage Users</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($user = $users_query->fetch_assoc()) { 
                   $lastLogin = $user['last_login'] ?? ''; // NULL ko empty string banaya
                   $lastLoginTimestamp = !empty($lastLogin) ? strtotime($lastLogin) : 0;
                   
                   $status = ($lastLoginTimestamp >= strtotime('-5 days')) ? "Active" : "Inactive";
                   
                    echo "<tr>
                        <td>{$user['id']}</td>
                        <td>{$user['name']}</td>
                        <td>{$status}</td>
                        <td><button onclick='confirmDelete({$user['id']})' class='delete-btn'>Delete</button></td>
                    </tr>";
                } ?>
            </table>
        </section>
        <h3>User Messages</h3>
        <section class="messages">
    <?php
    // Fetch all messages with user info, ordered by creation date descending (newest first)
    $message_query = "SELECT m.id, m.user_id, m.message, m.admin_reply, m.created_at, u.name as user_name 
                  FROM messages m 
                  JOIN users u ON m.user_id = u.id 
                  ORDER BY m.user_id, m.created_at ASC";  // ASC taaki old-to-new order ho

    $messages = $conn->query($message_query);

    // Group messages by user
    $grouped_messages = [];
    while ($message = $messages->fetch_assoc()) {
        $grouped_messages[$message['user_id']][] = $message;
    }

    // Display messages for each user
    foreach ($grouped_messages as $user_id => $user_messages) 
    {
        $user_name = $user_messages[0]['user_name']; // Get the user's name
      ?>
        <div class="message-thread">
            <h4>Chat with <?php echo htmlspecialchars($user_name); ?></h4>
            <!-- <button class="close-chat" onclick="closeChat(<?php echo $user_id; ?>)">Close Chat</button> -->
            
            <div class="chat-box" id="chat-<?php echo $user_id; ?>">
                <?php
                foreach ($user_messages as $msg) {
                    // Display user's message
                    ?>
                    <div class="user-message">
                        <p><strong> <?php echo htmlspecialchars($user_name); ?> :</strong> <?php echo htmlspecialchars($msg['message']); ?></p>
                        <p><small>Sent on: <?php echo $msg['created_at']; ?></small></p>
                    </div>
                    <?php
                    // Display admin's reply if available
                    if (!empty($msg['admin_reply'])) {
                    ?>
                    <div class="admin-reply">
                        <p><strong>Admin:</strong> <?php echo htmlspecialchars($msg['admin_reply']); ?></p>
                    </div>
                    <?php
                    }
                }
                ?>
            </div>

            <!-- Admin reply form -->
            <form action="reply.php" method="POST">
                <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                <textarea name="reply" placeholder="Type your reply..." required></textarea>
                <button type="submit">Send Reply</button>
                <!-- Delete user chat -->
                <!-- <form action="delete_chat.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit" class="delete-btn">Delete Chat</button>
                </form> -->
            </form>

            <!-- Delete user chat -->
            <form action="delete_chat.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit" class="delete-btn-chat">Delete Chat</button>
            </form>
        </div>
     <?php
    }
 ?>
</section>
<section class="user-management">
  <h2>Contact Messages</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['message']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="dashboard.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
       </table>
    </section>



    </main>
</body>
</html>
