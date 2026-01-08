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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type']; // 'faculty', 'department', 'program'
    try {
        if ($type === 'faculty') {
            $name = $_POST['name'];
            $stmt = $pdo->prepare("INSERT INTO faculties (faculty_name) VALUES (?)");
            $stmt->execute([$name]);
        } elseif ($type === 'department') {
            $name = $_POST['name'];
            $f_id = $_POST['faculty_id'];
            $stmt = $pdo->prepare("INSERT INTO departments (faculty_id, department_name) VALUES (?, ?)");
            $stmt->execute([$f_id, $name]);
        } elseif ($type === 'program') {
            $name = $_POST['name'];
            $d_id = $_POST['department_id'];
            $deg = $_POST['degree_type'];
            $stmt = $pdo->prepare("INSERT INTO programs (department_id, program_name, degree_type) VALUES (?, ?, ?)");
            $stmt->execute([$d_id, $name, $deg]);
        }
        $success = "Structural node " . strtoupper($type) . " synthesized.";
    } catch (Exception $e) {
        $error = "Synthesis rejection: " . $e->getMessage();
    }
}

// Data Fetching
$faculties = $pdo->query("SELECT * FROM faculties ORDER BY faculty_name ASC")->fetchAll();
$departments = $pdo->query("SELECT d.*, f.faculty_name FROM departments d JOIN faculties f ON d.faculty_id = f.id ORDER BY f.faculty_name, d.department_name ASC")->fetchAll();
$programs = $pdo->query("SELECT p.*, d.department_name FROM programs p JOIN departments d ON p.department_id = d.id ORDER BY d.department_name, p.program_name ASC")->fetchAll();

$page_title = "Academic Structure - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false, tab: 'faculties' }">
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

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Academic & Location</div>
        <nav class="px-4 space-y-1 mb-8">
            <a href="academic.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/20">
                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                Aca. Structure
            </a>
            <a href="locations.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                Geo-Location
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
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">STRUCTURAL HIERARCHY</h2>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-10">
            <div class="max-w-6xl mx-auto space-y-10">
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-100 text-green-600 p-6 rounded-[2rem] font-bold text-sm flex items-center gap-4">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <!-- Tab Navigation -->
                <div class="flex gap-4 p-2 bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm w-fit">
                    <button @click="tab = 'faculties'" :class="tab === 'faculties' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:text-indigo-600'" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Faculties</button>
                    <button @click="tab = 'departments'" :class="tab === 'departments' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:text-indigo-600'" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Departments</button>
                    <button @click="tab = 'programs'" :class="tab === 'programs' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:text-indigo-600'" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Programs</button>
                </div>

                <!-- Faculties Panel -->
                <div x-show="tab === 'faculties'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Define New Faculty</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="faculty">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Faculty Designation</label>
                                    <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold">
                                </div>
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 transition-all">Synthesize Faculty</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800">
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Node Designation</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Synchronization</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php foreach($faculties as $f): ?>
                                    <tr class="group">
                                        <td class="py-5 font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($f['faculty_name']); ?></td>
                                        <td class="py-5 text-right">
                                            <button class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-red-500 transition-all">
                                                <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Departments Panel -->
                <div x-show="tab === 'departments'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Initialize Department Node</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="department">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Parent Faculty</label>
                                    <select name="faculty_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold appearance-none">
                                        <?php foreach($faculties as $f): ?>
                                            <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['faculty_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Dept. Designation</label>
                                    <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold">
                                </div>
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 transition-all">Synthesize Dept</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800">
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Department Node</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Parent Interface</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php foreach($departments as $d): ?>
                                    <tr class="group">
                                        <td class="py-5 font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($d['department_name']); ?></td>
                                        <td class="py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?php echo htmlspecialchars($d['faculty_name']); ?></td>
                                        <td class="py-5 text-right">
                                            <button class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-red-500 transition-all">
                                                <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Programs Panel -->
                <div x-show="tab === 'programs'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <!-- Similar structure for Programs... -->
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Initialize Curriculum Program</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="program">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Alignment Node (Dept)</label>
                                    <select name="department_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold appearance-none">
                                        <?php foreach($departments as $d): ?>
                                            <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['department_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Credential Logic (Type)</label>
                                    <select name="degree_type" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold appearance-none">
                                        <option value="Degree">Bachelor Degree</option>
                                        <option value="Masters">Masters Degree</option>
                                        <option value="PhD">Doctorate</option>
                                        <option value="Diploma">Diploma</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Program Designation</label>
                                    <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-indigo-600 font-bold">
                                </div>
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 transition-all">Synthesize Program</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800">
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Program Logic</th>
                                        <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Synchronization</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php foreach($programs as $p): ?>
                                    <tr class="group">
                                        <td class="py-5">
                                            <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($p['program_name']); ?></p>
                                            <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-tighter"><?php echo htmlspecialchars($p['degree_type'] . ' | ' . $p['department_name']); ?></p>
                                        </td>
                                        <td class="py-5 text-right">
                                            <button class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-red-500 transition-all">
                                                <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
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
