<?php
require_once 'includes/config.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Identity and Secret logic required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['student_id'] = $user['student_id'];
            
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: student/dashboard.php");
            }
            exit;
        } else {
            $error = "System rejected proposed credentials.";
        }
    }
}

$page_title = "Login - SCMS Dashboard";
include 'includes/header.php';
?>

<section class="min-h-[calc(100vh-80px)] flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black italic tracking-tighter uppercase leading-none text-gray-900 dark:text-white">PORTAL<br><span class="text-primary-600">ACCESS</span></h1>
            <p class="text-gray-400 mt-4 font-bold text-xs tracking-[0.3em] uppercase">Unified Login System</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/5 rounded-full blur-2xl -mr-16 -mt-16"></div>
            
            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-5 rounded-r-2xl mb-8 flex items-center gap-4">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <p class="text-xs font-black uppercase tracking-tight text-red-700 dark:text-red-400"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-8 relative z-10 italic">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 ml-1">Username / Identity</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" name="username" class="w-full pl-12 pr-6 py-5 bg-gray-50 dark:bg-gray-900 border-none rounded-3xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700" placeholder="User ID" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 ml-1">Access Secret</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="password" name="password" class="w-full pl-12 pr-6 py-5 bg-gray-50 dark:bg-gray-900 border-none rounded-3xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="w-full py-6 bg-primary-600 text-white font-black rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-[0.2em] text-sm">
                    Infiltrate System
                </button>
            </form>
            
            <div class="mt-10 pt-10 border-t border-gray-50 dark:border-gray-700 text-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    Authorized Access Only
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
