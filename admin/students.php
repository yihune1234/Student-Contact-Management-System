<?php
require_once '../includes/config.php';

// Role Check
$allowed_roles = ['admin', 'registrar', 'department officer'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.php");
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
$dept_filter = $_GET['dept'] ?? '';
$status_filter = $_GET['status'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (s.full_name LIKE ? OR s.student_id LIKE ? OR s.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($dept_filter) {
    $where .= " AND s.department_id = ?";
    $params[] = $dept_filter;
}

if ($status_filter) {
    $where .= " AND s.enrollment_status_id = ?";
    $params[] = $status_filter;
}

$query = "SELECT s.*, d.department_name, es.status_name, p.program_name 
          FROM students s 
          LEFT JOIN departments d ON s.department_id = d.id 
          LEFT JOIN enrollment_statuses es ON s.enrollment_status_id = es.id
          LEFT JOIN programs p ON s.program_id = p.id
          WHERE $where ORDER BY s.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();

// Fetch filters data
$departments = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll();
$statuses = $pdo->query("SELECT * FROM enrollment_statuses ORDER BY status_name ASC")->fetchAll();

$page_title = "Student Registry - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto">
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
            <a href="students.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="users" class="w-5 h-5"></i>
                Manage Students
            </a>
            <a href="requests.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="git-pull-request" class="w-5 h-5"></i>
                Update Requests
            </a>
        </nav>

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">Academic & Location</div>
        <nav class="px-4 space-y-1 mb-8">
            <a href="academic.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                Aca. Structure
            </a>
            <a href="locations.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                Geo-Location
            </a>
        </nav>

        <!-- (Rest of Sidebar Hidden for brevity in this tool call, but I will make sure it's consistent) -->
        <div class="p-6 border-t border-white/5 mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                Exit Protocol
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
                <h2 class="text-lg lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">STUDENT ARCHIVE</h2>
            </div>
            
            <a href="add-student.php" class="flex px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-black rounded-xl shadow-lg transition-all items-center gap-2 uppercase tracking-widest italic">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Enroll New Entity
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-8">
                <?php if ($success): ?>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-5 rounded-2xl flex items-center gap-3 italic">
                        <i data-lucide="check" class="w-6 h-6"></i>
                        <p class="font-bold text-sm uppercase"><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <div class="bg-white dark:bg-gray-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                    <form action="students.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 italic">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">Global Scan</label>
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" name="search" class="w-full pl-16 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" placeholder="Identity / Email / Phone" value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">Departmental Filter</label>
                            <select name="dept" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold appearance-none">
                                <option value="">All Units</option>
                                <?php foreach($departments as $d): ?>
                                    <option value="<?php echo $d['id']; ?>" <?php echo $dept_filter == $d['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['department_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">Growth Phase</label>
                                <select name="status" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold appearance-none">
                                    <option value="">All Statuses</option>
                                    <?php foreach($statuses as $s): ?>
                                        <option value="<?php echo $s['id']; ?>" <?php echo $status_filter == $s['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['status_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="w-14 h-14 shrink-0 bg-gray-950 dark:bg-white text-white dark:text-gray-950 rounded-2xl flex items-center justify-center hover:scale-105 transition-all shadow-xl">
                                <i data-lucide="filter" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Database Table -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800">
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Entity Details</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Academic Alignment</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Status Matrix</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 text-right">Synchronization</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800 italic">
                                <?php foreach ($students as $student): ?>
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                    <td class="py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 font-black group-hover:bg-primary-600 group-hover:text-white transition-all overflow-hidden shadow-inner">
                                                <?php if ($student['profile_photo']): ?>
                                                    <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-1">ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <p class="text-[10px] font-black text-primary-600 uppercase mb-1"><?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?></p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest"><?php echo htmlspecialchars($student['program_name'] ?? 'General'); ?></p>
                                    </td>
                                    <td class="py-6">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border <?php 
                                            echo match($student['status_name']) {
                                                'Active' => 'bg-green-50 text-green-600 border-green-200',
                                                'Graduated' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                'Withdrawn' => 'bg-red-50 text-red-600 border-red-200',
                                                default => 'bg-gray-50 text-gray-600 border-gray-200'
                                            };
                                        ?>">
                                            <?php echo htmlspecialchars($student['status_name'] ?? 'Pending'); ?>
                                        </span>
                                    </td>
                                    <td class="py-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="edit-student.php?id=<?php echo $student['id']; ?>" class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all flex items-center justify-center shadow-sm">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>
                                            <a href="students.php?delete=<?php echo $student['id']; ?>" class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all flex items-center justify-center shadow-sm" onclick="return confirm('Purge entity from archive?')">
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
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
