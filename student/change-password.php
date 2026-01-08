<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "New individual secrets (passwords) do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (password_verify($old_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $_SESSION['user_id']]);
            $success = "Secret cryptographic node updated successfully.";
        } else {
            $error = "The current primary secret is invalid.";
        }
    }
}

$page_title = "Security - Student Portal";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden text-gray-900 dark:text-gray-100 italic">
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="user" class="w-5 h-5"></i>
                My Profile
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security Node
            </a>
        </nav>
        <!-- Logout... -->
    </aside>

    <!-- Main -->
    <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-gray-950 flex flex-col pt-20">
        <div class="max-w-xl mx-auto w-full px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">SECURITY PROTOCOL</h2>
                <p class="text-gray-500 mt-2 font-medium tracking-wide">Update your unique portal access secret.</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900 text-red-600 p-5 rounded-2xl mb-8 font-bold text-sm italic">
                    <i data-lucide="shield-alert" class="w-4 h-4 inline-block mr-2"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900 text-green-600 p-5 rounded-2xl mb-8 font-bold text-sm italic">
                    <i data-lucide="shield-check" class="w-4 h-4 inline-block mr-2"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 border border-gray-100 dark:border-gray-800 shadow-2xl">
                <form action="change-password.php" method="POST" class="space-y-8">
                    <div>
                        <label for="old_password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 ml-1">Current Secret Logic</label>
                        <div class="relative">
                            <i data-lucide="key-round" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="old_password" id="old_password" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" required placeholder="Enter current secret">
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 dark:bg-gray-800"></div>

                    <div>
                        <label for="new_password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 ml-1">New Secret Architecture</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="new_password" id="new_password" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" required placeholder="New secret (min 6 chars)">
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 ml-1">Verify Architecture</label>
                        <div class="relative">
                            <i data-lucide="check-square" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="confirm_password" id="confirm_password" class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-500/50 transition-all font-bold" required placeholder="Confirm new secret">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-5 bg-gradient-to-r from-gray-900 to-gray-800 dark:from-white dark:to-gray-100 text-white dark:text-gray-950 font-black rounded-3xl shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-widest text-sm">
                            UPDATE CRYPTO LOGIC
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="text-center mt-12 text-gray-400 text-[10px] font-black uppercase tracking-[0.3em]">
                SHA-256 ENCRYPTED TRANSACTION
            </p>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
