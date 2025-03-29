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

$sql = "SELECT * FROM schedules WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Schedule not found!";
    exit();
}

$schedule = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = $_POST['location'];
    $shoot_date = $_POST['shoot_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $shoot_purpose = $_POST['shoot_purpose'];

    if (strtotime($start_time) >= strtotime($end_time)) {
        echo "<script>alert('Start time must be before end time!'); window.history.back();</script>";
        exit();
    }

    $update_sql = "UPDATE schedules SET location = ?, shoot_date = ?, start_time = ?, end_time = ?, shoot_purpose = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $location, $shoot_date, $start_time, $end_time, $shoot_purpose, $schedule_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Schedule updated successfully!'); window.location.href='schedule.php';</script>";
    } else {
        echo "Error: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="../css/edit-schedule.css">
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
        <h2>Edit Schedule</h2>
        <form action="" method="POST" onsubmit="return validateDates()">
            <label>Location:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($schedule['location']); ?>" required>

            <label>Shoot Date:</label>
            <input type="date" name="shoot_date" value="<?php echo htmlspecialchars($schedule['shoot_date']); ?>" required>

            <label>Start Time:</label>
            <input type="time" name="start_time" value="<?php echo htmlspecialchars($schedule['start_time']); ?>" required>

            <label>End Time:</label>
            <input type="time" name="end_time" value="<?php echo htmlspecialchars($schedule['end_time']); ?>" required>

            <label>Shoot Purpose:</label>
            <select name="shoot_purpose" required>
                <option value="Promo" <?php if ($schedule['shoot_purpose'] == 'Promo') echo 'selected'; ?>>Promo</option>
                <option value="Scene" <?php if ($schedule['shoot_purpose'] == 'Scene') echo 'selected'; ?>>Scene</option>
                <option value="Interview" <?php if ($schedule['shoot_purpose'] == 'Interview') echo 'selected'; ?>>Interview</option>
                <option value="Photoshoot" <?php if ($schedule['shoot_purpose'] == 'Photoshoot') echo 'selected'; ?>>Photoshoot</option>
                <option value="Behind the Scenes" <?php if ($schedule['shoot_purpose'] == 'Behind the Scenes') echo 'selected'; ?>>Behind the Scenes</option>
            </select>

            <button type="submit">Update Schedule</button>
        </form>

        
    </div>

   

    <script>
        function validateDates() {
            let startTime = document.querySelector("input[name='start_time']").value;
            let endTime = document.querySelector("input[name='end_time']").value;

            if (startTime >= endTime) {
                alert("Start time must be before end time!");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>

