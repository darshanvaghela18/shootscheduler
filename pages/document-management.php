<?php
session_start();
include '../server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if project ID is provided
if (!isset($_GET['project_id'])) {
    header("Location: document-list.php");
    exit();
}

$project_id = $_GET['project_id'];

// Fetch project name
$project_sql = "SELECT project_name FROM projects WHERE id = ? AND created_by = ?";
$project_stmt = $conn->prepare($project_sql);
$project_stmt->bind_param("ii", $project_id, $_SESSION['user_id']);
$project_stmt->execute();
$project_result = $project_stmt->get_result();

if ($project_result->num_rows === 0) {
    header("Location: document-list.php");
    exit();
}

$project = $project_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Documents - <?php echo htmlspecialchars($project['project_name']); ?></title>
    <link rel="stylesheet" href="../css/document-management.css">
    <script>
        function confirmDelete(url) {
            if (confirm("Are you sure you want to delete this file?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>ShootScheduler - Document Management</h1>
        <nav>
            <a href="document-list.php">Back to Projects</a>
            
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <section class="upload-section">
        <h2>Project: <?php echo htmlspecialchars($project['project_name']); ?></h2>
            <h2>Upload New Document</h2>
            <form action="../server/upload-document.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <input type="text" name="document_name" placeholder="Enter document name" required>
                <input type="file" name="document" required>
                <button type="submit">Upload</button>
            </form>
        </section>

        <section class="documents">
            <h2>Documents</h2>
            <table>
                <thead>
                    <tr>
                        <th>Document Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $docs_sql = "SELECT id, document_name, file_name FROM documents WHERE project_id = ?";
    $docs_stmt = $conn->prepare($docs_sql);
    $docs_stmt->bind_param("i", $project_id);
    $docs_stmt->execute();
    $docs_result = $docs_stmt->get_result();

    while ($doc = $docs_result->fetch_assoc()) {
        $file_url = "../uploads/documents/" . rawurlencode($doc['file_name']);
        echo "<tr>
                <td>" . htmlspecialchars($doc['document_name']) . "</td>
                <td>
                    <a href='$file_url' target='_blank'>View</a> |
                   
                    <a href=\"javascript:void(0);\" onclick=\"confirmDelete('../server/delete-document.php?doc_id=" . $doc['id'] . "')\">Delete</a>
                </td>
              </tr>";
    }
    ?>
</tbody>

            </table>
        </section>

        <section class="upload-section">
            <h2>Upload New Photo</h2>
            <form action="../server/upload-photo.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <input type="text" name="photo_name" placeholder="Enter photo name" required>
                <input type="file" name="photo" accept="image/*" required>
                <button type="submit">Upload Photo</button>
            </form>
        </section>

        <section class="photo-gallery">
    <h2>Project Photos</h2>
    <div class="photo-container">
        <?php
        $photos_sql = "SELECT id, photo_name, file_name FROM photos WHERE project_id = ?";
        $photos_stmt = $conn->prepare($photos_sql);
        $photos_stmt->bind_param("i", $project_id);
        $photos_stmt->execute();
        $photos_result = $photos_stmt->get_result();

        while ($photo = $photos_result->fetch_assoc()) {
            $photo_url = "../uploads/photos/" . rawurlencode($photo['file_name']);
            echo "<div class='photo-item'>
                    <img src='$photo_url' alt='" . htmlspecialchars($photo['photo_name']) . "'>
                    <p>" . htmlspecialchars($photo['photo_name']) . "</p>
                    <a href=\"javascript:void(0);\" onclick=\"confirmDelete('../server/delete-photo.php?photo_id=" . $photo['id'] . "')\">🗑 Delete</a>
                  </div>";
        }
        ?>
    </div>
</section>

    </main>
</body>
</html>
