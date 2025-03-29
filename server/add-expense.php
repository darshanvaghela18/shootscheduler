<?php
include '../server/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST['project_id'];
    $amount = floatval($_POST['amount']);  // Ensure numeric value
    $expense_unit = $_POST['expense_unit'];
    $description = $_POST['description'];

    // Fetch the project's budget unit
    $sql_budget = "SELECT budget, budget_unit FROM projects WHERE id = ?";
    $stmt_budget = $conn->prepare($sql_budget);
    $stmt_budget->bind_param("i", $project_id);
    $stmt_budget->execute();
    $result_budget = $stmt_budget->get_result();
    $project = $result_budget->fetch_assoc();
    
    if (!$project) {
        echo "error: Project not found!";
        exit;
    }

    $budget_unit = $project['budget_unit'];

    // Unit conversion logic
    $conversion_rates = [
        "Cr" => ["Cr" => 1, "Lakh" => 100, "Thousand" => 100000],
        "Lakh" => ["Cr" => 0.01, "Lakh" => 1, "Thousand" => 1000],
        "Thousand" => ["Cr" => 0.00001, "Lakh" => 0.001, "Thousand" => 1]
    ];

    if (!isset($conversion_rates[$expense_unit][$budget_unit])) {
        echo "error: Invalid unit conversion!";
        exit;
    }

    $converted_amount = round($amount * $conversion_rates[$expense_unit][$budget_unit], 2); // Rounding to 2 decimal places

    // Debugging Converted Amount
    if ($converted_amount <= 0) {
        echo "error: Converted amount is too small!";
        exit;
    }

    // Check if expense exceeds the budget
    $sql_total_expense = "SELECT SUM(amount) AS total_expense FROM expenses WHERE project_id = ?";
    $stmt_total = $conn->prepare($sql_total_expense);
    $stmt_total->bind_param("i", $project_id);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $row = $result_total->fetch_assoc();
    
    $total_expense = floatval($row['total_expense'] ?? 0);
    $remaining_budget = floatval($project['budget']) - $total_expense;

    if ($converted_amount > $remaining_budget) {
        echo "error: Expense exceeds available budget!";
        exit;
    }

    // Insert converted expense amount into database
    $sql = "INSERT INTO expenses (project_id, amount, description, expense_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $project_id, $converted_amount, $description);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: Something went wrong!";
    }
}
?>
