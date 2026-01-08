<?php
require_once '../includes/config.php';

// Role Check - Allow Admin, Registrar, Department Officer
$allowed_roles = ['admin', 'registrar', 'department officer'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.php");
    exit;
}

// 1. Basic Stats
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();

// 2. Status Breakdown
$active_students = $pdo->query("SELECT COUNT(*) FROM students s JOIN enrollment_statuses es ON s.enrollment_status_id = es.id WHERE es.status_name = 'Active'")->fetchColumn();
$graduated_students = $pdo->query("SELECT COUNT(*) FROM students s JOIN enrollment_statuses es ON s.enrollment_status_id = es.id WHERE es.status_name = 'Graduated'")->fetchColumn();

// 3. Data Completeness Stats
$missing_phone = $pdo->query("SELECT COUNT(*) FROM students WHERE phone IS NULL OR phone = ''")->fetchColumn();
$missing_email = $pdo->query("SELECT COUNT(*) FROM students WHERE email IS NULL OR email = ''")->fetchColumn();
$missing_emergency = $pdo->query("SELECT COUNT(*) FROM students s LEFT JOIN guardians g ON s.id = g.student_id WHERE g.id IS NULL")->fetchColumn();

// 4. Latest Applications (requests)
$total_apps = $pdo->query("SELECT COUNT(*) FROM update_requests WHERE status = 'Pending'")->fetchColumn();

// 5. Latest Students
$latest_students = $pdo->query("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();

$page_title = "Command Center - Nexus SCMS";
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Manage Students
            </a>
            <a href="requests.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="git-pull-request" class="w-5 h-5"></i>
                Update Requests
                <?php if ($total_apps > 0): ?>
                    <span class="ml-auto bg-primary-600 text-[10px] px-2 py-1 rounded-full"><?php echo $total_apps; ?></span>
                <?php endif; ?>
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

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">Communication</div>
        <nav class="px-4 space-y-1 mb-8">
            <a href="messages.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                Bulk Messaging
            </a>
            <a href="templates.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-template" class="w-5 h-5"></i>
                Templates
            </a>
        </nav>

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">System</div>
        <nav class="px-4 space-y-1">
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
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

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-primary-600 transition-all">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div>
                    <h2 class="text-lg lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">Operation Matrix</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest"><?php echo strtoupper($_SESSION['role']); ?>: ACTIVE</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4 p-2 pl-4 pr-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                <div class="text-left hidden sm:block">
                    <p class="text-sm font-black leading-none"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-1"><?php echo htmlspecialchars($_SESSION['role']); ?></p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-10">
                <!-- Status Hub -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4">Total Population</p>
                        <p class="text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_students; ?></p>
                        <div class="mt-4 flex items-center gap-4 text-[10px] font-bold">
                            <span class="text-green-500"><?php echo $active_students; ?> Active</span>
                            <span class="text-blue-500"><?php echo $graduated_students; ?> Alum</span>
                        </div>
                        <i data-lucide="users" class="absolute -right-4 -bottom-4 w-24 h-24 text-gray-50 dark:text-gray-800/20 group-hover:scale-110 transition-transform"></i>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4">Contact Integrity</p>
                        <p class="text-4xl font-black text-gray-950 dark:text-white"><?php echo round(($total_students > 0) ? (($total_students-($missing_phone+$missing_email)/2)/$total_students)*100 : 100); ?>%</p>
                        <div class="mt-4 text-[10px] font-bold text-red-500">
                            <?php echo $missing_phone; ?> Missing Phones
                        </div>
                        <i data-lucide="phone" class="absolute -right-4 -bottom-4 w-24 h-24 text-gray-50 dark:text-gray-800/20 group-hover:scale-110 transition-transform"></i>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4">Guardian Linked</p>
                        <p class="text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_students - $missing_emergency; ?></p>
                        <div class="mt-4 text-[10px] font-bold text-orange-500">
                            <?php echo $missing_emergency; ?> Unshielded
                        </div>
                        <i data-lucide="shield-alert" class="absolute -right-4 -bottom-4 w-24 h-24 text-gray-50 dark:text-gray-800/20 group-hover:scale-110 transition-transform"></i>
                    </div>

                    <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4">Pending Requests</p>
                        <p class="text-4xl font-black text-gray-950 dark:text-white"><?php echo $total_apps; ?></p>
                        <div class="mt-4 text-[10px] font-bold text-primary-500">
                            Action Required
                        </div>
                        <i data-lucide="git-pull-request" class="absolute -right-4 -bottom-4 w-24 h-24 text-gray-50 dark:text-gray-800/20 group-hover:scale-110 transition-transform"></i>
                    </div>
                </div>

                <!-- Recent Registrations Card -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-2xl font-black text-gray-950 dark:text-white tracking-tight italic uppercase">Recent Ingress</h3>
                            <p class="text-gray-500 text-xs mt-1 uppercase font-bold tracking-widest opacity-60">Latest Student Registrations</p>
                        </div>
                        <a href="students.php" class="px-6 py-3 bg-gray-950 dark:bg-white text-white dark:text-gray-950 text-[10px] font-black rounded-xl uppercase tracking-widest italic">Full Records</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800">
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Entity</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Department</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Contact</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 text-right">Synchronization</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800 italic">
                                <?php foreach ($latest_students as $student): ?>
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                    <td class="py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 font-black group-hover:bg-primary-600 group-hover:text-white transition-all overflow-hidden">
                                                <?php if ($student['profile_photo']): ?>
                                                    <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                                <p class="text-[10px] text-gray-500 font-bold mt-0.5 uppercase">ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-50 dark:bg-gray-800 text-[9px] font-black uppercase text-gray-500 border border-gray-100 dark:border-gray-700">
                                            <?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?>
                                        </span>
                                    </td>
                                    <td class="py-6">
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($student['email']); ?></p>
                                        <p class="text-[10px] text-gray-400 font-bold mt-0.5"><?php echo htmlspecialchars($student['phone']); ?></p>
                                    </td>
                                    <td class="py-6 text-right">
                                        <p class="text-xs font-black text-gray-950 dark:text-white"><?php echo date('M d, Y', strtotime($student['created_at'])); ?></p>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5"><?php echo date('H:i', strtotime($student['created_at'])); ?></p>
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
