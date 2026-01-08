<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['download'])) {
    $filename = "student_records_" . date('Y-m-d') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    
    fputcsv($output, array('Student ID', 'Full Name', 'Department', 'Phone', 'Email', 'Address', 'Date Registered'));
    
    $stmt = $pdo->query("SELECT s.student_id, s.full_name, d.department_name, s.phone, s.email, s.address, s.created_at 
                        FROM students s 
                        LEFT JOIN departments d ON s.department_id = d.id 
                        ORDER BY s.student_id ASC");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data - Admin SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Admin Panel</h1>
            <nav>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="students.php" class="nav-link">Manage Students</a>
                <a href="departments.php" class="nav-link">Departments</a>
                <a href="export.php" class="nav-link active">Export Data</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Export Student Data</h2>
            </div>

            <div class="table-container" style="padding: 3rem; text-align: center;">
                <div style="margin-bottom: 2rem;">
                    <img src="https://cdn-icons-png.flaticon.com/512/2306/2306022.png" alt="CSV Export" style="width: 100px; opacity: 0.5;">
                </div>
                <h3>Download Student Records</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Click the button below to download the complete student contact list in CSV format.</p>
                <a href="export.php?download=1" class="btn btn-primary" style="width: auto; padding: 1rem 2.5rem; font-size: 1.125rem;">Download CSV File</a>
            </div>
        </div>
    </div>
</body>
</html>
