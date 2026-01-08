<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    try {
        $stmt = $pdo->prepare("UPDATE students SET phone = ?, email = ?, address = ? WHERE student_id = ?");
        $stmt->execute([$phone, $email, $address, $student_id]);
        $success = "Contact information updated successfully!";
    } catch (Exception $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE s.student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Student SCMS</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <div class="sidebar">
            <h1>Student Portal</h1>
            <nav>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="profile.php" class="nav-link">My Profile</a>
                <a href="edit-profile.php" class="nav-link active">Edit Contact</a>
                <a href="change-password.php" class="nav-link">Change Password</a>
                <a href="logout.php" class="nav-link">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Update Contact Info</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="table-container" style="padding: 2rem;">
                <form action="edit-profile.php" method="POST">
                    <div class="form-group">
                        <label>Student ID (Locked)</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Full Name (Locked)</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['full_name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: auto;">Save Changes</button>
                    <a href="profile.php" class="btn" style="width: auto; text-decoration: none; color: var(--text-muted);">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
