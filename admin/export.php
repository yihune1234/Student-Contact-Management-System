<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['download'])) {
    $filename = "SCMS_ARCHIVE_" . date('Y-m-d_His') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'SERIAL_ID', 'FULL_NAME', 'DEPT', 'PHONE', 'EMAIL', 'ADDRESS', 'TIMESTAMP'));
    $stmt = $pdo->query("SELECT s.id, s.student_id, s.full_name, d.department_name, s.phone, s.email, s.address, s.created_at FROM students s LEFT JOIN departments d ON s.department_id = d.id ORDER BY s.id ASC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { fputcsv($output, $row); }
    fclose($output);
    exit;
}

$page_title = "Data Export - Admin SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter uppercase italic">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>Admin Panel</span>
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
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Departments
            </a>
            <a href="export.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="download-cloud" class="w-5 h-5"></i>
                Export Archive
            </a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950 flex flex-col pt-20">
        <div class="max-w-4xl mx-auto w-full px-10">
            <div class="bg-white dark:bg-gray-900 rounded-[3rem] p-16 border border-gray-100 dark:border-gray-800 shadow-2xl text-center relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-[2rem] flex items-center justify-center text-primary-600 mx-auto mb-10 shadow-inner">
                        <i data-lucide="file-spreadsheet" class="w-12 h-12"></i>
                    </div>
                    
                    <h1 class="text-4xl font-black text-gray-950 dark:text-white tracking-tighter uppercase mb-6 italic">ARCHIVE EXTRACTION</h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-12 max-w-md mx-auto leading-relaxed">
                        Extract the entire student relational database into a standardized CSV format for external processing or backup.
                    </p>
                    
                    <div class="space-y-4">
                        <a href="export.php?download=1" class="inline-flex items-center justify-center gap-3 px-10 py-5 bg-primary-600 hover:bg-primary-700 text-white font-black text-lg rounded-[2rem] shadow-2xl shadow-primary-500/40 transition-all hover:scale-[1.05] active:scale-[0.98]">
                            <i data-lucide="download" class="w-6 h-6"></i>
                            GENERATE CSV ARCHIVE
                        </a>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">
                            Format: Comma Separated Values (.csv)
                        </p>
                    </div>
                    
                    <div class="mt-20 grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <p class="text-xs font-black uppercase text-gray-400 mb-1 tracking-widest">Encoding</p>
                            <p class="text-sm font-bold text-gray-950 dark:text-white tracking-tighter">UTF-8</p>
                        </div>
                        <div class="text-center border-x border-gray-100 dark:border-gray-800 px-4">
                            <p class="text-xs font-black uppercase text-gray-400 mb-1 tracking-widest">Logic</p>
                            <p class="text-sm font-bold text-gray-950 dark:text-white tracking-tighter">PDO-STREAM</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-black uppercase text-gray-400 mb-1 tracking-widest">Timestamp</p>
                            <p class="text-sm font-bold text-gray-950 dark:text-white tracking-tighter"><?php echo date('Y-m-d'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="text-center mt-12 text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] opacity-50">
                CONFIDENTIAL DATA - AUTHORIZED PERSONNEL ONLY
            </p>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
