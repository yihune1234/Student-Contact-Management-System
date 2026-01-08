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

$page_title = "Gateway - Nexus Matrix Control";
include 'includes/header.php';
?>

<div class="relative min-h-screen bg-slate-50 dark:bg-gray-950 flex flex-col items-center justify-center p-6 overflow-hidden">
    <!-- Ambient Background Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[30%] left-[10%] w-96 h-96 bg-primary-600/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[20%] right-[15%] w-80 h-80 bg-indigo-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg">
        <div class="text-center mb-12 space-y-4">
            <h1 class="text-6xl font-black italic tracking-tighter uppercase leading-none text-gray-950 dark:text-white">PORTAL<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-500 underline underline-offset-8 decoration-primary-600/20">GATEWAY.</span></h1>
            <p class="text-gray-400 font-black text-xs tracking-[0.4em] uppercase italic">Centralized Authentication Matrix</p>
        </div>

        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-3xl rounded-[3.5rem] p-12 shadow-2xl border border-gray-100 dark:border-gray-800 relative group transition-all hover:shadow-primary-500/5">
            <div class="absolute top-0 right-0 w-48 h-48 bg-primary-500/5 rounded-full blur-3xl -mr-24 -mt-24"></div>
            
            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/50 p-6 rounded-3xl mb-10 flex items-center gap-4 animate-shake">
                    <i data-lucide="shield-alert" class="w-6 h-6 text-red-600 flex-shrink-0"></i>
                    <p class="text-xs font-black uppercase tracking-tight text-red-600 italic"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-10 relative z-10 italic">
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-4 ml-2 group-focus-within:text-primary-600 transition-colors">Identity Node</label>
                        <div class="relative">
                            <i data-lucide="fingerprint" class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-300 group-focus-within:text-primary-600 transition-colors"></i>
                            <input type="text" name="username" class="w-full pl-16 pr-8 py-6 bg-gray-50 dark:bg-gray-800/50 border-none rounded-3xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700" placeholder="Username / ID" required>
                        </div>
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-4 ml-2 group-focus-within:text-primary-600 transition-colors">Security Secret</label>
                        <div class="relative">
                            <i data-lucide="key-round" class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-300 group-focus-within:text-primary-600 transition-colors"></i>
                            <input type="password" name="password" class="w-full pl-16 pr-8 py-6 bg-gray-50 dark:bg-gray-800/50 border-none rounded-3xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-6 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-[0.3em] text-sm italic group flex items-center justify-center gap-4 overflow-hidden relative">
                    <span class="relative z-10">Initialize Ingress</span>
                    <i data-lucide="shield-check" class="relative z-10 w-5 h-5 transition-transform group-hover:scale-125"></i>
                    <div class="absolute inset-0 bg-primary-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </form>
            
            <div class="mt-12 pt-10 border-t border-gray-100 dark:border-gray-800 text-center">
                <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.4em] italic mb-4">
                    Architectural Authority: SCMS Core
                </p>
                <div class="flex justify-center gap-6 opacity-30">
                    <i data-lucide="monitor" class="w-4 h-4 text-gray-500"></i>
                    <i data-lucide="smartphone" class="w-4 h-4 text-gray-500"></i>
                    <i data-lucide="shield-check" class="w-4 h-4 text-gray-500"></i>
                </div>
            </div>
        </div>
        
        <div class="mt-12 text-center">
            <a href="index.php" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary-600 transition-colors flex items-center justify-center gap-2 italic">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Return to Nexus Home
            </a>
        </div>
    </div>
</div>

<style>
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}
.animate-shake {
  animation: shake 0.3s ease-in-out;
}
</style>

<?php include 'includes/footer.php'; ?>
