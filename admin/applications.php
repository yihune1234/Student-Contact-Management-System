<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';

if (isset($_POST['action'])) {
    $id = $_POST['app_id'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $success = "Application status updated successfully.";
    } catch (Exception $e) {
        $error = "Action failure: " . $e->getMessage();
    }
}

// Fetch applications with student names
$stmt = $pdo->query("SELECT a.*, s.full_name FROM applications a JOIN students s ON a.student_id = s.student_id ORDER BY a.created_at DESC");
$applications = $stmt->fetchAll();

$page_title = "Manage Applications - Admin SCMS";
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
        <nav class="flex-1 px-6 space-y-2 overflow-y-auto italic">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard Overview
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Student Database
            </a>
            <a href="applications.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Request Logs
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Manage Departments
            </a>
            <a href="export.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="download-cloud" class="w-5 h-5"></i>
                Export Archive
            </a>
        </nav>
        <div class="p-6 border-t border-white/5 mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                System Logout
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-primary-600 transition-all">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-base lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">REQUEST LOGS</h2>
            </div>
            
            <div class="flex items-center gap-2 px-4 py-2 bg-primary-50 dark:bg-primary-900/10 border border-primary-500/10 rounded-full">
                <span class="w-2 h-2 rounded-full bg-primary-600 animate-pulse"></span>
                <span class="text-[9px] font-black uppercase text-primary-600 tracking-widest italic">Monitoring Active</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 space-y-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <?php if ($success): ?>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-5 rounded-2xl italic font-bold text-xs flex items-center gap-4 animate-shake">
                        <i data-lucide="check" class="w-6 h-6"></i>
                        <p><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-6 lg:p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto -mx-6 lg:mx-0">
                        <div class="inline-block min-w-full align-middle px-6 lg:px-0">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800 italic">
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Subject Vector</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 hidden sm:table-cell">Proposing Entity</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Status Logic</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 text-right">Operational Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800 italic">
                                    <?php foreach ($applications as $app): ?>
                                    <tr class="group hover:bg-gray-50/30 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="py-8 sm:py-10 px-4 max-w-[200px] sm:max-w-xs">
                                            <p class="font-black text-gray-950 dark:text-white tracking-tight italic text-base sm:text-lg uppercase mb-2 truncate"><?php echo htmlspecialchars($app['subject']); ?></p>
                                            <p class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed line-clamp-2"><?php echo htmlspecialchars($app['content']); ?></p>
                                        </td>
                                        <td class="py-8 sm:py-10 px-4 hidden sm:table-cell">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 flex items-center justify-center font-black text-[10px] text-gray-400 shadow-sm">
                                                    <?php echo strtoupper(substr($app['full_name'], 0, 1)); ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-gray-950 dark:text-white text-xs truncate"><?php echo htmlspecialchars($app['full_name']); ?></p>
                                                    <p class="text-[9px] text-gray-500 tracking-tighter uppercase font-black truncate"><?php echo htmlspecialchars($app['student_id']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-8 sm:py-10 px-4">
                                            <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border
                                                <?php echo $app['status'] == 'pending' ? 'bg-orange-50 text-orange-600 border-orange-500/10 dark:bg-orange-900/20' : 
                                                           ($app['status'] == 'approved' ? 'bg-green-50 text-green-600 border-green-500/10 dark:bg-green-900/20' : 'bg-red-50 text-red-600 border-red-500/10 dark:bg-red-900/20'); ?>">
                                                <?php echo $app['status']; ?>
                                            </span>
                                        </td>
                                        <td class="py-8 sm:py-10 px-4 text-right">
                                            <form action="applications.php" method="POST" class="inline-flex gap-2">
                                                <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                                                <input type="hidden" name="action" value="update">
                                                
                                                <button name="status" value="approved" class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center hover:scale-110 active:scale-90 transition-all shadow-sm border border-green-500/10" title="Approve Logic">
                                                    <i data-lucide="check" class="w-5 h-5"></i>
                                                </button>
                                                <button name="status" value="rejected" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 flex items-center justify-center hover:scale-110 active:scale-90 transition-all shadow-sm border border-red-500/10" title="Reject Logic">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($applications)): ?>
                                        <tr>
                                            <td colspan="4" class="py-20 text-center text-gray-300 font-black uppercase tracking-widest text-[10px] italic">
                                                Zero request vectors detected in the nexus logs.
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
