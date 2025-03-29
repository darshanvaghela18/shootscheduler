<?php
session_start();
include('../server/connection.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['gmail']) && !empty($_POST['password'])) {
        $gmail = trim(htmlspecialchars($_POST['gmail'])); 
        $password = trim($_POST['password']);

        // Fetch user data
        $query = "SELECT * FROM users WHERE gmail = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("s", $gmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stmt->close();

            // Password verify
            if (password_verify($password, $user['password'])) {
                // Session setup
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['gmail'] = $user['gmail'];

                // Update last login time after setting session
                $update_login_time = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $update_login_time->bind_param("i", $_SESSION['user_id']);
                $update_login_time->execute();

                // Success Message
                echo "<script>
                    alert('✅ Login Successful! Redirecting...');
                    window.location.href = '../pages/dashboard.php';
                </script>";
                exit();
            } else {
                $error_message = "❌ Invalid password. Please try again.";
            }
        } else {
            $error_message = "❌ No user found with this email. Please try again.";
        }
    } else {
        $error_message = "❌ Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShootScheduler</title>
    <link rel="stylesheet" href="../css/auth.css"> 
</head>
<body>
    <div class="container">
        <!-- Back to Home Button -->
        <a href="../index.php" class="back-button">
            ⬅ Back to Home
        </a>

        <h1>Login</h1>
        <p>Access your ShootScheduler account</p>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="gmail" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span>
            </div>

            <div class="extra-links">
                <a href="forget-password.php">Forgot Password?</a>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="extra-links">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </p>
    </div>

    <script>
        function togglePassword(id) {
            let field = document.getElementById(id);
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        }
    </script>
</body>
</html>
