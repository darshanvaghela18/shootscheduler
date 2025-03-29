<?php
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = trim($_POST["new_password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

    // Validate password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('❌ Passwords do not match. Please try again.');</script>";
    } elseif (strlen($newPassword) < 6) {
        echo "<script>alert('❌ Password must be at least 6 characters long.');</script>";
    } else {
        // Hash password for security
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // TODO: Update password in database
        // Example: UPDATE users SET password='$hashedPassword' WHERE email='$user_email';

        echo "<script>
                alert('✅ Password reset successfully! Click OK to login.');
                window.location.href = '../auth/login.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/auth.css?v=1"> <!-- Common CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>

        <form action="reset-password.php" method="POST">
            <label for="new_password">New Password</label>
            <div class="password-wrapper">
                <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('new_password', this)"></i>
            </div>

            <label for="confirm_password">Confirm Password</label>
            <div class="password-wrapper">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePassword(fieldId, icon) {
            const passwordField = document.getElementById(fieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>
</html>
