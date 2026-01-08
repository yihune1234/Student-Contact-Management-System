<?php
require_once '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Administrator credentials required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "System rejected proposed admin logic.";
        }
    }
}

$page_title = "Admin Login - SCMS";
include '../includes/header.php';
?>

<section class="min-h-screen flex text-gray-950 dark:text-white">
    <!-- Left Side: Login Form -->
    <div class="w-full lg:w-[450px] bg-white dark:bg-gray-950 p-12 flex flex-col justify-center relative overflow-hidden">
        <div class="relative z-10">
            <div class="mb-12">
                <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center text-white mb-8 shadow-2xl shadow-primary-500/40">
                    <i data-lucide="shield-check" class="w-10 h-10"></i>
                </div>
                <h1 class="text-4xl font-black italic tracking-tighter uppercase leading-none">ADMIN<br><span class="text-primary-600">CENTRAL</span></h1>
                <p class="text-gray-400 mt-4 font-bold text-sm tracking-widest uppercase">System Control Interface</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-5 rounded-r-2xl mb-10 flex items-center gap-4 animate-shake">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-500"></i>
                    <p class="text-sm font-black italic uppercase tracking-tight text-red-700 dark:text-red-400"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-8">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 ml-1">Admin Identity</label>
                    <input type="text" name="username" class="w-full px-8 py-5 bg-gray-50 dark:bg-gray-900 border-none rounded-3xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700" placeholder="Root Username" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 ml-1">Security Secret</label>
                    <input type="password" name="password" class="w-full px-8 py-5 bg-gray-50 dark:bg-gray-900 border-none rounded-3xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700" placeholder="••••••••" required>
                </div>
                <button type="submit" class="w-full py-6 bg-gray-950 dark:bg-primary-600 text-white font-black rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-[0.2em]">
                    EXECUTE LOGIN
                </button>
            </form>

            <div class="mt-12 text-center">
                <a href="../student/login.php" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-primary-600 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Return to Student Portal
                </a>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-primary-100 dark:bg-primary-900/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Right Side: Visual Content -->
    <div class="hidden lg:flex flex-1 bg-gray-100 dark:bg-gray-900 items-center justify-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-full grid grid-cols-12 gap-0">
                <?php for($i=0; $i<144; $i++): ?>
                    <div class="border-[0.5px] border-gray-400 dark:border-gray-600 aspect-square"></div>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="relative z-10 text-center px-20">
            <h2 class="text-6xl font-black italic text-gray-200 dark:text-gray-800 tracking-tighter mb-8 leading-tight">THE CORE<br>ARCHITECTURE</h2>
            <p class="text-gray-400 dark:text-gray-600 font-bold uppercase tracking-[0.5em] text-xs">Administrative Matrix Access Only</p>
        </div>
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
