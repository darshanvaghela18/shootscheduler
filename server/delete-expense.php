<?php
include '../server/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_id = $_POST['expense_id'];

    $sql = "DELETE FROM expenses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $expense_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: Could not delete expense!";
    }
}
?>
