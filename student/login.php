<?php
require_once '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] === 'student') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['student_id'] = $user['student_id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Access denied. This login is for students only.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

$page_title = "Student Login - SCMS";
include '../includes/header.php';
?>

<section class="min-h-screen flex items-center justify-center p-6 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-primary-700 via-primary-900 to-gray-900">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/10 glass mb-6">
                <i data-lucide="graduation-cap" class="w-12 h-12 text-white"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Student Portal</h1>
            <p class="text-primary-200 mt-2">Manage your contact records securely</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/90 dark:bg-gray-800/90 glass p-8 rounded-[2.5rem] shadow-2xl">
            <h2 class="text-2xl font-bold mb-2">Welcome Back!</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8 text-sm">Please enter your student credentials</p>

            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 p-4 rounded-2xl mb-6 flex items-center gap-3 animate-shake">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <p class="text-sm font-medium"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-semibold mb-2 ml-1">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" name="username" id="username" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-primary-500 transition-all outline-none" placeholder="Enter your username" required>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold mb-2 ml-1">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="password" name="password" id="password" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-primary-500 transition-all outline-none" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account? 
                    <a href="register.php" class="text-primary-600 dark:text-primary-400 font-bold hover:underline">Register Now</a>
                </p>
                <div class="flex items-center justify-center gap-4 py-2">
                    <span class="h-px bg-gray-200 dark:bg-gray-700 w-full"></span>
                    <span class="text-xs text-gray-400 uppercase tracking-widest font-bold">OR</span>
                    <span class="h-px bg-gray-200 dark:bg-gray-700 w-full"></span>
                </div>
                <a href="../admin/login.php" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 dark:hover:text-white uppercase tracking-wider transition-colors">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Admin Access
                </a>
            </div>
        </div>
        
        <p class="text-center mt-8 text-primary-200/50 text-xs font-medium">
            &copy; <?php echo date('Y'); ?> Student Contact Management System. All rights reserved.
        </p>
    </div>
</section>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
.animate-shake {
    animation: shake 0.4s ease-in-out;
}
</style>

<?php include '../includes/footer.php'; ?>
