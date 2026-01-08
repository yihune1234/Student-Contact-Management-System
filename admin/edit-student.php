<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
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
    <!-- Sidebar... -->
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
                Dashboard
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="users" class="w-5 h-5"></i>
                Database
            </a>
            <!-- ... -->
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
        <header class="h-20 bg-white dark:bg-gray-900 border-bottom border-gray-100 dark:border-gray-800 px-10 flex items-center justify-between sticky top-0 z-20">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">MODIFY ENTITY RECORD</h2>
            <div class="flex gap-4">
                <a href="students.php" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm font-black rounded-xl transition-all">
                    Dismiss
                </a>
            </div>
        </header>

        <div class="p-10 max-w-4xl mx-auto">
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

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 border border-gray-100 dark:border-gray-800 shadow-sm">
                <form action="edit-student.php?id=<?php echo $id; ?>" method="POST" class="space-y-10">
                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Fixed Identity Node</h4>
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Entity Serial ID (Read-only)</label>
                            <input type="text" class="w-full px-6 py-4 bg-gray-100 dark:bg-gray-800 border-none rounded-2xl outline-none font-black text-gray-500 cursor-not-allowed" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Evolvable Attributes</h4>
                        <div class="grid grid-cols-1 gap-8">
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Full Legal Entity Name*</label>
                                <input type="text" name="full_name" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Departmental Allocation</label>
                                <select name="department_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold appearance-none cursor-pointer">
                                    <option value="">Select Allocation</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept['id']; ?>" <?php echo ($student['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($dept['department_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Connection Interfaces</h4>
                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Cellular Link</label>
                                <input type="text" name="phone" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" value="<?php echo htmlspecialchars($student['phone']); ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Electronic Mail Logic</label>
                                <input type="email" name="email" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" value="<?php echo htmlspecialchars($student['email']); ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Geographic Coordinates (Address)</label>
                            <textarea name="address" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold"><?php echo htmlspecialchars($student['address']); ?></textarea>
                        </div>
                    </section>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-5 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-3xl shadow-2xl shadow-primary-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            COMMIT RECORD UPDATE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
