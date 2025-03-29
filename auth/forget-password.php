<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="../css/auth.css?v=1">
</head>
<body>
    <div class="container">
        <h1>Forget Password</h1>

        <!-- Show Alert Message (Success/Error) -->
        <?php if (isset($_SESSION['error'])): ?>
            <script>alert("<?php echo $_SESSION['error']; ?>");</script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <script>alert("<?php echo $_SESSION['success']; ?>");</script>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Form to enter email -->
        <form action="../server/send-otp.php" method="POST">
            <label for="email">Enter Your Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <button type="submit">Send OTP</button>
        </form>
        <div class="extra-links">
            Remembered your password? <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
