<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Fetch departments for the dropdown
$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    
    // Student specific fields
    $student_id = $_POST['student_id'] ?? null;
    $department_id = $_POST['department_id'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $address = $_POST['address'] ?? null;

    if (empty($username) || empty($password) || empty($full_name)) {
        $error = "Base identity attributes (Username, Password, Name) are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Proposed username already exists in registry.";
            } else {
                $pdo->beginTransaction();
                
                $final_student_id = null;
                if ($role === 'student') {
                    if (empty($student_id)) {
                        $error = "Student Serial ID is required for student entities.";
                        $pdo->rollBack();
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, department_id, phone, email, address) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$student_id, $full_name, $department_id, $phone, $email, $address]);
                        $final_student_id = $student_id;
                    }
                }

                if (!$error) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, student_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$username, $hashed_password, $role, $final_student_id]);
                    $pdo->commit();
                    $success = "New $role entity synthesized successfully.";
                }
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = "System failure: " . $e->getMessage();
        }
    }
}

$page_title = "User Registration - Admin SCMS";
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
            <a href="applications.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Applications
            </a>
            <a href="departments.php" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Departments
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
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">ENROLL NEW NODE</h2>
            <a href="students.php" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm font-black rounded-xl transition-all">
                Registry View
            </a>
        </header>

        <div class="p-10 max-w-4xl mx-auto w-full">
            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 text-red-600 p-5 rounded-2xl mb-8 font-bold text-sm italic">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 text-green-600 p-5 rounded-2xl mb-8 font-bold text-sm italic">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <form action="add-student.php" method="POST" class="space-y-12 relative z-10 italic">
                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Logic Class (Role)</h4>
                        <div class="flex gap-6">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="role" value="student" class="hidden peer" checked onchange="toggleStudentFields(true)">
                                <div class="p-6 rounded-3xl border-2 border-gray-100 dark:border-gray-800 peer-checked:border-primary-600 peer-checked:bg-primary-600/5 transition-all text-center">
                                    <i data-lucide="graduation-cap" class="w-8 h-8 mx-auto mb-3 text-gray-400 peer-checked:text-primary-600"></i>
                                    <span class="font-black uppercase text-xs tracking-widest leading-none">Student</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="role" value="admin" class="hidden peer" onchange="toggleStudentFields(false)">
                                <div class="p-6 rounded-3xl border-2 border-gray-100 dark:border-gray-800 peer-checked:border-primary-600 peer-checked:bg-primary-600/5 transition-all text-center">
                                    <i data-lucide="shield-check" class="w-8 h-8 mx-auto mb-3 text-gray-400 peer-checked:text-primary-600"></i>
                                    <span class="font-black uppercase text-xs tracking-widest leading-none">Admin</span>
                                </div>
                            </label>
                        </div>
                    </section>

                    <section id="base-info">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Base Identity</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Full Legal Name*</label>
                                <input type="text" name="full_name" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" required>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Username*</label>
                                <input type="text" name="username" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" required>
                            </div>
                        </div>
                        <div class="mt-8">
                            <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Initial Secret (Password)*</label>
                            <input type="password" name="password" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" required>
                        </div>
                    </section>

                    <section id="student-only-fields" class="space-y-12">
                        <div>
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Academic Attributes</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Student Serial ID*</label>
                                    <input type="text" name="student_id" id="field-student-id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Faculty Node</label>
                                    <select name="department_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-black appearance-none cursor-pointer text-gray-500">
                                        <option value="">Select Department Node</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">Connection Ports</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <div>
                                    <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Mobile Interface</label>
                                    <input type="text" name="phone" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold">
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Electronic Mail Port</label>
                                    <input type="email" name="email" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-3 ml-1">Residential Coordinates</label>
                                <textarea name="address" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold"></textarea>
                            </div>
                        </div>
                    </section>

                    <div class="pt-10 flex gap-4">
                        <button type="submit" class="flex-1 py-5 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-3xl shadow-2xl transition-all hover:scale-[1.02] active:scale-[0.98] uppercase tracking-widest text-sm italic">
                            Synthesize Entity Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function toggleStudentFields(isStudent) {
    const fields = document.getElementById('student-only-fields');
    const studentIdInput = document.getElementById('field-student-id');
    
    if (isStudent) {
        fields.style.display = 'block';
        studentIdInput.required = true;
    } else {
        fields.style.display = 'none';
        studentIdInput.required = false;
    }
}
</script>

<?php include '../includes/footer.php'; ?>
