<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE s.student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Student Portal</h1>
            <nav>
                <a href="dashboard.php" class="nav-link active">Dashboard</a>
                <a href="profile.php" class="nav-link">My Profile</a>
                <a href="edit-profile.php" class="nav-link">Edit Contact</a>
                <a href="change-password.php" class="nav-link">Change Password</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Welcome, <?php echo htmlspecialchars($student['full_name']); ?>!</h2>
                <div class="badge badge-student">Student Account</div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Student ID</h3>
                    <div class="value"><?php echo htmlspecialchars($student['student_id']); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Department</h3>
                    <div class="value"><?php echo htmlspecialchars($student['department_name'] ?? 'Not Assigned'); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Account Status</h3>
                    <div class="value" style="color: var(--success);">Active</div>
                </div>
            </div>

            <div class="table-container" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.5rem;">Quick Actions</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <a href="profile.php" class="btn btn-primary" style="text-decoration: none;">View Full Profile</a>
                    <a href="edit-profile.php" class="btn btn-warning" style="text-decoration: none;">Update Contact Info</a>
                    <a href="change-password.php" class="btn btn-warning" style="text-decoration: none; background: #6366f1;">Change Password</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
