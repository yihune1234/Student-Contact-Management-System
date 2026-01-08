<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
        $success = "Student record deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting student: " . $e->getMessage();
    }
}

// Search & Filter
$search = $_GET['search'] ?? '';
$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (full_name LIKE ? OR student_id LIKE ? OR email LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
}

$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE $where ORDER BY s.created_at DESC");
$stmt->execute($params);
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Admin Panel</h1>
            <nav>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="students.php" class="nav-link active">Manage Students</a>
                <a href="departments.php" class="nav-link">Departments</a>
                <a href="export.php" class="nav-link">Export Data</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Manage Students</h2>
                <a href="add-student.php" class="btn btn-primary" style="width: auto;">Add New Student</a>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="table-container">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                    <form action="students.php" method="GET" style="display: flex; gap: 1rem;">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, ID or email..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary" style="width: auto;">Search</button>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['department_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td class="action-btns">
                                <a href="edit-student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="students.php?delete=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No student records found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
