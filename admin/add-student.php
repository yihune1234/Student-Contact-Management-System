<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Fetch departments for the dropdown
$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $department_id = $_POST['department_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($student_id) || empty($full_name) || empty($username) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username already taken.";
            } else {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, department_id, phone, email, address) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$student_id, $full_name, $department_id, $phone, $email, $address]);

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role, student_id) VALUES (?, ?, 'student', ?)");
                $stmt->execute([$username, $hashed_password, $student_id]);

                $pdo->commit();
                $success = "Student added successfully!";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Admin SCMS</title>
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
                <h2>Add New Student</h2>
                <a href="students.php" class="btn btn-warning" style="width: auto;">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="table-container" style="padding: 2rem;">
                <form action="add-student.php" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="student_id">Student ID*</label>
                            <input type="text" name="student_id" id="student_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="full_name">Full Name*</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="department_id">Department</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2"></textarea>
                    </div>

                    <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid var(--border-color);">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="username">Username*</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Initial Password*</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: auto;">Create Student Account</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
