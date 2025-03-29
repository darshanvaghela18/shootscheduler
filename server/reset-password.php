<?php
session_start();
include('connection.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['email']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $email = $_SESSION['email']; // Email stored from the verified OTP step
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if passwords match
        if ($new_password === $confirm_password) {
            // Hash the password for security
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $query = "UPDATE users SET password = ? WHERE gmail = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                // Success: Redirect to login page with a success message
                echo "<script>
                        alert('Password changed successfully. Please log in.');
                        window.location.href = '../pages/login.php';
                      </script>";
            } else {
                // Error: Database issue
                echo "<script>
                        alert('An error occurred. Please try again.');
                        window.location.href = '../pages/reset-password.php';
                      </script>";
            }
        } else {
            // Error: Passwords do not match
            echo "<script>
                    alert('Passwords do not match. Please try again.');
                    window.location.href = '../pages/reset-password.php';
                  </script>";
        }
    } else {
        // Error: Missing session or form data
        echo "<script>
                alert('Invalid request. Please try again.');
                window.location.href = '../pages/reset-password.php';
              </script>";
    }
} else {
    // Redirect to reset-password page if accessed directly
    header("Location: ../pages/reset-password.php");
    exit();
}
?>
