<?php
include '../server/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        mysqli_begin_transaction($conn);

        // Budget conversion logic
        $conversion_rates = [
            'thousand' => 1000,
            'lakh' => 100000,
            'crore' => 10000000
        ];
        
        $budget_amount = (float)$_POST['budget_amount'];
        $budget_unit = mysqli_real_escape_string($conn, $_POST['budget_unit']);
        $converted_budget = $budget_amount * $conversion_rates[$budget_unit];

        // Insert project
        $stmt = mysqli_prepare($conn, 
            "INSERT INTO Projects (project_name, director_name, genre, language, budget) 
            VALUES (?, ?, ?, ?, ?)");
        
        mysqli_stmt_bind_param($stmt, 'ssssd', 
            $_POST['project_name'],
            $_POST['director_name'],
            $_POST['genre'],
            $_POST['language'],
            $converted_budget
        );
        mysqli_stmt_execute($stmt);
        $project_id = mysqli_insert_id($conn);

        // Handle cast members
        if (!empty($_POST['cast_name'])) {
            $cast_stmt = mysqli_prepare($conn,
                "INSERT INTO Cast (project_id, cast_name, cast_email) 
                VALUES (?, ?, ?)");
            
            foreach ($_POST['cast_name'] as $index => $name) {
                $email = $_POST['cast_email'][$index] ?? '';
                mysqli_stmt_bind_param($cast_stmt, 'iss', $project_id, $name, $email);
                mysqli_stmt_execute($cast_stmt);
            }
        }

        // Handle schedule
        $schedule_stmt = mysqli_prepare($conn,
            "INSERT INTO Schedule 
            (project_id, pre_start, pre_end, prod_start, prod_end, post_start, post_end)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        mysqli_stmt_bind_param($schedule_stmt, 'issssss', $project_id,
            $_POST['pre_production_start_date'],
            $_POST['pre_production_end_date'],
            $_POST['production_start_date'],
            $_POST['production_end_date'],
            $_POST['post_production_start_date'],
            $_POST['post_production_end_date']
        );
        mysqli_stmt_execute($schedule_stmt);

        // File upload handling
        $document_paths = [];
        $upload_types = ['script', 'screenplay'];
        
        foreach ($upload_types as $type) {
            if ($_FILES[$type]['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "../uploads/$type/";
                !is_dir($upload_dir) && mkdir($upload_dir, 0755, true);
                
                $file_name = uniqid() . '_' . basename($_FILES[$type]['name']);
                $target_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES[$type]['tmp_name'], $target_path)) {
                    $document_paths[$type] = $target_path;
                }
            }
        }

        // Insert documents
        $doc_stmt = mysqli_prepare($conn,
            "INSERT INTO Documents (project_id, script_path, screenplay_path)
            VALUES (?, ?, ?)");
        
        mysqli_stmt_bind_param($doc_stmt, 'iss', $project_id,
            $document_paths['script'] ?? '',
            $document_paths['screenplay'] ?? ''
        );
        mysqli_stmt_execute($doc_stmt);

        mysqli_commit($conn);
        header('Location: ../pages/dashboard.php?success=Project added successfully');
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log("Error: " . $e->getMessage());
        header('Location: ../pages/add-project.php?error=Error: ' . $e->getMessage());
        exit;
    }
}