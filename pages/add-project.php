<?php
session_start();
include '../server/connection.php';

// Agar user logged in nahi hai toh redirect karo
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ka ID
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = trim($_POST['project_name']);
    $director_name = trim($_POST['director_name']);
    $expected_release_date = $_POST['expected_release_date'];
    $budget = trim($_POST['budget']);
    $budget_unit = $_POST['budget_unit'];
    $project_status = $_POST['project_status'];
    $synopsis = trim($_POST['synopsis']); // New synopsis field
    $members = $_POST['member_name'];
    $roles = $_POST['member_role'];
    $emails = $_POST['member_email'];

    // **Validation**
    if (empty($project_name) || empty($director_name) || empty($expected_release_date) || empty($budget) || empty($budget_unit) || empty($project_status) || empty($synopsis)) {
        $error = "All fields are required!";
    } elseif (!is_numeric($budget) || $budget <= 0) {
        $error = "Enter a valid budget amount!";
    } else {
        // **Budget conversion logic**
        switch ($budget_unit) {
            case 'Cr':
                // Save the budget as is when the unit is Crore
                $budget = $budget . " Cr"; // Append " Cr" to the budget
                break;
            case 'Lakh':
                // Save the budget as is when the unit is Lakh
                $budget = $budget . " Lakh"; // Append " Lakh" to the budget
                break;
            case 'Thousand':
                // Save the budget as is when the unit is Thousand
                $budget = $budget . " Thousand"; // Append " Thousand" to the budget
                break;
        }

        // **Insert Project**
        $sql = "INSERT INTO projects (project_name, director_name, expected_release_date, budget, budget_unit, project_status, synopsis, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdsssi", $project_name, $director_name, $expected_release_date, $budget, $budget_unit, $project_status, $synopsis, $user_id);

        if ($stmt->execute()) {
            $project_id = $conn->insert_id;
            
            // **Insert Project Members**
            for ($i = 0; $i < count($members); $i++) {
                $member_name = trim($members[$i]);
                $role = trim($roles[$i]);
                $email = trim($emails[$i]);

                if (!empty($member_name) && !empty($role) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $conn->query("INSERT INTO project_members (project_id, member_name, role, email) 
                                  VALUES ('$project_id', '$member_name', '$role', '$email')");
                }
            }
            echo "<script>alert('Project Added Successfully!'); window.location.href='dashboard.php';</script>";
        } else {
            $error = "Error adding project. Try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - ShootScheduler</title>
    <link rel="stylesheet" href="../css/add-project.css">
</head>
<body>
    <header>
        <h1>ShootScheduler</h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <section class="add-project">
            <h2>Add New Project</h2>

            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <label>Project Name:</label>
                <input type="text" name="project_name" required>
                
                <label>Director Name:</label>
                <input type="text" name="director_name" required>
                
                <label>Expected Release Date:</label>
                <input type="date" name="expected_release_date" required>
                
                <label>Budget:</label>
                <input type="text" name="budget" required>

                <label>Budget Unit:</label>
                <select name="budget_unit" required>
                    <option value="Cr">Crore</option>
                    <option value="Lakh" selected>Lakh</option>
                    <option value="Thousand">Thousand</option>
                </select>
                
                <label>Project Status:</label>
                <select name="project_status">
                    <option value="Pre-Production">Pre-Production</option>
                    <option value="Filming">Filming</option>
                    <option value="Post-Production">Post-Production</option>
                    <option value="Completed">Completed</option>
                </select>
                
                <label>Movie Synopsis:</label>
                <textarea name="synopsis" rows="4" required placeholder="Enter a short summary of the movie..."></textarea>
                
                <h3>Project Members</h3>
                <div id="members">
                    <div class="member">
                        <input type="text" name="member_name[]" placeholder="Member Name" required>
                        <select name="member_role[]">
                            <option value="Actor">Actor</option>
                            <option value="Director">Director</option>
                            <option value="Crew">Crew Member</option>
                        </select>
                        <input type="email" name="member_email[]" placeholder="Email" required>
                        <button type="button" class="remove-member">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-member">+ Add Member</button>
                
                <button type="submit">Save Project</button>
            </form>
        </section>
    </main>
    
    <script>
        // Add Member Script
        document.getElementById('add-member').addEventListener('click', function() {
            var memberDiv = document.createElement('div');
            memberDiv.classList.add('member');
            memberDiv.innerHTML = `
                <input type="text" name="member_name[]" placeholder="Member Name" required>
                <select name="member_role[]">
                    <option value="Actor">Actor</option>
                    <option value="Director">Director</option>
                    <option value="Crew">Crew Member</option>
                </select>
                <input type="email" name="member_email[]" placeholder="Email" required>
                <button type="button" class="remove-member">Remove</button>
            `;
            document.getElementById('members').appendChild(memberDiv);
        });

        // Remove Member Script
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-member')) {
                e.target.parentElement.remove();
            }
        });
    </script>
</body>
</html>
