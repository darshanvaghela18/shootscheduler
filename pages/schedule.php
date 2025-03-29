<?php
session_start();
include '../server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch schedules with dynamic status
$sql = "SELECT s.*, p.project_name, 
        CASE 
            WHEN s.shoot_date > CURDATE() THEN 'Upcoming'
            WHEN s.shoot_date = CURDATE() AND CURTIME() BETWEEN s.start_time AND s.end_time THEN 'Ongoing'
            WHEN s.shoot_date < CURDATE() THEN 'Completed'
        END AS dynamic_status
        FROM schedules s 
        JOIN projects p ON s.project_id = p.id 
        WHERE s.created_by = ? 
        ORDER BY s.shoot_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule - ShootScheduler</title>
    <link rel="stylesheet" href="../css/schedule.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>ShootScheduler</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../server/logout.php">Logout</a>
            
        </nav>
    </header>
    
    <main>
        <section class="schedule-list">
            <h2>My Shoot Schedules</h2>
            <a href="add-schedule.php" class="add-btn">+ Add New Schedule</a>
            <?php if ($result->num_rows > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Location</th>
                            <th>Shoot Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr id="schedule_<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['shoot_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['dynamic_status']); ?></td>
                                <td>
                                    <a href="view-schedule.php?id=<?php echo $row['id']; ?>" class="view">View</a>
                                    <a href="edit-schedule.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                    <a href="#" class="delete" data-id="<?php echo $row['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php } ?> 
                    </tbody>
                </table>
            <?php  } else { ?>
                <p>No schedules found. <a href="add-schedule.php">Add a new schedule</a>.</p>
            <?php } ?>
        </section>
    </main>
    
    
    <script>
        $(document).on("click", ".delete", function(e) {
            e.preventDefault();
            let scheduleId = $(this).data("id");
            let row = $("#schedule_" + scheduleId);

            if (confirm("Are you sure you want to delete this schedule?")) {
                $.ajax({
                    url: "../server/delete-schedule.php",
                    type: "POST",
                    data: { id: scheduleId },
                    success: function(response) {
                        if (response.trim() === "success") {
                            row.fadeOut("slow", function() {
                                $(this).remove();
                            });
                        } else {
                            alert("Error: " + response);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
