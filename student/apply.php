<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $content = $_POST['content'];

    if (empty($subject) || empty($content)) {
        $error = "Subject and content nodes are required for transmission.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO applications (student_id, subject, content) VALUES (?, ?, ?)");
            $stmt->execute([$student_id, $subject, $content]);
            $success = "Application logic successfully transmitted to the central matrix.";
        } catch (Exception $e) {
            $error = "Transmission failure: " . $e->getMessage();
        }
    }
}

// Fetch existing applications
$stmt = $pdo->prepare("SELECT * FROM applications WHERE student_id = ? ORDER BY created_at DESC");
$stmt->execute([$student_id]);
$applications = $stmt->fetchAll();

$page_title = "Apply - Student Portal";
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
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-primary-600 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto italic">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all uppercase tracking-widest">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all uppercase tracking-widest">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profile Node
            </a>
            <a href="apply.php" class="flex items-center gap-3 px-4 py-3 text-sm font-black rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20 uppercase tracking-widest">
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
                <h2 class="text-base lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">APPLICATION MATRIX</h2>
            </div>
            
            <div class="h-10 w-10 sm:h-auto sm:w-auto px-0 sm:px-4 py-0 sm:py-2 bg-primary-600/10 border border-primary-500/10 rounded-xl flex items-center justify-center text-primary-600">
                <i data-lucide="send" class="w-5 h-5 sm:mr-2"></i>
                <span class="hidden sm:inline text-[10px] font-black uppercase tracking-widest italic">Node Active</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- New Application Form -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-400 px-2 italic">Ingress Vector</h3>
                    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-6 sm:p-10 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/5 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-primary-500/10 transition-colors"></div>
                        
                        <?php if ($success): ?>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 text-green-600 dark:text-green-400 p-5 rounded-2xl mb-8 font-bold text-xs italic flex items-center gap-4">
                                <i data-lucide="check" class="w-6 h-6"></i>
                                <p><?php echo $success; ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-400 p-5 rounded-2xl mb-8 font-bold text-xs italic flex items-center gap-4">
                                <i data-lucide="shield-alert" class="w-6 h-6"></i>
                                <p><?php echo $error; ?></p>
                            </div>
                        <?php endif; ?>

                        <form action="apply.php" method="POST" class="space-y-8 relative z-10 italic">
                            <div class="group">
                                <label class="block text-[9px] font-black uppercase text-gray-400 mb-3 ml-2 group-focus-within:text-primary-600 transition-colors tracking-widest">Request Subject</label>
                                <div class="relative">
                                    <i data-lucide="tag" class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300 group-focus-within:text-primary-600 transition-colors"></i>
                                    <input type="text" name="subject" class="w-full pl-16 pr-6 py-5 bg-gray-50 dark:bg-gray-800/50 border border-transparent focus:bg-white dark:focus:bg-gray-800 rounded-[2rem] outline-none focus:ring-2 focus:ring-primary-600/20 transition-all font-black" required placeholder="Nature of transmission...">
                                </div>
                            </div>
                            <div class="group">
                                <label class="block text-[9px] font-black uppercase text-gray-400 mb-3 ml-2 group-focus-within:text-primary-600 transition-colors tracking-widest">Detailed Log (Content)</label>
                                <textarea name="content" rows="6" class="w-full px-8 py-6 bg-gray-50 dark:bg-gray-800/50 border border-transparent focus:bg-white dark:focus:bg-gray-800 rounded-[2.5rem] outline-none focus:ring-2 focus:ring-primary-600/20 transition-all font-bold placeholder-gray-400 text-sm" required placeholder="Provide analytical details for processing..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-6 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-[0.3em] text-[10px] sm:text-xs flex items-center justify-center gap-3 group relative overflow-hidden">
                                <span class="relative z-10">Transmit Signal Node</span>
                                <i data-lucide="zap" class="relative z-10 w-4 h-4 group-hover:fill-current group-hover:scale-125 transition-all"></i>
                                <div class="absolute inset-0 bg-primary-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Existing Applications -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-400 px-2 italic">Transmission Archives</h3>
                    <div class="space-y-4">
                        <?php if (empty($applications)): ?>
                            <div class="bg-white/50 dark:bg-gray-900/20 border border-dashed border-gray-200 dark:border-gray-800 rounded-[3rem] p-12 text-center">
                                <i data-lucide="inbox" class="w-12 h-12 text-gray-200 dark:text-gray-800 mx-auto mb-4"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">No historical nodes detected.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <div class="bg-white dark:bg-gray-900 rounded-[2rem] p-6 sm:p-8 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group transition-all hover:shadow-xl hover:-translate-y-1">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                                        <div class="min-w-0">
                                            <h4 class="text-base sm:text-lg font-black text-gray-950 dark:text-white tracking-tight italic uppercase truncate max-w-[180px] sm:max-w-xs"><?php echo htmlspecialchars($app['subject']); ?></h4>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1 italic">Log ID: #<?php echo str_pad($app['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                        </div>
                                        <span class="inline-flex px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border
                                            <?php echo $app['status'] == 'pending' ? 'bg-orange-50 text-orange-600 border-orange-500/10 dark:bg-orange-900/20' : 
                                                       ($app['status'] == 'approved' ? 'bg-green-50 text-green-600 border-green-500/10 dark:bg-green-900/20' : 'bg-red-50 text-red-600 border-red-500/10 dark:bg-red-900/20'); ?>">
                                            <?php echo $app['status']; ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 italic line-clamp-2 leading-relaxed"><?php echo htmlspecialchars($app['content']); ?></p>
                                    <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-gray-400 pt-4 border-t border-gray-50 dark:border-gray-800">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            <span><?php echo date('M d, Y', strtotime($app['created_at'])); ?></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            <span><?php echo date('H:i', strtotime($app['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
