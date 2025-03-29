<?php
session_start();
include '../server/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

// Fetch project details
$project_sql = "SELECT id, project_name, budget, budget_unit FROM projects WHERE id = ? AND created_by = ?";
$project_stmt = $conn->prepare($project_sql);
$project_stmt->bind_param("ii", $project_id, $user_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();
$project = $project_result->fetch_assoc();

if (!$project) {
    die("Project not found!");
}

// Ensure budget is numeric
$project['budget'] = is_numeric($project['budget']) ? (float)$project['budget'] : 0;

// Fetch expenses
$expense_sql = "SELECT * FROM expenses WHERE project_id = ? ORDER BY expense_date DESC";
$expense_stmt = $conn->prepare($expense_sql);
$expense_stmt->bind_param("i", $project_id);
$expense_stmt->execute();
$expense_result = $expense_stmt->get_result();

// Calculate remaining budget
$total_spent_sql = "SELECT SUM(amount) AS total_spent FROM expenses WHERE project_id = ?";
$total_spent_stmt = $conn->prepare($total_spent_sql);
$total_spent_stmt->bind_param("i", $project_id);
$total_spent_stmt->execute();
$total_spent_result = $total_spent_stmt->get_result()->fetch_assoc();
$total_spent = is_numeric($total_spent_result['total_spent']) ? (float)$total_spent_result['total_spent'] : 0;
$remaining_budget = $project['budget'] - $total_spent;

$warning = ($project['budget'] > 0 && ($remaining_budget / $project['budget']) * 100 <= 10) ? "<p style='color: red;'>Warning: Budget is almost exhausted!</p>" : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracking</title>
    <link rel="stylesheet" href="../css/budget-tracking.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>ShootScheduler - Budget Tracking</h1>
        <nav>
            
            <a href="project-list.php">Back to project list</a>
            <a href="../server/logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <section class="budget-details">
            <h2>Project: <?php echo htmlspecialchars($project['project_name']); ?></h2>
            <p><strong>Budget:</strong> <?php echo number_format($project['budget'], 2) . ' ' . $project['budget_unit']; ?></p>
            <p><strong>Remaining Budget:</strong> <span id="remaining-budget" data-remaining="<?php echo $remaining_budget; ?>"> <?php echo number_format($remaining_budget, 2) . ' ' . $project['budget_unit']; ?></span></p>
            <?php echo $warning; ?>
            
            <h3>Update Budget</h3>
            <form id="update-budget-form">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <input type="number" name="budget" step="0.01" required>
                <select name="budget_unit">
                    <option value="Cr">Cr</option>
                    <option value="Lakh">Lakh</option>
                    <option value="Thousand">Thousand</option>
                </select>
                <button type="submit">Update</button>
            </form>
        </section>

        <section class="expenses">
            <h3>Manage Expenses</h3>
            <form id="add-expense-form">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <input type="number" name="amount" step="0.01" required>
                <select name="expense_unit">
                    <option value="Cr">Cr</option>
                    <option value="Lakh">Lakh</option>
                    <option value="Thousand">Thousand</option>
                </select>
                <input type="text" name="description" placeholder="Expense Description" required>
                <input type="date" name="expense_date" required>
                <button type="submit">Add Expense</button>
            </form>
            
            <table>
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($expense = $expense_result->fetch_assoc()) { ?>
                        <tr id="expense_<?php echo $expense['id']; ?>">
                            <td><?php echo number_format($expense['amount'], 2) . ' ' . $project['budget_unit']; ?></td>
                            <td><?php echo htmlspecialchars($expense['description']); ?></td>
                            <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                            <td>
                                <button class="delete-expense" data-id="<?php echo $expense['id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    
    <script>
        $(document).ready(function () {
            $("#update-budget-form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "../server/update-budget.php",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.trim() === "success") {
                            alert("Budget Updated Successfully!");
                            location.reload();
                        } else {
                            alert(response);
                        }
                    }
                });
            });
            
            $("#add-expense-form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "../server/add-expense.php",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.trim() === "success") {
                            alert("Expense Added Successfully!");
                            location.reload();
                        } else {
                            alert(response);
                        }
                    }
                });
            });
            
            $(".delete-expense").click(function () {
                let expenseId = $(this).data("id");
                if (confirm("Are you sure you want to delete this expense?")) {
                    $.ajax({
                        url: "../server/delete-expense.php",
                        type: "POST",
                        data: { expense_id: expenseId },
                        success: function (response) {
                            if (response.trim() === "success") {
                                alert("Expense Deleted!");
                                location.reload();
                            } else {
                                alert(response);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
