<?php
session_start();
include '../server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch upcoming projects for logged-in user
$today = date("Y-m-d");
$user_id = $_SESSION['user_id'];

$projects_sql = "SELECT id, project_name, expected_release_date 
                 FROM projects 
                 WHERE expected_release_date > ? AND created_by = ? 
                 ORDER BY expected_release_date ASC";

$projects_stmt = $conn->prepare($projects_sql);
$projects_stmt->bind_param("si", $today, $user_id);
$projects_stmt->execute();
$projects_result = $projects_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Projects</title>
    <link rel="stylesheet" href="../css/upcoming-projects.css">
</head>
<body>
    <header>
        <h1>Upcoming Projects</h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <section class="project-list">
            <h2>Projects Releasing Soon</h2>
            <?php if ($projects_result->num_rows > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Release Date</th>
                            <th>Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($project = $projects_result->fetch_assoc()) {
                            $release_date = new DateTime($project['expected_release_date']);
                            $today_date = new DateTime($today);
                            $days_left = $today_date->diff($release_date)->days;
                            echo "<tr>
                                    <td>" . htmlspecialchars($project['project_name']) . "</td>
                                    <td>" . htmlspecialchars($project['expected_release_date']) . "</td>
                                    <td><strong>" . $days_left . " days left</strong></td>
                                  </tr>";
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="no-projects">No upcoming projects found.</p>
            <?php } ?>
        </section>
    </main>
</body>
</html>
