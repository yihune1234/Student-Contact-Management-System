<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
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

$page_title = "Edit Profile - Student Portal";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:flex flex-col">
        <div class="p-6">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-bold text-xl">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50">
                <i data-lucide="user" class="w-5 h-5"></i>
                My Profile
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security
            </a>
        </nav>
        <!-- Logout... -->
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-gray-900">
        <header class="h-16 bg-white/70 dark:bg-gray-800/70 glass sticky top-0 z-10 px-8 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Update Contact Details</h2>
        </header>

        <div class="p-8 max-w-2xl">
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-5 rounded-2xl mb-8 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    <p class="font-bold text-sm"><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form action="edit-profile.php" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-60">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Student ID (Locked)</label>
                            <input type="text" class="w-full px-5 py-4 bg-gray-100 dark:bg-gray-700 border-none rounded-2xl cursor-not-allowed" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Department (Locked)</label>
                            <input type="text" class="w-full px-5 py-4 bg-gray-100 dark:bg-gray-700 border-none rounded-2xl cursor-not-allowed" value="<?php echo htmlspecialchars($student['department_name'] ?? 'Not Assigned'); ?>" disabled>
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-700">

                    <div>
                        <label for="phone" class="block text-sm font-bold mb-2 ml-1">Phone Number</label>
                        <div class="relative">
                            <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="text" name="phone" id="phone" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700/50 border border-transparent focus:border-primary-500 rounded-2xl transition-all outline-none" value="<?php echo htmlspecialchars($student['phone']); ?>">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold mb-2 ml-1">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="email" name="email" id="email" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700/50 border border-transparent focus:border-primary-500 rounded-2xl transition-all outline-none" value="<?php echo htmlspecialchars($student['email']); ?>">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-bold mb-2 ml-1">Home Address</label>
                        <div class="relative">
                            <i data-lucide="map-pin" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                            <textarea name="address" id="address" rows="4" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700/50 border border-transparent focus:border-primary-500 rounded-2xl transition-all outline-none"><?php echo htmlspecialchars($student['address']); ?></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-4 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-2xl shadow-xl shadow-primary-500/20 transition-all">
                            Save Changes
                        </button>
                        <a href="profile.php" class="px-8 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
