<?php
session_start();
include('../server/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $gmail = $_POST['email']; 
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $query = "SELECT * FROM users WHERE gmail = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $gmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (name, gmail, password) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("sss", $name, $gmail, $hashed_password);
            
            if ($insert_stmt->execute()) {
                echo "<script>alert('User registered successfully!'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error: " . $insert_stmt->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="../index.php" class="back-button">&#8592; Back to Home</a>

        <h1>Create Account</h1>
        <form action="signup.php" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Create a password" required>
                <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span>
            </div>

            <label for="confirm_password">Confirm Password</label>
            <div class="password-wrapper">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">&#128065;</span>
            </div>

            <button type="submit">Signup</button>
        </form>
        <div class="extra-links">
            <br>
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            let field = document.getElementById(id);
            let icon = field.nextElementSibling;

            if (field.type === "password") {
                field.type = "text";
                icon.classList.add("active");
            } else {
                field.type = "password";
                icon.classList.remove("active");
            }
        }
    </script>
</body>
</html>
