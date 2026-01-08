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

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
        </div>
        <nav class="flex-1 px-6 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Students
            </a>
            <a href="applications.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Applications
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Departments
            </a>
        </nav>
        <div class="p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all mt-auto">
                <i data-lucide="power" class="w-5 h-5"></i>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-10 flex items-center justify-between sticky top-0 z-20">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">REQUEST LOGS</h2>
        </header>

        <div class="p-10 max-w-7xl mx-auto">
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900 text-green-600 p-5 rounded-2xl mb-8 font-bold text-sm">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-800">
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Subject Node</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Proposing Entity</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Status Logic</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 text-right">Operational Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            <?php foreach ($applications as $app): ?>
                            <tr class="group hover:bg-gray-50/30 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="py-10 px-4 max-w-xs">
                                    <p class="font-black text-gray-950 dark:text-white tracking-tight italic text-lg uppercase mb-2"><?php echo htmlspecialchars($app['subject']); ?></p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed line-clamp-2"><?php echo htmlspecialchars($app['content']); ?></p>
                                </td>
                                <td class="py-10 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center font-black text-xs text-gray-400">
                                            <?php echo strtoupper(substr($app['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-950 dark:text-white"><?php echo htmlspecialchars($app['full_name']); ?></p>
                                            <p class="text-[10px] text-gray-500 tracking-tighter uppercase font-black"><?php echo htmlspecialchars($app['student_id']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-10 px-4">
                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                        <?php echo $app['status'] == 'pending' ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : 
                                                   ($app['status'] == 'approved' ? 'bg-green-50 text-green-600 dark:bg-green-900/20' : 'bg-red-50 text-red-600 dark:bg-red-900/20'); ?>">
                                        <?php echo $app['status']; ?>
                                    </span>
                                </td>
                                <td class="py-10 px-4 text-right">
                                    <form action="applications.php" method="POST" class="inline-flex gap-2">
                                        <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                                        <input type="hidden" name="action" value="update">
                                        
                                        <button name="status" value="approved" class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center hover:scale-110 transition-transform shadow-sm" title="Approve Logic">
                                            <i data-lucide="check" class="w-5 h-5"></i>
                                        </button>
                                        <button name="status" value="rejected" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 flex items-center justify-center hover:scale-110 transition-transform shadow-sm" title="Reject Logic">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($applications)): ?>
                                <tr>
                                    <td colspan="4" class="py-20 text-center text-gray-300 font-black uppercase tracking-widest text-xs italic">
                                        No request nodes detected in the architectural logs.
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
