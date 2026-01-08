<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Handle Add
if (isset($_POST['add_dept'])) {
    $name = $_POST['dept_name'];
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO departments (department_name) VALUES (?)");
            $stmt->execute([$name]);
            $success = "Department added successfully!";
        } catch (Exception $e) {
            $error = "Error adding department: " . $e->getMessage();
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $pdo->prepare("DELETE FROM departments WHERE id = ?")->execute([$id]);
        $success = "Department deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting department: It might be assigned to students.";
    }
}

$departments = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments - SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Admin Panel</h1>
            <nav>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="students.php" class="nav-link">Manage Students</a>
                <a href="departments.php" class="nav-link active">Departments</a>
                <a href="export.php" class="nav-link">Export Data</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Departments</h2>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                <div class="table-container" style="padding: 1.5rem; height: fit-content;">
                    <h3>Add Department</h3>
                    <form action="departments.php" method="POST" style="margin-top: 1rem;">
                        <div class="form-group">
                            <label for="dept_name">Department Name</label>
                            <input type="text" name="dept_name" id="dept_name" class="form-control" required>
                        </div>
                        <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $index => $dept): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($dept['department_name']); ?></td>
                                <td class="action-btns">
                                    <a href="departments.php?delete=<?php echo $dept['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this department?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
