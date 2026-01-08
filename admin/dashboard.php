<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Stats
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$total_apps = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'")->fetchColumn();

// Latest Students
$latest_students = $pdo->query("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();

$page_title = "Admin Dashboard - SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
        </div>
        <nav class="flex-1 px-6 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard Overview
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Student Database
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Manage Departments
            </a>
            <a href="export.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="download-cloud" class="w-5 h-5"></i>
                Export Records
            </a>
        </nav>
        <div class="p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                System Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
        <!-- Top Bar -->
        <header class="h-20 bg-white dark:bg-gray-900 border-bottom border-gray-100 dark:border-gray-800 px-10 flex items-center justify-between sticky top-0 z-20">
            <div>
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">System Status</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Server: Operational</span>
                </div>
            </div>
            <div class="flex items-center gap-4 p-2 pl-4 pr-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black">A</div>
                <div class="text-left">
                    <p class="text-sm font-black leading-none">Administrator</p>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-1">Super User</p>
                </div>
            </div>
        </header>

        <div class="p-10 max-w-7xl mx-auto">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 mb-6">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </div>
                    <p class="text-gray-500 text-xs font-black uppercase tracking-widest mb-2">Total Enrollment</p>
                    <p class="text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_students; ?></p>
                </div>
                <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 mb-6">
                        <i data-lucide="building" class="w-7 h-7"></i>
                    </div>
                    <p class="text-gray-500 text-xs font-black uppercase tracking-widest mb-2">Departments</p>
                    <p class="text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_departments; ?></p>
                </div>
                <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all col-span-1 md:col-span-2 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 mb-6">
                            <i data-lucide="database" class="w-7 h-7"></i>
                        </div>
                        <p class="text-gray-500 text-xs font-black uppercase tracking-widest mb-2">System Health</p>
                        <p class="text-4xl font-black text-gray-950 dark:text-white">OPTIMAL</p>
                    </div>
                    <i data-lucide="activity" class="absolute -right-8 -bottom-8 w-40 h-40 text-gray-50 dark:text-gray-800/20 group-hover:scale-110 transition-transform"></i>
                </div>
            </div>

            <!-- Recent Students Table -->
            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-gray-950 dark:text-white tracking-tight italic">RECENT REGISTRATIONS</h3>
                        <p class="text-gray-500 text-sm mt-1">Showing the latest 5 students to join the system.</p>
                    </div>
                    <a href="students.php" class="px-6 py-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-950 dark:text-white text-sm font-bold rounded-xl transition-all border border-gray-100 dark:border-gray-700">
                        View Database
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-800">
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Student Identity</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Faculty/Dept</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Electronic Mail</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 text-right">Date Applied</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            <?php foreach ($latest_students as $student): ?>
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="py-6 px-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 font-black group-hover:bg-primary-600 group-hover:text-white transition-all">
                                            <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                            <p class="text-xs text-gray-500 font-bold tracking-tighter mt-0.5">ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-[10px] font-black uppercase text-gray-600 dark:text-gray-400">
                                        <?php echo htmlspecialchars($student['department_name'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td class="py-6 px-4 font-bold text-sm text-gray-600 dark:text-gray-400 italic">
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </td>
                                <td class="py-6 px-4 text-right">
                                    <p class="text-sm font-black text-gray-950 dark:text-white"><?php echo date('M d, Y', strtotime($student['created_at'])); ?></p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-1"><?php echo date('H:i A', strtotime($student['created_at'])); ?></p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($latest_students)): ?>
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                                    <p class="text-gray-400 font-black uppercase tracking-widest text-xs">No Records Found</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
