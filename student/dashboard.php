<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE s.student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

$page_title = "Dashboard - Student Portal";
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="user" class="w-5 h-5"></i>
                My Profile
            </a>
            <a href="apply.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Applications
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security
            </a>
        </nav>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-gray-900">
        <!-- Top Nav -->
        <header class="h-16 bg-white/70 dark:bg-gray-800/70 glass sticky top-0 z-10 px-8 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Student Dashboard</h2>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-bold truncate max-w-[150px]"><?php echo htmlspecialchars($student['full_name']); ?></p>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($student['student_id']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-full overflow-hidden bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold border border-gray-100 dark:border-gray-700">
                    <?php if ($student['profile_photo']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="p-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Welcome back, <?php echo explode(' ', htmlspecialchars($student['full_name']))[0]; ?>! 👋</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Here's an overview of your student profile and contact information.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 mb-4">
                        <i data-lucide="id-card" class="w-6 h-6"></i>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Student ID</p>
                    <p class="text-2xl font-bold mt-1"><?php echo htmlspecialchars($student['student_id']); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 mb-4">
                        <i data-lucide="building-2" class="w-6 h-6"></i>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Department</p>
                    <p class="text-2xl font-bold mt-1 truncate"><?php echo htmlspecialchars($student['department_name'] ?? 'Not Assigned'); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 mb-4">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <?php 
                    $app_stats = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
                    $app_stats->execute([$student_id]);
                    $app_count = $app_stats->fetchColumn();
                    ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Applications Sent</p>
                    <p class="text-2xl font-bold mt-1"><?php echo $app_count; ?></p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <i data-lucide="zap" class="text-yellow-500"></i>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="profile.php" class="group flex items-center gap-4 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all duration-200">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/30 flex items-center justify-center group-hover:text-primary-600 transition-colors">
                            <i data-lucide="eye" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">View Profile</p>
                            <p class="text-xs text-gray-500">Detailed information</p>
                        </div>
                    </a>
                    <a href="edit-profile.php" class="group flex items-center gap-4 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-all duration-200">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/30 flex items-center justify-center group-hover:text-orange-600 transition-colors">
                            <i data-lucide="edit-3" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">Update Contact</p>
                            <p class="text-xs text-gray-500">Phone, Email, Address</p>
                        </div>
                    </a>
                    <a href="change-password.php" class="group flex items-center gap-4 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all duration-200">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 flex items-center justify-center group-hover:text-indigo-600 transition-colors">
                            <i data-lucide="key" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">Change Password</p>
                            <p class="text-xs text-gray-500">Secure your account</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
