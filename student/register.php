<?php
require_once '../includes/config.php';

$error = '';
$success = '';

// Fetch departments for the dropdown
$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $department_id = $_POST['department_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($student_id) || empty($full_name) || empty($username) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username already taken.";
            } else {
                $stmt = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
                $stmt->execute([$student_id]);
                if ($stmt->fetch()) {
                    $error = "Student ID already registered.";
                } else {
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, department_id, phone, email, address) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$student_id, $full_name, $department_id, $phone, $email, $address]);
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, student_id) VALUES (?, ?, 'student', ?)");
                    $stmt->execute([$username, $hashed_password, $student_id]);
                    $pdo->commit();
                    $success = "Registration successful! <a href='login.php' class='font-bold underline'>Login here</a>";
                }
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}

$page_title = "Student Registration - SCMS";
include '../includes/header.php';
?>

<section class="min-h-screen py-20 bg-slate-50 dark:bg-gray-950 flex flex-col items-center justify-center p-6 relative overflow-hidden">
    <!-- Blob Decorations -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-500/10 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-500/10 rounded-full blur-[100px]"></div>

    <div class="w-full max-w-2xl relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight italic uppercase">Join the Portal</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Create your permanent student identity record.</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 shadow-2xl border border-gray-100 dark:border-gray-800">
            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 p-5 rounded-2xl mb-8 flex items-center gap-3">
                    <i data-lucide="alert-octagon" class="w-6 h-6"></i>
                    <p class="font-bold text-sm"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-5 rounded-2xl mb-8 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    <p class="font-bold text-sm"><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-8">
                <!-- Personal Info Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary-500 mb-6 flex items-center gap-2">
                        <span class="w-8 h-px bg-primary-500/30"></span>
                        Academic Identity
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold mb-2 ml-1">Student ID*</label>
                            <input type="text" name="student_id" placeholder="e.g. STU-001" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 ml-1">Full Identity Name*</label>
                            <input type="text" name="full_name" placeholder="Full legal name" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all" required>
                        </div>
                    </div>
                </div>

                <!-- Academic Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary-500 mb-6 flex items-center gap-2">
                        <span class="w-8 h-px bg-primary-500/30"></span>
                        Departmental Affiliation
                    </h3>
                    <div class="form-group">
                        <label class="block text-sm font-bold mb-2 ml-1">Faculty / Department</label>
                        <select name="department_id" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Contact Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary-500 mb-6 flex items-center gap-2">
                        <span class="w-8 h-px bg-primary-500/30"></span>
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold mb-2 ml-1">Phone Number</label>
                            <input type="text" name="phone" placeholder="+1..." class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 ml-1">Email Electronic</label>
                            <input type="email" name="email" placeholder="name@college.edu" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all">
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-bold mb-2 ml-1">Home Logistics Address</label>
                        <textarea name="address" rows="2" placeholder="Street, City, State..." class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all"></textarea>
                    </div>
                </div>

                <!-- Security Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary-500 mb-6 flex items-center gap-2">
                        <span class="w-8 h-px bg-primary-500/30"></span>
                        Access Credentials
                    </h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-bold mb-2 ml-1">Portal Username*</label>
                            <input type="text" name="username" placeholder="Login name" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold mb-2 ml-1">Secret Password*</label>
                                <input type="password" name="password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 ml-1">Confirm Secret*</label>
                                <input type="password" name="confirm_password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-primary-500 dark:border-gray-700 rounded-2xl outline-none transition-all" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-700 hover:to-indigo-700 text-white font-black text-lg rounded-[2rem] shadow-2xl shadow-primary-500/40 transition-all hover:scale-[1.03] active:scale-[0.98]">
                        Complete Registration
                    </button>
                    <p class="text-center mt-8 text-sm text-gray-500 dark:text-gray-400">
                        Locked and encrypted access for students only. 
                        Already a member? <a href="login.php" class="text-primary-600 font-black hover:underline underline-offset-4 decoration-2">Login Here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
