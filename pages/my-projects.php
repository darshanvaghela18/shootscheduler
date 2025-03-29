<?php
session_start();
include '../server/connection.php';

// Agar user logged in nahi hai toh redirect karo
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ka ID
$sql = "SELECT * FROM projects WHERE created_by = ?";
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
    <title>My Projects - ShootScheduler</title>
    <link rel="stylesheet" href="../css/my-projects.css">
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
        <section class="project-list">
            <h2>My Projects</h2>
            <?php if ($result->num_rows > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Director</th>
                            <th>Release Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr id="project_<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['director_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['expected_release_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['project_status']); ?></td>
                                <td>
                                    <a href="view-project.php?id=<?php echo $row['id']; ?>" class="view">View</a>
                                    <a href="update-project.php?id=<?php echo $row['id']; ?>" class="edit">Update</a>
                                    <a href="#" class="delete" data-id="<?php echo $row['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p style="text-align: center; font-size: 18px; color: red;">
                    Abhi tak apne koi project nahi banaya hai! Pehle <a href="add-project.php" style="color: blue;">Add Project</a> page par jaye.
                </p>
            <?php } ?>
        </section>
    </main>
    
    

    <script>
       $(document).on("click", ".delete", function(e) {
            e.preventDefault();
            let projectId = $(this).data("id");
            let row = $("#project_" + projectId);

            if (confirm("Are you sure you want to delete this project?")) {
                $.ajax({
                    url: "../server/delete-project.php",
                    type: "POST",
                    data: { id: projectId },
                    success: function(response) {
                        if (response.trim() === "success") {
                            row.fadeOut("slow", function() {
                                $(this).remove();
                                if ($("tbody tr").length === 0) {
                                    $(".project-list").html(
                                        '<p style="text-align: center; font-size: 18px; color: red;">Abhi tak apne koi project nahi banaya hai! Pehle <a href="add-project.php" style="color: blue;">Add Project</a> page par jaye.</p>'
                                    );
                                }
                            });
                        } else {
                            alert("Error: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX Error: " + error);
                    }
                });
            }
        });
    </script>
</body>
</html>
