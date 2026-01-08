<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE s.student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

$page_title = "Entity Profile - Student SCMS";
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
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profile Node
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
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
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">ENTITY DOSSIER</h2>
            <a href="edit-profile.php" class="px-6 py-2.5 bg-primary-600 text-white text-xs font-black rounded-xl shadow-lg hover:bg-primary-700 transition-all flex items-center gap-2 uppercase tracking-widest">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                Revise Data
            </a>
        </header>

        <div class="p-10 max-w-5xl mx-auto w-full">
            <div class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden relative">
                <!-- Cover Decoration -->
                <div class="h-48 bg-gradient-to-br from-primary-600 to-indigo-900 relative">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                </div>
                
                <div class="px-12 pb-12">
                    <div class="relative flex flex-col md:flex-row items-center md:items-end -mt-24 gap-8 mb-12">
                        <div class="w-48 h-48 rounded-[3.5rem] bg-white dark:bg-gray-800 p-2 shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <?php if ($student['profile_photo']): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover rounded-[3rem]">
                            <?php else: ?>
                                <div class="w-full h-full rounded-[3rem] bg-primary-50 dark:bg-primary-900/10 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                    <i data-lucide="user" class="w-20 h-20"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center md:text-left pb-4">
                            <h1 class="text-4xl font-black text-gray-950 dark:text-white tracking-tighter uppercase italic"><?php echo htmlspecialchars($student['full_name']); ?></h1>
                            <p class="text-primary-600 font-black uppercase tracking-[0.3em] text-xs mt-2 italic">Student Unit #<?php echo htmlspecialchars($student['student_id']); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 italic">
                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-transparent hover:border-gray-200 transition-all">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Organizational Node</p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 group-hover:text-primary-600 transition-colors shadow-sm">
                                    <i data-lucide="building-2" class="w-6 h-6"></i>
                                </div>
                                <p class="font-bold text-gray-950 dark:text-white leading-tight"><?php echo htmlspecialchars($student['department_name'] ?? 'Unaligned'); ?></p>
                            </div>
                        </div>

                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-transparent hover:border-gray-200 transition-all">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Remote Port (Email)</p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 transition-colors shadow-sm">
                                    <i data-lucide="mail" class="w-6 h-6"></i>
                                </div>
                                <p class="font-bold text-gray-950 dark:text-white leading-tight truncate"><?php echo htmlspecialchars($student['email'] ?: 'NULL'); ?></p>
                            </div>
                        </div>

                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border border-transparent hover:border-gray-200 transition-all">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Signal Node (Phone)</p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-400 transition-colors shadow-sm">
                                    <i data-lucide="phone" class="w-6 h-6"></i>
                                </div>
                                <p class="font-bold text-gray-950 dark:text-white leading-tight"><?php echo htmlspecialchars($student['phone'] ?: 'NULL'); ?></p>
                            </div>
                        </div>

                        <div class="lg:col-span-2 p-8 rounded-[2.5rem] bg-gray-950 text-white relative overflow-hidden group">
                             <div class="absolute inset-0 bg-primary-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                             <p class="text-[10px] font-black uppercase tracking-widest text-primary-500 mb-4">Physical Residential Vector</p>
                             <div class="flex gap-6">
                                 <i data-lucide="map-pin" class="w-8 h-8 text-primary-600 flex-shrink-0"></i>
                                 <p class="text-xl font-bold italic leading-relaxed text-gray-300">
                                     <?php echo nl2br(htmlspecialchars($student['address'] ?: 'No spatial coordinates provided in the registry.')); ?>
                                 </p>
                             </div>
                        </div>

                        <div class="p-8 rounded-[2.5rem] border-2 border-dashed border-gray-100 dark:border-gray-800 flex flex-col justify-center items-center text-center">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Since Initiation</p>
                            <p class="text-3xl font-black text-gray-950 dark:text-white tracking-tighter"><?php echo date('M Y', strtotime($student['created_at'])); ?></p>
                            <p class="text-[10px] font-bold text-primary-600 uppercase tracking-[0.2em] mt-2">Active Entity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
