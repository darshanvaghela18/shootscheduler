<?php
include '../server/connection.php';

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $sql = "SELECT * FROM projects WHERE id = $project_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    } else {
        echo "<script>alert('Project Not Found!'); window.location.href='my-projects.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid Request!'); window.location.href='my-projects.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Project - ShootScheduler</title>
    <link rel="stylesheet" href="../css/view-project.css">
</head>
<body>
    <header>
        <h1>ShootScheduler</h1>
        <nav>
            
            <a href="my-projects.php">Back to Projects</a>
            <a href="../server/logout.php">Logout</a>
            
        </nav>
    </header>

    <main class="project-details">
        <h2>Project Details</h2>
        <p><strong>Project Name:</strong> <?php echo $project['project_name']; ?></p>
        <p><strong>Director:</strong> <?php echo $project['director_name']; ?></p>
        <p><strong>Expected Release Date:</strong> <?php echo $project['expected_release_date']; ?></p>
        <p><strong>Status:</strong> <?php echo $project['project_status']; ?></p>
        <p><strong>Synopsis:</strong> <?php echo $project['synopsis']; ?></p>
        
        <h3>Project Members</h3>
        <ul>
            <?php
            $members_sql = "SELECT * FROM project_members WHERE project_id = $project_id";
            $members_result = $conn->query($members_sql);

            while ($member = $members_result->fetch_assoc()) {
                echo "<li><strong>{$member['member_name']}</strong> - {$member['role']} ({$member['email']})</li>";
            }
            ?>
        </ul>
    </main>

    
</body>
</html>
