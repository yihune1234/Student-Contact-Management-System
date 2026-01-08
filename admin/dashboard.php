<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
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

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-gray-950/60 backdrop-blur-sm lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <nav class="flex-1 px-6 space-y-2 overflow-y-auto">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard Overview
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Student Database
            </a>
            <a href="applications.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Request Logs
                <?php if ($total_apps > 0): ?>
                    <span class="ml-auto bg-primary-600 text-[10px] px-2 py-1 rounded-full"><?php echo $total_apps; ?></span>
                <?php endif; ?>
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
        <div class="p-6 border-t border-white/5 mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                System Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-primary-600 transition-all">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div>
                    <h2 class="text-lg lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">System Status</h2>
                    <div class="flex items-center gap-2 mt-0.5 sm:mt-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-500 uppercase tracking-widest truncate max-w-[100px] sm:max-w-none">Server: Operational</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3 lg:gap-4 p-1.5 sm:p-2 pl-3 sm:pl-4 pr-4 sm:pr-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 max-w-[150px] sm:max-w-none">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black text-sm sm:text-base">A</div>
                <div class="text-left hidden sm:block">
                    <p class="text-sm font-black leading-none truncate">Administrator</p>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-1">Super User</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 space-y-8 sm:space-y-10">
            <div class="max-w-7xl mx-auto space-y-10">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 mb-4 sm:mb-6">
                            <i data-lucide="users" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                        </div>
                        <p class="text-gray-500 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-1 sm:mb-2">Total Enrollment</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_students; ?></p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 mb-4 sm:mb-6">
                            <i data-lucide="building" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                        </div>
                        <p class="text-gray-500 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-1 sm:mb-2">Departments</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_departments; ?></p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 mb-4 sm:mb-6">
                            <i data-lucide="file-text" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                        </div>
                        <p class="text-gray-500 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-1 sm:mb-2">Pending Logs</p>
                        <p class="text-3xl sm:text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_apps; ?></p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 mb-4 sm:mb-6">
                                <i data-lucide="shield-check" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                            </div>
                            <p class="text-gray-500 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-1 sm:mb-2">Security Status</p>
                            <p class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-950 dark:text-white">OPTIMAL</p>
                        </div>
                        <i data-lucide="activity" class="absolute -right-8 -bottom-8 w-32 h-32 sm:w-40 sm:h-40 text-gray-50 dark:text-gray-800/20 group-hover:scale-110 transition-transform"></i>
                    </div>
                </div>

                <!-- Recent Registrations Card -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 sm:mb-10 gap-4">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-gray-950 dark:text-white tracking-tight italic">RECENT REGISTRATIONS</h3>
                            <p class="text-gray-500 text-xs sm:text-sm mt-1 uppercase font-bold tracking-widest opacity-60">System Ingress Log (Latest 5)</p>
                        </div>
                        <a href="students.php" class="inline-flex items-center justify-center px-6 py-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-950 dark:text-white text-xs font-black rounded-xl transition-all border border-gray-100 dark:border-gray-800 uppercase tracking-widest">
                            View Database
                        </a>
                    </div>

                    <div class="overflow-x-auto -mx-6 sm:mx-0">
                        <div class="inline-block min-w-full align-middle px-6 sm:px-0">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800">
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Entity Identity</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 hidden md:table-cell">Faculty/Dept</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 hidden sm:table-cell">Contact Tunnel</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 text-right">Synchronization</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800 italic">
                                    <?php foreach ($latest_students as $student): ?>
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="py-6 px-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 font-black group-hover:bg-primary-600 group-hover:text-white transition-all overflow-hidden flex-shrink-0">
                                                    <?php if ($student['profile_photo']): ?>
                                                        <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-black text-gray-950 dark:text-white truncate"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                                    <p class="text-[10px] text-gray-500 font-bold tracking-tighter mt-0.5 uppercase">ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-6 px-4 hidden md:table-cell">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-50 dark:bg-gray-800 text-[9px] font-black uppercase text-gray-500 border border-gray-100 dark:border-gray-700">
                                                <?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?>
                                            </span>
                                        </td>
                                        <td class="py-6 px-4 hidden sm:table-cell">
                                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400 truncate max-w-[150px]"><?php echo htmlspecialchars($student['email']); ?></p>
                                        </td>
                                        <td class="py-6 px-4 text-right">
                                            <p class="text-xs font-black text-gray-950 dark:text-white"><?php echo date('M d, y', strtotime($student['created_at'])); ?></p>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5"><?php echo date('H:i', strtotime($student['created_at'])); ?></p>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($latest_students)): ?>
                                    <tr>
                                        <td colspan="4" class="py-20 text-center">
                                            <i data-lucide="inbox" class="w-12 h-12 text-gray-200 dark:text-gray-800 mx-auto mb-4"></i>
                                            <p class="text-gray-400 font-black uppercase tracking-[0.2em] text-[10px]">Zero Ingress Records Detected</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
