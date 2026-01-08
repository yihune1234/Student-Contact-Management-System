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

// Handle Additions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_template'])) {
    $title = $_POST['title'];
    $channel = $_POST['channel'];
    $content = $_POST['content'];

    try {
        $stmt = $pdo->prepare("INSERT INTO message_templates (title, channel, content) VALUES (?, ?, ?)");
        $stmt->execute([$title, $channel, $content]);
        $success = "Template configuration saved.";
    } catch (Exception $e) {
        $error = "Logic rejection: " . $e->getMessage();
    }
}

$templates = $pdo->query("SELECT * FROM message_templates ORDER BY created_at DESC")->fetchAll();

$page_title = "Message Blueprints - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto italic">
        <!-- Sidebar Content (Consistent with others) -->
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">Core Modules</div>
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

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">Communication</div>
        <nav class="px-4 space-y-1 mb-8">
            <a href="messages.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                Broadcast Nexus
            </a>
            <a href="templates.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-amber-600 text-white shadow-lg shadow-amber-500/20">
                <i data-lucide="layout-template" class="w-5 h-5"></i>
                Blueprints
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden italic">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">MESSAGE BLUEPRINTS</h2>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-10">
            <div class="max-w-6xl mx-auto space-y-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-10">Define New Blueprint</h4>
                        <form method="POST" class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Blueprint Title</label>
                                <input type="text" name="title" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Logic Channel</label>
                                <select name="channel" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl font-bold appearance-none">
                                    <option value="Portal">Internal Portal</option>
                                    <option value="SMS">SMS Protocol</option>
                                    <option value="Email">Electronic Mail</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Composition</label>
                                <textarea name="content" rows="5" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl font-bold"></textarea>
                            </div>
                            <button type="submit" name="save_template" class="w-full py-4 bg-amber-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg">Save Blueprint</button>
                        </form>
                    </div>

                    <div class="space-y-6">
                        <?php foreach($templates as $t): ?>
                        <div class="bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h5 class="font-black text-gray-950 dark:text-white uppercase tracking-tight"><?php echo htmlspecialchars($t['title']); ?></h5>
                                    <span class="text-[9px] font-bold text-amber-600 uppercase"><?php echo $t['channel']; ?> Logic</span>
                                </div>
                                <button class="text-gray-300 hover:text-red-500 transition-all"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </div>
                            <p class="text-xs font-bold text-gray-500 italic"><?php echo nl2br(htmlspecialchars($t['content'])); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
