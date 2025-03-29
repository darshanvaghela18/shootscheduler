<?php
session_start();
include '../server/connection.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch user's projects
$project_sql = "SELECT id, project_name FROM projects WHERE created_by = ?";
$project_stmt = $conn->prepare($project_sql);
$project_stmt->bind_param("i", $user_id);
$project_stmt->execute();
$projects_result = $project_stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST['project_id'];
    $location = $_POST['location'];
    $shoot_date = $_POST['shoot_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $shoot_purpose = $_POST['shoot_purpose']; // ✅ Fix: Shoot purpose ka variable create kiya

    if (strtotime($start_time) >= strtotime($end_time)) {
        echo "<script>alert('Start time must be before end time!'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO schedules (project_id, shoot_date, start_time, end_time, location, shoot_purpose, created_by, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssi", $project_id, $shoot_date, $start_time, $end_time, $location, $shoot_purpose, $user_id, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Schedule added successfully!'); window.location.href='schedule.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>
    <link rel="stylesheet" href="../css/add-schedule.css">
</head>
<body>
    <h2>Add New Schedule</h2>
    <form action="add-schedule.php" method="POST" onsubmit="return validateDates()">
        <label>Project:</label>
        <select name="project_id" required>
            <option value="">Select Project</option>
            <?php while ($row = $projects_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['project_name']); ?></option>
            <?php } ?>
        </select>
        
        <label>Location:</label>
        <input type="text" name="location" required>
        
        <label>Shoot Date:</label>
        <input type="date" name="shoot_date" required>
        
        <label>Start Time:</label>
        <input type="time" name="start_time" required>
        
        <label>End Time:</label>
        <input type="time" name="end_time" required>
        
        <label>Shoot Purpose:</label>
        <select name="shoot_purpose" required>
            <option value="Promo">Promo</option>
            <option value="Scene">Scene</option>
            <option value="Interview">Interview</option>
            <option value="Photoshoot">Photoshoot</option>
            <option value="Behind the Scenes">Behind the Scenes</option>
        </select>

        <button type="submit">Add Schedule</button>
    </form>
    <a href="schedule.php">Back to Schedule List</a>
    
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
