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
    <title>My Profile - Student SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Student Portal</h1>
            <nav>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="profile.php" class="nav-link active">My Profile</a>
                <a href="edit-profile.php" class="nav-link">Edit Contact</a>
                <a href="change-password.php" class="nav-link">Change Password</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>My Profile</h2>
                <a href="edit-profile.php" class="btn btn-warning btn-sm" style="width: auto;">Edit Contact Info</a>
            </div>

            <div class="table-container" style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: var(--text-muted);">Student ID</div>
                    <div><?php echo htmlspecialchars($student['student_id']); ?></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: var(--text-muted);">Full Name</div>
                    <div><?php echo htmlspecialchars($student['full_name']); ?></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: var(--text-muted);">Department</div>
                    <div><?php echo htmlspecialchars($student['department_name'] ?? 'Not Assigned'); ?></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: var(--text-muted);">Phone Number</div>
                    <div><?php echo htmlspecialchars($student['phone'] ?: 'Not Provided'); ?></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: var(--text-muted);">Email Address</div>
                    <div><?php echo htmlspecialchars($student['email'] ?: 'Not Provided'); ?></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                    <div style="font-weight: 600; color: var(--text-muted);">Home Address</div>
                    <div><?php echo nl2br(htmlspecialchars($student['address'] ?: 'Not Provided')); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
