<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: students.php");
    exit;
}

$error = '';
$success = '';

// Fetch student
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: students.php");
    exit;
}

// Fetch departments
$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $department_id = $_POST['department_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    if (empty($full_name)) {
        $error = "Full name is required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE students SET full_name = ?, department_id = ?, phone = ?, email = ?, address = ? WHERE id = ?");
            $stmt->execute([$full_name, $department_id, $phone, $email, $address, $id]);
            $success = "Student record updated successfully!";
            
            // Re-fetch updated data
            $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute([$id]);
            $student = $stmt->fetch();
        } catch (Exception $e) {
            $error = "Error updating record: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Admin SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
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
                <h2>Edit Student Record</h2>
                <a href="students.php" class="btn btn-warning" style="width: auto;">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="table-container" style="padding: 2rem;">
                <form action="edit-student.php?id=<?php echo $id; ?>" method="POST">
                    <div class="form-group">
                        <label>Student ID (Cannot be changed)</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Full Name*</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="department_id">Department</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" <?php echo ($student['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['department_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: auto;">Update Student Info</button>
                    <a href="students.php" class="btn" style="width: auto; text-decoration: none; color: var(--text-muted);">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
