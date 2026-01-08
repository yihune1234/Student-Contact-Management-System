<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Stats
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();

// Latest Students
$latest_students = $pdo->query("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Admin Panel</h1>
            <nav>
                <a href="dashboard.php" class="nav-link active">Dashboard</a>
                <a href="students.php" class="nav-link">Manage Students</a>
                <a href="departments.php" class="nav-link">Departments</a>
                <a href="export.php" class="nav-link">Export Data</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>System Overview</h2>
                <div class="badge badge-admin">Logged in as Admin</div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <div class="value"><?php echo $total_students; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Departments</h3>
                    <div class="value"><?php echo $total_departments; ?></div>
                </div>
                <div class="stat-card">
                    <h3>System Status</h3>
                    <div class="value" style="color: var(--success);">Online</div>
                </div>
            </div>

            <div class="table-container">
                <div style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 1.125rem;">Recently Registered Students</h3>
                    <a href="students.php" style="color: var(--primary-color); font-size: 0.875rem; text-decoration: none; font-weight: 500;">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latest_students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['department_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($latest_students)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No students registered yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
