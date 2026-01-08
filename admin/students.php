<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
        $success = "Student record purged from system.";
    } catch (Exception $e) {
        $error = "Critical error during deletion: " . $e->getMessage();
    }
}

// Search & Filter
$search = $_GET['search'] ?? '';
$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (full_name LIKE ? OR student_id LIKE ? OR email LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
}

$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE $where ORDER BY s.created_at DESC");
$stmt->execute($params);
$students = $stmt->fetchAll();

$page_title = "Student Database - Admin SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar (Same as dashboard) -->
    <aside class="w-72 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
        </div>
        <nav class="flex-1 px-6 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard Overview
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="users" class="w-5 h-5"></i>
                Student Database
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Manage Departments
            </a>
            <a href="export.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="download-cloud" class="w-5 h-5"></i>
                Export Records
            </a>
        </nav>
        <div class="p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
        <header class="h-20 bg-white dark:bg-gray-900 border-bottom border-gray-100 dark:border-gray-800 px-10 flex items-center justify-between sticky top-0 z-20">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">STUDENT ARCHIVE</h2>
            <a href="add-student.php" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-black rounded-xl shadow-lg transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Enroll New Student
            </a>
        </header>

        <div class="p-10 max-w-7xl mx-auto">
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-5 rounded-2xl mb-8 flex items-center gap-3">
                    <i data-lucide="check" class="w-6 h-6"></i>
                    <p class="font-bold text-sm text-green-800 dark:text-green-300"><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <!-- Search & Filter Bar -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm mb-10">
                <form action="students.php" method="GET" class="flex gap-4">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" name="search" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-medium" placeholder="Query by name, unique ID, or electronic mail..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <button type="submit" class="px-10 py-4 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-2xl hover:scale-105 transition-all">
                        Search Database
                    </button>
                    <a href="students.php" class="px-6 py-4 bg-gray-100 dark:bg-gray-800 text-gray-400 font-bold rounded-2xl hover:bg-gray-200 flex items-center justify-center transition-all">
                        <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                    </a>
                </form>
            </div>

            <!-- Database Table -->
            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-800">
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Entity Identity</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Departmental Node</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4">Contact Logic</th>
                                <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 text-right">Operational Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            <?php foreach ($students as $student): ?>
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="py-6 px-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 font-black group-hover:bg-primary-600 group-hover:text-white transition-all shadow-sm">
                                            <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                            <p class="text-xs text-gray-500 font-bold tracking-tighter mt-0.5"><?php echo htmlspecialchars($student['student_id']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary-50 dark:bg-primary-900/10 text-[10px] font-black uppercase text-primary-600 dark:text-primary-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
                                        <?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?>
                                    </span>
                                </td>
                                <td class="py-6 px-4">
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                            <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <?php echo htmlspecialchars($student['email']); ?>
                                        </p>
                                        <p class="text-xs font-bold text-gray-500 flex items-center gap-2 italic">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <?php echo htmlspecialchars($student['phone']); ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="py-6 px-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="edit-student.php?id=<?php echo $student['id']; ?>" class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center hover:scale-110 transition-transform">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <a href="students.php?delete=<?php echo $student['id']; ?>" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 flex items-center justify-center hover:scale-110 transition-transform" onclick="return confirm('Execute permanent record deletion?')">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
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

<?php include '../includes/footer.php'; ?>
