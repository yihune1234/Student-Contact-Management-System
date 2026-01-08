<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Handle Add
if (isset($_POST['add_dept'])) {
    $name = $_POST['dept_name'];
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO departments (department_name) VALUES (?)");
            $stmt->execute([$name]);
            $success = "New logic node (department) registered.";
        } catch (Exception $e) {
            $error = "Registration error: " . $e->getMessage();
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $pdo->prepare("DELETE FROM departments WHERE id = ?")->execute([$id]);
        $success = "Department node decommissioned.";
    } catch (Exception $e) {
        $error = "Decommission failure: Existing entity dependencies detected.";
    }
}

$departments = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll();

$page_title = "Manage Departments - Admin SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-indigo-400">
                <i data-lucide="shield-check" class="w-8 h-8 text-primary-400"></i>
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
                Database
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Departments
            </a>
            <a href="export.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="download-cloud" class="w-5 h-5"></i>
                Export Records
            </a>
        </nav>
        <div class="p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all mt-auto tracking-widest uppercase text-xs">
                <i data-lucide="power" class="w-4 h-4"></i>
                Terminate Session
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
        <header class="h-20 bg-white dark:bg-gray-900 border-bottom border-gray-100 dark:border-gray-800 px-10 flex items-center justify-between sticky top-0 z-20">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">DEPARTMENTAL NODES</h2>
        </header>

        <div class="p-10 max-w-7xl mx-auto">
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900 text-green-600 p-5 rounded-2xl mb-8 font-bold text-sm">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900 text-red-600 p-5 rounded-2xl mb-8 font-bold text-sm">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="grid lg:grid-cols-3 gap-10">
                <!-- Registration Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm sticky top-28">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-6 flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            New Allocation
                        </h3>
                        <form action="departments.php" method="POST" class="space-y-6">
                            <div>
                                <label for="dept_name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Node Label (Name)</label>
                                <input type="text" name="dept_name" id="dept_name" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" required placeholder="e.g. Quantum Computing">
                            </div>
                            <button type="submit" name="add_dept" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-2xl shadow-xl shadow-primary-500/20 transition-all">
                                REGISTER NODE
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Database View -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800">
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Registry Index</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Node Specification</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 text-right">Entity Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                <?php foreach ($departments as $index => $dept): ?>
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                    <td class="py-6 px-4">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center font-black text-xs text-gray-400 group-hover:bg-gray-950 dark:group-hover:bg-white group-hover:text-white dark:group-hover:text-gray-950 transition-all">
                                            #<?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>
                                        </div>
                                    </td>
                                    <td class="py-6 px-4">
                                        <p class="font-black text-gray-950 dark:text-white tracking-tight uppercase"><?php echo htmlspecialchars($dept['department_name']); ?></p>
                                    </td>
                                    <td class="py-6 px-4 text-right">
                                        <a href="departments.php?delete=<?php echo $dept['id']; ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-500 hover:text-white dark:bg-red-900/10 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all" onclick="return confirm('Execute permanent decommissioning of this node?')">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            Purge
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($departments)): ?>
                                <tr>
                                    <td colspan="3" class="py-20 text-center text-gray-300 font-black uppercase tracking-widest text-xs italic">
                                        No departmental nodes registered in registry.
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

<?php include '../includes/footer.php'; ?>
