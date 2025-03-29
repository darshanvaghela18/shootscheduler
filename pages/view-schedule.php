<?php
session_start();
include '../server/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
if (!isset($_GET['id'])) {
    echo "Invalid Schedule ID";
    exit();
}

$schedule_id = $_GET['id'];

$sql = "SELECT s.*, p.project_name FROM schedules s JOIN projects p ON s.project_id = p.id WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Schedule not found!";
    exit();
}

$schedule = $result->fetch_assoc();

// **Dynamic Status Calculation**
date_default_timezone_set('Asia/Kolkata'); // India time zone
$current_date = date("Y-m-d");
$current_time = date("H:i:s");

if ($schedule['shoot_date'] > $current_date) {
    $status = "Upcoming";
} elseif ($schedule['shoot_date'] == $current_date) {
    if ($schedule['end_time'] > $current_time) {
        $status = "Ongoing";
    } else {
        $status = "Completed";
    }
} else {
    $status = "Completed";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule</title>
    <link rel="stylesheet" href="../css/view-schedule.css">
</head>
<body>

<header>
        <h1>ShootScheduler</h1>
        <nav>
            
        <a href="schedule.php" class="back-btn">Back to Schedule</a>
        <a href="../server/logout.php">Logout</a>
            
        </nav>
    </header>

    <div class="container">
        <h2>Schedule Details</h2>
        <div class="schedule-card">
            <h3><?php echo htmlspecialchars($schedule['project_name']); ?></h3>
            <div class="schedule-info">
                <p><strong>Location:</strong> <?php echo htmlspecialchars($schedule['location']); ?></p>
                <p><strong>Shoot Date:</strong> <?php echo htmlspecialchars($schedule['shoot_date']); ?></p>
                <p><strong>Start Time:</strong> <?php echo htmlspecialchars($schedule['start_time']); ?></p>
                <p><strong>End Time:</strong> <?php echo htmlspecialchars($schedule['end_time']); ?></p>
                <p><strong>Shoot Purpose:</strong> <?php echo htmlspecialchars($schedule['shoot_purpose']); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status <?php echo strtolower($status); ?>">
                        <?php echo $status; ?>
                    </span>
                </p>
            </div>
        </div>

        
    </div>

    

</body>
</html>
