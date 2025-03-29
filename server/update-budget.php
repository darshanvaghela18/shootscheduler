<?php
session_start();
include '../server/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST['project_id'];
    $new_budget = $_POST['budget'];
    $new_budget_unit = $_POST['budget_unit'];

    // Fetch the old budget
    $old_budget_sql = "SELECT budget FROM projects WHERE id = ?";
    $old_budget_stmt = $conn->prepare($old_budget_sql);
    $old_budget_stmt->bind_param("i", $project_id);
    $old_budget_stmt->execute();
    $old_budget_result = $old_budget_stmt->get_result();
    
    if ($old_budget_result->num_rows > 0) {
        $old_budget_data = $old_budget_result->fetch_assoc();
        $old_budget = $old_budget_data['budget'];

        // Check if there are expenses
        $expense_check_sql = "SELECT COUNT(*) AS expense_count FROM expenses WHERE project_id = ?";
        $expense_check_stmt = $conn->prepare($expense_check_sql);
        $expense_check_stmt->bind_param("i", $project_id);
        $expense_check_stmt->execute();
        $expense_count_result = $expense_check_stmt->get_result()->fetch_assoc();
        $expense_count = $expense_count_result['expense_count'];

        // If expenses exist, delete them
        if ($expense_count > 0) {
            $delete_expenses_sql = "DELETE FROM expenses WHERE project_id = ?";
            $delete_expenses_stmt = $conn->prepare($delete_expenses_sql);
            $delete_expenses_stmt->bind_param("i", $project_id);
            $delete_expenses_stmt->execute();
        }

        // Now update the budget
        $update_budget_sql = "UPDATE projects SET budget = ?, budget_unit = ? WHERE id = ?";
        $update_budget_stmt = $conn->prepare($update_budget_sql);
        $update_budget_stmt->bind_param("dsi", $new_budget, $new_budget_unit, $project_id);

        if ($update_budget_stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Project not found!";
    }
}
?>
