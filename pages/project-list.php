<?php
session_start();
include '../server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$projects_sql = "SELECT id, project_name FROM projects WHERE created_by = ?";
$projects_stmt = $conn->prepare($projects_sql);
$projects_stmt->bind_param("i", $user_id);
$projects_stmt->execute();
$projects_result = $projects_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project List - ShootScheduler</title>
    <link rel="stylesheet" href="../css/project-list.css">
</head>
<body>
    <header>
        <h1>ShootScheduler - Budget Tracking</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="../server/logout.php">Logout</a>
          
        </nav>
    </header>
    
    <main>
        <section class="project-list">
            <h2>Select Project for Budget Tracking</h2>
            <?php if ($projects_result->num_rows > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($project = $projects_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                                <td>
                                <a href="budget-tracking.php?project_id=<?php echo $project['id']; ?>" class="view">Manage Budget</a>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No projects found. <a href="add-project.php">Create a new project</a>.</p>
            <?php } ?>
        </section>
    </main>
    
    
</body>
</html>
