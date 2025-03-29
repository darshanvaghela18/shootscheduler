<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Check if the email exists in the database
        $query = "SELECT * FROM users WHERE gmail = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate OTP (random 6 digit number)
            $otp = rand(100000, 999999);
            $expiration_time = date('Y-m-d H:i:s', strtotime('+90 seconds')); // OTP expires in 90 seconds

            // Store OTP and expiration time in the database
            $insert_query = "INSERT INTO otp_requests (gmail, otp, created_at) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sss", $email, $otp, $expiration_time);
            $stmt->execute();

            // Send OTP via Email using PHPMailer
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'darshanvaghela919@gmail.com'; // Your Gmail
                $mail->Password = 'geuc irba vcge yscf'; // Your App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('darshanvaghela919@gmail.com', 'ShootScheduler');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body = "Your OTP for resetting the password is: <b>$otp</b>.<br><br>It will expire in 90 seconds.";

                $mail->send();

                // ✅ Alert box dikhayenge aur fir verify-otp.php pe le jayenge
                echo "<script>
                        alert('✅ OTP sent successfully! Check your email.');
                        window.location.href = '../auth/verify-otp.php?email=$email';
                      </script>";
                exit();
            } catch (Exception $e) {
                echo "<script>
                        alert('❌ Failed to send OTP. Please try again.');
                        window.location.href = '../auth/forget-password.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('❌ No account found with this email.');
                    window.location.href = '../auth/forget-password.php';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('❌ Please enter your email!');
                window.location.href = '../auth/forget-password.php';
              </script>";
        exit();
    }
}
?>
