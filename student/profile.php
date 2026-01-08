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

$page_title = "My Profile - Student Portal";
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
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400">
                <i data-lucide="user" class="w-5 h-5"></i>
                My Profile
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50">
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
        <header class="h-16 bg-white/70 dark:bg-gray-800/70 glass sticky top-0 z-10 px-8 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Profile Details</h2>
            <a href="edit-profile.php" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                Edit Profile
            </a>
        </header>

        <div class="p-8 max-w-4xl">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Cover Area -->
                <div class="h-32 bg-gradient-to-r from-primary-500 to-purple-500"></div>
                
                <!-- Avatar & Basic Info -->
                <div class="px-8 pb-8">
                    <div class="relative flex justify-between items-end -mt-12 mb-8">
                        <div class="w-24 h-24 rounded-[2rem] bg-white dark:bg-gray-800 p-2 shadow-lg border border-gray-100 dark:border-gray-700">
                            <div class="w-full h-full rounded-[1.5rem] bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 text-3xl font-black">
                                <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-10">
                        <h1 class="text-3xl font-black text-gray-900 dark:text-white"><?php echo htmlspecialchars($student['full_name']); ?></h1>
                        <p class="text-gray-500 font-medium">Student ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Academic Department</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-800 dark:text-gray-200"><?php echo htmlspecialchars($student['department_name'] ?? 'Not Assigned'); ?></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Electronic Mail</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-800 dark:text-gray-200"><?php echo htmlspecialchars($student['email'] ?: 'Not Provided'); ?></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Phone Number</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-800 dark:text-gray-200"><?php echo htmlspecialchars($student['phone'] ?: 'Not Provided'); ?></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Joining Date</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600">
                                    <i data-lucide="calendar" class="w-5 h-5"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-800 dark:text-gray-200"><?php echo date('F d, Y', strtotime($student['created_at'])); ?></p>
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Residential Address</p>
                            <div class="flex gap-3">
                                <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mt-1"></i>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed italic">
                                    <?php echo nl2br(htmlspecialchars($student['address'] ?: 'No address information found on record.')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
