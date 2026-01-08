<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: students.php");
    exit;
}

$error = '';
$success = '';

// Fetch student
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: students.php");
    exit;
}

// Fetch departments
$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $department_id = $_POST['department_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    if (empty($full_name)) {
        $error = "Full name is mandatory.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE students SET full_name = ?, department_id = ?, phone = ?, email = ?, address = ? WHERE id = ?");
            $stmt->execute([$full_name, $department_id, $phone, $email, $address, $id]);
            $success = "Entity record updated successfully.";
            
            // Re-fetch updated data
            $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute([$id]);
            $student = $stmt->fetch();
        } catch (Exception $e) {
            $error = "Update failure: " . $e->getMessage();
        }
    }
}

$page_title = "Edit Record - Admin SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white hidden md:flex flex-col flex-shrink-0">
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
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="users" class="w-5 h-5"></i>
                Database
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Departments
            </a>
            <a href="export.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="download-cloud" class="w-5 h-5"></i>
                Export
            </a>
        </nav>
        <div class="p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950 flex flex-col">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-10 flex items-center justify-between sticky top-0 z-20">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">MODIFY ENTITY RECORD</h2>
            <a href="students.php" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm font-black rounded-xl transition-all">
                Back to Archive
            </a>
        </header>

        <div class="p-10 max-w-4xl mx-auto w-full">
            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900 text-red-600 p-5 rounded-2xl mb-8 font-bold text-sm">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900 text-green-600 p-5 rounded-2xl mb-8 font-bold text-sm">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 rounded-full blur-2xl -mr-16 -mt-16"></div>

                <form action="edit-student.php?id=<?php echo $id; ?>" method="POST" class="space-y-12 relative z-10 text-gray-900 dark:text-gray-100 italic font-bold">
                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4 italic">Stable Identity Node</h4>
                        <div class="space-y-2 opacity-50">
                            <label class="block text-xs font-black uppercase text-gray-400 ml-1">Entity Serial ID (IMMUTABLE)</label>
                            <div class="relative">
                                <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" class="w-full pl-12 pr-6 py-4 bg-gray-100 dark:bg-gray-800 border-none rounded-2xl outline-none font-black text-gray-500 cursor-not-allowed uppercase" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4 italic">Evolvable Attributes</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase text-gray-400 ml-1">Full Legal Name*</label>
                                <div class="relative">
                                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input type="text" name="full_name" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-black text-gray-900 dark:text-white" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase text-gray-400 ml-1">Departmental Allocation</label>
                                <div class="relative">
                                    <i data-lucide="layers" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <select name="department_id" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-black appearance-none cursor-pointer text-gray-900 dark:text-white">
                                        <option value="">Select Allocation</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" <?php echo ($student['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($dept['department_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4 italic">Remote Connection Ports</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase text-gray-400 ml-1">Mobile Interface</label>
                                <div class="relative">
                                    <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input type="text" name="phone" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-black text-gray-900 dark:text-white" value="<?php echo htmlspecialchars($student['phone']); ?>">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase text-gray-400 ml-1">Electronic Mail Port</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input type="email" name="email" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-black text-gray-900 dark:text-white" value="<?php echo htmlspecialchars($student['email']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase text-gray-400 ml-1">Physical Residential Locale</label>
                            <div class="relative">
                                <i data-lucide="map-pin" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                                <textarea name="address" rows="3" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-black text-gray-900 dark:text-white"><?php echo htmlspecialchars($student['address']); ?></textarea>
                            </div>
                        </div>
                    </section>

                    <div class="pt-10 flex gap-4">
                        <button type="submit" class="flex-1 py-5 bg-orange-600 hover:bg-orange-700 text-white font-black rounded-3xl shadow-2xl shadow-orange-500/30 transition-all hover:scale-[1.02] active:scale-[0.98] uppercase tracking-widest text-sm">
                            COMMIT REVISION
                        </button>
                        <a href="students.php" class="px-10 py-5 bg-gray-100 dark:bg-gray-800 text-gray-500 font-black rounded-3xl hover:bg-gray-200 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                            Abort
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
