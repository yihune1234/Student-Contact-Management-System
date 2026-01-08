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

<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
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
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-primary-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto italic">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-black rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400 transition-all uppercase tracking-widest">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all">
                <i data-lucide="user" class="w-5 h-5"></i>
                My Profile
            </a>
            <a href="apply.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Applications
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security Node
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 italic">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Logout Protocol
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Nav -->
        <header class="h-20 bg-white/70 dark:bg-gray-900/70 glass sticky top-0 z-10 px-6 lg:px-10 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-primary-600 transition-all">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-base sm:text-lg font-black text-gray-950 dark:text-white uppercase tracking-tight italic">Student Portal</h2>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4 max-w-[200px] sm:max-w-none">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black truncate max-w-[150px] italic"><?php echo htmlspecialchars($student['full_name']); ?></p>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mt-0.5">ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-2xl overflow-hidden bg-primary-100 dark:bg-primary-900/10 flex items-center justify-center text-primary-700 dark:text-primary-300 font-black border border-gray-100 dark:border-gray-700 shadow-sm flex-shrink-0">
                    <?php if ($student['profile_photo']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 space-y-8 sm:space-y-10">
            <div class="max-w-7xl mx-auto space-y-10 sm:space-y-12">
                <!-- Welcome Section -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-2">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-950 dark:text-white tracking-tighter italic leading-none">Welcome back,<br><span class="text-primary-600 uppercase"><?php echo explode(' ', htmlspecialchars($student['full_name']))[0]; ?>!</span> 👋</h1>
                        <p class="text-gray-500 font-bold italic tracking-tight uppercase text-[10px] sm:text-xs opacity-60">Architectural Node Status: Nominal</p>
                    </div>
                </div>

                <!-- Status Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                            <i data-lucide="id-card" class="w-6 h-6"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-black uppercase tracking-[0.2em] mb-2">Student Identity</p>
                        <p class="text-2xl sm:text-3xl font-black text-gray-950 dark:text-white italic tracking-tighter truncate"><?php echo htmlspecialchars($student['student_id']); ?></p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-black uppercase tracking-[0.2em] mb-2">Institutional Node</p>
                        <p class="text-2xl sm:text-3xl font-black text-gray-950 dark:text-white italic tracking-tighter truncate"><?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?></p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all group lg:col-span-1 sm:col-span-2">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <?php 
                        $app_stats = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
                        $app_stats->execute([$student_id]);
                        $app_count = $app_stats->fetchColumn();
                        ?>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-black uppercase tracking-[0.2em] mb-2">Signal Logs Sent</p>
                        <p class="text-2xl sm:text-3xl font-black text-gray-950 dark:text-white italic tracking-tighter"><?php echo $app_count; ?></p>
                    </div>
                </div>

                <!-- Quick Commands Matrix -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-6 sm:p-10 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-600/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
                    
                    <h3 class="text-xl sm:text-2xl font-black mb-8 sm:mb-10 flex items-center gap-3 italic uppercase tracking-tighter">
                        <i data-lucide="zap" class="text-yellow-500 fill-yellow-500 w-6 h-6"></i>
                        Quick Command Matrix
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 relative z-10">
                        <a href="profile.php" class="group p-6 sm:p-8 rounded-3xl bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-primary-600 border border-transparent hover:border-primary-500/20 transition-all duration-300 shadow-sm hover:shadow-2xl hover:-translate-y-1">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 group-hover:text-primary-600 transition-colors mb-6 shadow-sm">
                                <i data-lucide="eye" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-black text-gray-950 dark:text-white group-hover:dark:text-white uppercase tracking-widest text-xs mb-2">Visualize Dossier</h4>
                            <p class="text-[10px] text-gray-400 font-bold italic">Extract detailed identity attributes.</p>
                        </a>

                        <a href="edit-profile.php" class="group p-6 sm:p-8 rounded-3xl bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-orange-600 border border-transparent hover:border-orange-500/20 transition-all duration-300 shadow-sm hover:shadow-2xl hover:-translate-y-1">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 group-hover:text-orange-600 transition-colors mb-6 shadow-sm">
                                <i data-lucide="edit-3" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-black text-gray-950 dark:text-white group-hover:dark:text-white uppercase tracking-widest text-xs mb-2">Modify Ports</h4>
                            <p class="text-[10px] text-gray-400 font-bold italic">Update contact & synchronization nodes.</p>
                        </a>

                        <a href="change-password.php" class="group p-6 sm:p-8 rounded-3xl bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-indigo-600 border border-transparent hover:border-indigo-500/20 transition-all duration-300 shadow-sm hover:shadow-2xl hover:-translate-y-1 sm:col-span-2 lg:col-span-1">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 group-hover:text-indigo-600 transition-colors mb-6 shadow-sm">
                                <i data-lucide="lock" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-black text-gray-950 dark:text-white group-hover:dark:text-white uppercase tracking-widest text-xs mb-2">Rotate Secret</h4>
                            <p class="text-[10px] text-gray-400 font-bold italic">Re-initialize security encryption keys.</p>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
