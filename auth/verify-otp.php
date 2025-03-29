<?php
session_start();
include('../server/connection.php'); // Database connection

$message = ""; // Store success or error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['otp']) || empty($_POST['email'])) {
        $message = "❌ All fields are required!";
    } else {
        $otp = trim($_POST['otp']);
        $email = trim($_POST['email']);

        // OTP should be exactly 6 digits and numbers only
        if (!ctype_digit($otp) || strlen($otp) != 6) {
            $message = "❌ Invalid OTP format. Enter a 6-digit number.";
        } else {
            // Check if OTP exists and is valid
            $query = "SELECT * FROM otp_requests WHERE gmail = ? AND otp = ? ORDER BY created_at DESC LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $email, $otp);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $created_at = $row['created_at'];
                $expiration_time = strtotime($created_at) + 90; // OTP expires in 90 seconds

                if (time() <= $expiration_time) {
                    $_SESSION['email'] = $email; // Store email in session
                    echo "<script>
                            alert('✅ OTP verified successfully!');
                            window.location.href = 'reset-password.php';
                          </script>";
                    exit();
                } else {
                    $message = "❌ OTP has expired. Please request a new OTP.";
                }
            } else {
                $message = "❌ Invalid OTP. Please try again.";
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
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../css/auth.css?v=1"> <!-- Using auth.css for consistent styling -->
</head>
<body>
    <div class="otp-container">
        <h1>Verify Your OTP</h1>
        <p>Please enter the OTP sent to your email to proceed.</p>

        <?php if (!empty($message)) : ?>
            <p style="color: red; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="otp">OTP:</label>
            <input type="text" name="otp" id="otp" placeholder="Enter OTP" maxlength="6" pattern="\d{6}" title="Enter a 6-digit number" required>

            <button type="submit">Verify OTP</button>
        </form>

        <div id="timer"></div>
    </div>

    <script>
        let timerDisplay = document.getElementById('timer');
        let storedTime = sessionStorage.getItem('otpExpiryTime');

        if (!storedTime) {
            let currentTime = Math.floor(Date.now() / 1000);
            let expiryTime = currentTime + 90; // OTP valid for 90 seconds
            sessionStorage.setItem('otpExpiryTime', expiryTime);
            storedTime = expiryTime;
        }

        function updateTimer() {
            let currentTime = Math.floor(Date.now() / 1000);
            let timeLeft = storedTime - currentTime;

            if (timeLeft > 0) {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerDisplay.textContent = `Time Remaining: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            } else {
                clearInterval(countdown);
                sessionStorage.removeItem('otpExpiryTime'); // Clear session storage
                alert('❌ OTP has expired! Please request a new one.');
                window.location.href = 'forget-password.php'; // Redirect to request new OTP
            }
        }

        updateTimer();
        let countdown = setInterval(updateTimer, 1000);
    </script>
</body>
</html>
