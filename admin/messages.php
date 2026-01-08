<?php
require_once '../includes/config.php';

// Role Check
$allowed_roles = ['admin', 'registrar'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';

// Handle Message Dispatch
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dispatch'])) {
    $channel = $_POST['channel']; // SMS, Email, Portal
    $target = $_POST['target']; // All, Department, Section
    $content = $_POST['content'];
    $sender_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();
        
        // Define target student set
        $sql = "SELECT id FROM students WHERE 1=1";
        $params = [];
        if ($target === 'department' && !empty($_POST['department_id'])) {
            $sql .= " AND department_id = ?";
            $params[] = $_POST['department_id'];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $receivers = $stmt->fetchAll();

        // Log the messages
        $log_stmt = $pdo->prepare("INSERT INTO message_logs (sender_id, receiver_id, channel, content) VALUES (?, ?, ?, ?)");
        foreach ($receivers as $r) {
            $log_stmt->execute([$sender_id, $r['id'], $channel, $content]);
        }

        $pdo->commit();
        $success = "Broadcast dispatched to " . count($receivers) . " entities via $channel.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Dispatch failure: " . $e->getMessage();
    }
}

// Fetch Master Data
$departments = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll();
$recent_logs = $pdo->query("SELECT l.*, s.full_name as rname FROM message_logs l JOIN students s ON l.receiver_id = s.id ORDER BY l.sent_at DESC LIMIT 10")->fetchAll();

$page_title = "Communication Nexus - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto italic">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Core Modules</div>
        <nav class="flex-1 px-4 space-y-1 mb-8">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Manage Students
            </a>
        </nav>

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Communication</div>
        <nav class="px-4 space-y-1 mb-8">
            <a href="messages.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-amber-600 text-white shadow-lg shadow-amber-500/20">
                <i data-lucide="send" class="w-5 h-5"></i>
                Broadcast Nexus
            </a>
            <a href="templates.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="file-type-2" class="w-5 h-5"></i>
                Templates
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden italic">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">COMMUNICATION NEXUS</h2>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-10">
            <div class="max-w-6xl mx-auto space-y-10">
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-100 text-green-600 p-6 rounded-[2rem] font-bold text-sm flex items-center gap-4">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Broadcast Terminal -->
                    <div class="bg-white dark:bg-gray-900 rounded-[3rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full bg-amber-600"></div>
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-10">Broadcast Terminal</h4>
                        
                        <form method="POST" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Logic Channel</label>
                                    <select name="channel" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl font-bold appearance-none">
                                        <option value="Portal">Internal Portal</option>
                                        <option value="SMS">Global SMS Protocol</option>
                                        <option value="Email">Electronic Mail</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Target Cluster</label>
                                    <select name="target" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl font-bold appearance-none">
                                        <option value="all">Universal (All Entities)</option>
                                        <option value="department">Departmental Nodes</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Message Payload</label>
                                <textarea name="content" rows="6" required class="w-full px-8 py-6 bg-gray-50 dark:bg-gray-800 border-none rounded-[2rem] outline-none focus:ring-2 focus:ring-amber-600 font-bold placeholder-gray-400" placeholder="Initialize data payload for broadcast..."></textarea>
                            </div>

                            <button type="submit" name="dispatch" class="w-full py-5 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-3xl shadow-2xl hover:scale-105 transition-all uppercase tracking-[0.2em] text-xs flex items-center justify-center gap-3">
                                <i data-lucide="send" class="w-5 h-5"></i>
                                Execute Broadcast
                            </button>
                        </form>
                    </div>

                    <!-- Dispatch History -->
                    <div class="bg-white dark:bg-gray-900 rounded-[3rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-10">Operational History</h4>
                        
                        <div class="space-y-6">
                            <?php foreach($recent_logs as $log): ?>
                            <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 hover:border-amber-500/30 transition-all">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-tighter mb-1"><?php echo $log['channel']; ?> Logic</p>
                                        <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($log['rname']); ?></p>
                                    </div>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase"><?php echo date('H:i | d M', strtotime($log['sent_at'])); ?></p>
                                </div>
                                <p class="text-xs font-bold text-gray-500 line-clamp-2 italic"><?php echo htmlspecialchars($log['content']); ?></p>
                            </div>
                            <?php endforeach; ?>

                            <?php if (empty($recent_logs)): ?>
                            <div class="text-center py-20 italic">
                                <i data-lucide="history" class="w-12 h-12 mx-auto text-gray-200 mb-4 font-thin"></i>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No previous dispatch logs.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
