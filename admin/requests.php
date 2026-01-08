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

// Handle Approval / Rejection
if (isset($_POST['action']) && isset($_POST['request_id'])) {
    $req_id = $_POST['request_id'];
    $action = $_POST['action']; // 'Approved' or 'Rejected'
    $reviewed_by = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();
        
        // Fetch request details
        $stmt = $pdo->prepare("SELECT * FROM update_requests WHERE id = ?");
        $stmt->execute([$req_id]);
        $request = $stmt->fetch();

        if ($request && $request['status'] === 'Pending') {
            if ($action === 'Approved') {
                // Apply update to student table
                $field = $request['field_name'];
                $new_val = $request['new_value'];
                $s_id = $request['student_id'];
                
                // Security Note: In a real app, whitelist field names
                $allowed_fields = ['phone', 'email', 'secondary_phone', 'address_detail', 'profile_photo'];
                if (in_array($field, $allowed_fields)) {
                    $upd = $pdo->prepare("UPDATE students SET $field = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                    $upd->execute([$new_val, $s_id]);
                }
            }

            // Update request status
            $stmt = $pdo->prepare("UPDATE update_requests SET status = ?, reviewed_by = ?, reviewed_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$action, $reviewed_by, $req_id]);

            $pdo->commit();
            $success = "Request status updated to $action.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Logic mismatch: " . $e->getMessage();
    }
}

// Fetch Pending Requests
$query = "SELECT r.*, s.full_name, s.student_id as sid, u.username as reviewer 
          FROM update_requests r 
          JOIN students s ON r.student_id = s.id 
          LEFT JOIN users u ON r.reviewed_by = u.id
          ORDER BY r.requested_at DESC";
$requests = $pdo->query($query)->fetchAll();

$page_title = "Update Requests - Nexus SCMS";
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
            <a href="students.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Manage Students
            </a>
            <a href="requests.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="git-pull-request" class="w-5 h-5"></i>
                Update Requests
            </a>
        </nav>
        <div class="p-6 border-t border-white/5 mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                Exit Protocol
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">MAINTENANCE PROTOCOLS</h2>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-10">
            <div class="max-w-7xl mx-auto space-y-10">
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-100 text-green-600 p-6 rounded-[2rem] font-bold text-sm italic flex items-center gap-4">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 gap-8">
                    <?php foreach($requests as $req): ?>
                    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest <?php 
                                echo match($req['status']) {
                                    'Pending' => 'bg-orange-50 text-orange-600 border border-orange-200',
                                    'Approved' => 'bg-green-50 text-green-600 border border-green-200',
                                    'Rejected' => 'bg-red-50 text-red-600 border border-red-200',
                                    default => 'bg-gray-50 text-gray-600'
                                };
                            ?>">
                                <?php echo $req['status']; ?>
                            </span>
                        </div>

                        <div class="flex flex-col md:flex-row gap-10">
                            <!-- Student Info -->
                            <div class="md:w-1/3 space-y-4 italic">
                                <div>
                                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Entity Origin</h5>
                                    <p class="font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($req['full_name']); ?></p>
                                    <p class="text-[10px] font-bold text-primary-600 uppercase mt-0.5">ID: <?php echo htmlspecialchars($req['sid']); ?></p>
                                </div>
                                <div>
                                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Time Log</h5>
                                    <p class="text-sm font-bold text-gray-600"><?php echo date('M d, Y H:i', strtotime($req['requested_at'])); ?></p>
                                </div>
                            </div>

                            <!-- Modification Content -->
                            <div class="flex-1 bg-gray-50 dark:bg-gray-800/50 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 italic">
                                <h5 class="text-[10px] font-black text-primary-600 uppercase tracking-[0.2em] mb-4">Proposed Modification: <span class="text-gray-950 dark:text-white"><?php echo strtoupper(str_replace('_', ' ', $req['field_name'])); ?></span></h5>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Original State</p>
                                        <p class="text-sm font-bold text-gray-400 line-through"><?php echo htmlspecialchars($req['old_value'] ?: 'Null'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-green-500 uppercase mb-2">Target State</p>
                                        <p class="text-sm font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($req['new_value']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <?php if ($req['status'] === 'Pending'): ?>
                            <div class="md:w-48 flex flex-col gap-3 justify-center">
                                <form method="POST" class="contents">
                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                    <button name="action" value="Approved" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-lg transition-transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                        Authorize
                                    </button>
                                    <button name="action" value="Rejected" class="w-full py-4 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-red-50 hover:text-red-600 transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                        Shutdown
                                    </button>
                                </form>
                            </div>
                            <?php else: ?>
                            <div class="md:w-48 flex flex-col justify-center italic">
                                <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Resolution by</p>
                                <p class="text-xs font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($req['reviewer'] ?? 'System'); ?></p>
                                <p class="text-[10px] font-bold text-gray-400 mt-1"><?php echo date('M d, Y', strtotime($req['reviewed_at'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($requests)): ?>
                    <div class="text-center py-20 bg-white dark:bg-gray-900 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-800 italic">
                        <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-200 mb-6 font-thin"></i>
                        <p class="text-xl font-black text-gray-400 uppercase tracking-tighter">Queue is Crystal Clear</p>
                        <p class="text-sm font-bold text-gray-300 mt-2 tracking-widest">No maintenance protocols pending validation.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
