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

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profile Node
            </a>
            <a href="apply.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Applications
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security
            </a>
        </nav>
        <div class="p-4 border-t border-gray-50 dark:border-gray-700">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-gray-950 flex flex-col">
        <header class="h-20 bg-white/70 dark:bg-gray-900/70 glass sticky top-0 z-10 px-10 flex items-center justify-between">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">APPLICATION MATRIX</h2>
        </header>

        <div class="p-10 max-w-5xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- New Application Form -->
            <div>
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/5 rounded-full blur-2xl -mr-16 -mt-16"></div>
                    
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-primary-500 mb-8 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        New Request Node
                    </h3>

                    <?php if ($success): ?>
                        <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 text-green-600 p-5 rounded-2xl mb-8 font-bold text-xs italic">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 text-red-600 p-5 rounded-2xl mb-8 font-bold text-xs italic">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="apply.php" method="POST" class="space-y-6 relative z-10 italic">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Request Subject</label>
                            <input type="text" name="subject" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" required placeholder="Nature of request">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Detailed Log (Content)</label>
                            <textarea name="content" rows="6" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" required placeholder="Provide analytical details..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-5 bg-primary-600 text-white font-black rounded-3xl shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-widest text-xs">
                            Transmit Logic
                        </button>
                    </form>
                </div>
            </div>

            <!-- Existing Applications -->
            <div class="space-y-6">
                <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-400 px-4">TRANSMISSION LOGS</h3>
                <?php if (empty($applications)): ?>
                    <div class="bg-white/50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-800 rounded-[2.5rem] p-10 text-center">
                        <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-4"></i>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-400 italic">No nodes detected in log.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                        <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-lg font-black text-gray-950 dark:text-white tracking-tight italic"><?php echo htmlspecialchars($app['subject']); ?></h4>
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                    <?php echo $app['status'] == 'pending' ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : 
                                               ($app['status'] == 'approved' ? 'bg-green-50 text-green-600 dark:bg-green-900/20' : 'bg-red-50 text-red-600 dark:bg-red-900/20'); ?>">
                                    <?php echo $app['status']; ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 italic line-clamp-2"><?php echo htmlspecialchars($app['content']); ?></p>
                            <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <span>ID: #<?php echo str_pad($app['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                <span><?php echo date('M d, Y', strtotime($app['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
