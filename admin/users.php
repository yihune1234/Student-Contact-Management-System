<?php
require_once '../includes/config.php';

// Role Check - Only Admin can access User Management
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];
    $dept_id = !empty($_POST['department_id']) ? $_POST['department_id'] : null;

    if (empty($username) || empty($password) || empty($role_id)) {
        $error = "Implicit logic requires all primary credentials.";
    } else {
        try {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id, department_link_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_pass, $role_id, $dept_id]);
            $success = "Authorized node '{$username}' successfully synthesized.";
        } catch (PDOException $e) {
            $error = ($e->getCode() == 23000) ? "Identity conflict: Username already archived." : "Synthesis failure: " . $e->getMessage();
        }
    }
}

// Handle Status Toggle
if (isset($_GET['toggle_status']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $new_status = $_GET['toggle_status'] == '1' ? 1 : 0;
    try {
        $stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ? AND username != 'admin'");
        $stmt->execute([$new_status, $id]);
        $success = "Node status recalibrated.";
    } catch (PDOException $e) {
        $error = "recalibration failed.";
    }
}

// Fetch Master Data
$users = $pdo->query("SELECT u.*, r.role_name, d.department_name FROM users u 
                     JOIN roles r ON u.role_id = r.id 
                     LEFT JOIN departments d ON u.department_link_id = d.id 
                     ORDER BY u.id DESC")->fetchAll();

$roles = $pdo->query("SELECT * FROM roles ORDER BY role_name ASC")->fetchAll();
$departments = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll();

$page_title = "Access Control - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false, modalOpen: false }">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto italic">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">System</div>
        <nav class="flex-1 px-4 space-y-1 mb-8">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/20">
                <i data-lucide="user-settings" class="w-5 h-5"></i>
                Access Control
            </a>
            <a href="logs.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="terminal" class="w-5 h-5"></i>
                Activity Logs
            </a>
        </nav>
        <div class="p-6 border-t border-white/5 mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                Exit Protocol
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden italic">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">SECURITY PROTOCOLS</h2>
            </div>
            <button @click="modalOpen = true" class="px-6 py-3 bg-indigo-600 text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Synthesize Node
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-10">
            <div class="max-w-6xl mx-auto space-y-10">
                <?php if ($success): ?>
                    <div class="bg-indigo-50 border border-indigo-100 text-indigo-600 p-6 rounded-[2rem] font-bold text-sm italic flex items-center gap-4 animate-bounce">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-[2rem] font-bold text-sm italic flex items-center gap-4">
                        <i data-lucide="alert-octagon" class="w-6 h-6"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- User Database -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800">
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Node Identity</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Authorization Level</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Linked Interface</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Status</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800 italic">
                                <?php foreach ($users as $user): ?>
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                    <td class="py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 font-black group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                            </div>
                                            <p class="font-black text-gray-950 dark:text-white uppercase tracking-tighter"><?php echo htmlspecialchars($user['username']); ?></p>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest border border-indigo-100 bg-indigo-50 px-3 py-1 rounded-lg">
                                            <?php echo htmlspecialchars($user['role_name']); ?>
                                        </span>
                                    </td>
                                    <td class="py-6">
                                        <p class="text-xs font-bold text-gray-400 italic">
                                            <?php echo htmlspecialchars($user['department_name'] ?? 'Universal'); ?>
                                        </p>
                                    </td>
                                    <td class="py-6">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full <?php echo $user['is_active'] ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">
                                                <?php echo $user['is_active'] ? 'Active' : 'Offline'; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-6 text-right">
                                        <?php if ($user['username'] !== 'admin'): ?>
                                        <a href="users.php?toggle_status=<?php echo $user['is_active'] ? '0' : '1'; ?>&id=<?php echo $user['id']; ?>" 
                                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest <?php echo $user['is_active'] ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'; ?> transition-all hover:scale-105">
                                            <i data-lucide="<?php echo $user['is_active'] ? 'power-off' : 'power'; ?>" class="w-3 h-3"></i>
                                            <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Synthesis Modal -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/20 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak>
        <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden italic" @click.away="modalOpen = false">
            <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-2xl font-black text-gray-950 dark:text-white uppercase tracking-tighter">Synthesize New Node</h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-red-500"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="add_user" value="1">
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Identity Designation</label>
                    <input type="text" name="username" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Secret Key</label>
                    <input type="password" name="password" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Authorization</label>
                        <select name="role_id" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold appearance-none">
                            <?php foreach($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['role_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Interface Link</label>
                        <select name="department_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold appearance-none">
                            <option value="">Universal Access</option>
                            <?php foreach($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-5 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-3xl uppercase tracking-widest text-xs hover:scale-[1.02] transition-all shadow-xl mt-4 italic">
                    Initialize Authorization
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('lucide-ready', () => {
        lucide.createIcons();
    });
</script>
<?php include '../includes/footer.php'; ?>
