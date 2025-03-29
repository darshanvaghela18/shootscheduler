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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_project'])) {
    $project_name = $_POST['project_name'];
    $director_name = $_POST['director_name'];
    $expected_release_date = $_POST['expected_release_date'];
    $project_status = $_POST['project_status'];
    $synopsis = $_POST['synopsis'];

$update_sql = "UPDATE projects SET project_name='$project_name', director_name='$director_name', expected_release_date='$expected_release_date', project_status='$project_status', synopsis='$synopsis' WHERE id = $project_id";

    
    if ($conn->query($update_sql) === TRUE) {
        foreach ($_POST['member_id'] as $index => $member_id) {
            $name = $_POST['member_name'][$index];
            $role = $_POST['role'][$index];
            $email = $_POST['email'][$index];

            if ($member_id == "new") {
                $conn->query("INSERT INTO project_members (project_id, member_name, role, email) VALUES ('$project_id', '$name', '$role', '$email')");
            } else {
                $conn->query("UPDATE project_members SET member_name='$name', role='$role', email='$email' WHERE id='$member_id'");
            }
        }
        echo "<script>alert('Project Updated Successfully!'); window.location.href='my-projects.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_member_id'])) {
    $remove_id = $_POST['remove_member_id'];
    $conn->query("DELETE FROM project_members WHERE id='$remove_id'");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project - ShootScheduler</title>
    <link rel="stylesheet" href="../css/update-project.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>ShootScheduler</h1>
        <nav>
            
            <a href="my-projects.php">Back to Projects</a>
            <a href="../server/logout.php">Logout</a>
           
        </nav>
    </header>

    <main class="update-project">
        <h2>Update Project</h2>
        <form action="" method="POST">
            <label>Project Name:</label>
            <input type="text" name="project_name" value="<?php echo $project['project_name']; ?>" required>
            
            <label>Director Name:</label>
            <input type="text" name="director_name" value="<?php echo $project['director_name']; ?>" required>
            
            <label>Expected Release Date:</label>
            <input type="date" name="expected_release_date" value="<?php echo $project['expected_release_date']; ?>" required>
            
            <label>Project Status:</label>
            <select name="project_status">
                <option value="Pre-Production" <?php if ($project['project_status'] == "Pre-Production") echo "selected"; ?>>Pre-Production</option>
                <option value="Filming" <?php if ($project['project_status'] == "Filming") echo "selected"; ?>>Filming</option>
                <option value="Post-Production" <?php if ($project['project_status'] == "Post-Production") echo "selected"; ?>>Post-Production</option>
                <option value="Completed" <?php if ($project['project_status'] == "Completed") echo "selected"; ?>>Completed</option>
            </select>
            <label>Synopsis:</label>
                <textarea name="synopsis" rows="4" required><?php echo $project['synopsis']; ?></textarea>

            
            <h3>Project Members</h3>
            <div id="members-list">
                <?php
                $members_sql = "SELECT * FROM project_members WHERE project_id = $project_id";
                $members_result = $conn->query($members_sql);

                while ($member = $members_result->fetch_assoc()) {
                    echo "<div class='member' id='member_{$member['id']}'>
                            <input type='hidden' name='member_id[]' value='{$member['id']}'>
                            <input type='text' name='member_name[]' value='{$member['member_name']}' required>
                            <input type='text' name='role[]' value='{$member['role']}' required>
                            <input type='email' name='email[]' value='{$member['email']}' required>
                            <button type='button' class='remove-btn' data-id='{$member['id']}'>Remove</button>
                        </div>";
                }
                ?>
            </div>
            <button type="button" onclick="addMember()">Add Member</button>
            
            <button type="submit" name="update_project">Update Project</button>
        </form>
    </main>

    

    <script>
        function addMember() {
            let membersList = document.getElementById('members-list');
            let newId = "new_" + Date.now();
            let newMember = `<div class='member' id='${newId}'>
                                <input type='hidden' name='member_id[]' value='new'>
                                <input type='text' name='member_name[]' placeholder='Member Name' required>
                                <input type='text' name='role[]' placeholder='Role' required>
                                <input type='email' name='email[]' placeholder='Email' required>
                                <button type='button' onclick='removeElement("${newId}")'>Remove</button>
                            </div>`;
            membersList.innerHTML += newMember;
        }

        function removeElement(id) {
            document.getElementById(id).remove();
        }

        $(document).on("click", ".remove-btn", function() {
            let memberId = $(this).data("id");
            let memberDiv = $("#member_" + memberId);

            if (confirm("Are you sure you want to remove this member?")) {
                $.ajax({
                    url: "update-project.php?id=<?php echo $project_id; ?>",
                    type: "POST",
                    data: { remove_member_id: memberId },
                    success: function(response) {
                        memberDiv.fadeOut("slow", function() {
                            $(this).remove();
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
