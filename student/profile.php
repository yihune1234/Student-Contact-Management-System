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

$page_title = "Entity Profile - Student SCMS";
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all uppercase tracking-widest">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-black rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20 uppercase tracking-widest">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profile Node
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
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 italic mt-auto">
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
                <h2 class="text-base lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">ENTITY DOSSIER</h2>
            </div>
            
            <a href="edit-profile.php" class="flex items-center gap-2 px-6 py-2.5 bg-primary-600 text-white text-[10px] sm:text-xs font-black rounded-xl shadow-lg hover:bg-primary-700 transition-all uppercase tracking-widest italic">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Revise Data</span>
                <span class="sm:hidden">Revise</span>
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
            <div class="max-w-5xl mx-auto w-full">
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] sm:rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden relative">
                    <!-- Cover Decoration -->
                    <div class="h-32 sm:h-48 bg-gradient-to-br from-primary-600 to-indigo-900 relative">
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                    </div>
                    
                    <div class="px-6 sm:px-12 pb-12">
                        <div class="relative flex flex-col items-center sm:items-end -mt-16 sm:-mt-24 gap-6 sm:gap-8 mb-12">
                            <div class="w-32 h-32 sm:w-48 sm:h-48 rounded-[2.5rem] sm:rounded-[3.5rem] bg-white dark:bg-gray-800 p-2 shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <?php if ($student['profile_photo']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover rounded-[2rem] sm:rounded-[3rem]">
                                <?php else: ?>
                                    <div class="w-full h-full rounded-[2rem] sm:rounded-[3rem] bg-primary-50 dark:bg-primary-900/10 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                        <i data-lucide="user" class="w-12 h-12 sm:w-20 sm:h-20"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-center sm:text-left sm:pb-4">
                                <h1 class="text-2xl sm:text-4xl font-black text-gray-950 dark:text-white tracking-tighter uppercase italic"><?php echo htmlspecialchars($student['full_name']); ?></h1>
                                <p class="text-primary-600 font-black uppercase tracking-[0.3em] text-[10px] sm:text-xs mt-2 italic opacity-80">Student Unit #<?php echo htmlspecialchars($student['student_id']); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 italic">
                            <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-primary-500/20 transition-all shadow-sm">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-4">Organizational Node</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 shadow-inner">
                                        <i data-lucide="building-2" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-sm font-black text-gray-950 dark:text-white leading-tight"><?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?></p>
                                </div>
                            </div>

                            <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-primary-500/20 transition-all shadow-sm">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-4">Remote Port (Email)</p>
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 shadow-inner">
                                        <i data-lucide="mail" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-sm font-black text-gray-950 dark:text-white leading-tight truncate"><?php echo htmlspecialchars($student['email'] ?: 'NULL'); ?></p>
                                </div>
                            </div>

                            <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-primary-500/20 transition-all shadow-sm">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-4">Signal Node (Phone)</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 shadow-inner">
                                        <i data-lucide="phone" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-sm font-black text-gray-950 dark:text-white leading-tight"><?php echo htmlspecialchars($student['phone'] ?: 'NULL'); ?></p>
                                </div>
                            </div>

                            <div class="lg:col-span-2 p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] bg-gray-950 text-white relative overflow-hidden group">
                                 <div class="absolute inset-0 bg-primary-600 opacity-5 transition-opacity"></div>
                                 <p class="text-[9px] font-black uppercase tracking-widest text-primary-500 mb-4 opacity-60">Physical Residential Vector</p>
                                 <div class="flex gap-4 sm:gap-6 relative z-10">
                                     <i data-lucide="map-pin" class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600 flex-shrink-0"></i>
                                     <p class="text-lg sm:text-xl font-bold italic leading-relaxed text-gray-300">
                                         <?php echo nl2br(htmlspecialchars($student['address'] ?: 'No spatial coordinates provided in the registry.')); ?>
                                     </p>
                                 </div>
                            </div>

                            <div class="p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border-2 border-dashed border-gray-100 dark:border-gray-800 flex flex-col justify-center items-center text-center italic shadow-sm bg-white dark:bg-gray-900">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2">Initiate Pulse</p>
                                <p class="text-2xl sm:text-3xl font-black text-gray-950 dark:text-white tracking-tighter"><?php echo date('M Y', strtotime($student['created_at'])); ?></p>
                                <p class="text-[9px] font-black text-primary-600 uppercase tracking-[0.3em] mt-3 bg-primary-600/10 px-3 py-1 rounded-full">Active Entity</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
