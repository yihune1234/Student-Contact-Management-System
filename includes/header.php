<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'SCMS'; ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            "50": "#eff6ff",
                            "100": "#dbeafe",
                            "200": "#bfdbfe",
                            "300": "#93c5fd",
                            "400": "#60a5fa",
                            "500": "#3b82f6",
                            "600": "#2563eb",
                            "700": "#1d4ed8",
                            "800": "#1e40af",
                            "900": "#1e3a8a",
                            "950": "#172554"
                        }
                    }
                },
                fontFamily: {
                    'body': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
                    'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass {
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased">
<?php 
// Only show public navbar if not on an admin or student dashboard page
$current_page = $_SERVER['PHP_SELF'];
$is_public = !strpos($current_page, '/admin/') && !strpos($current_page, '/student/');
$root_path = strpos($current_page, '/admin/') || strpos($current_page, '/student/') ? '../' : './';

if ($is_public || !isset($_SESSION['user_id'])): 
?>
<nav class="fixed top-0 left-0 w-full z-50 glass border-b border-white/20 px-10 py-4 flex items-center justify-between">
    <a href="<?php echo $root_path; ?>index.php" class="flex items-center gap-2 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter italic">
        <i data-lucide="graduation-cap" class="w-8 h-8"></i>
        <span>SCMS</span>
    </a>
    <div class="flex items-center gap-10">
        <a href="<?php echo $root_path; ?>index.php" class="text-sm font-bold hover:text-primary-600 transition-colors">Home</a>
        <a href="<?php echo $root_path; ?>about.php" class="text-sm font-bold hover:text-primary-600 transition-colors">About</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $_SESSION['role'] == 'admin' ? $root_path.'admin/dashboard.php' : $root_path.'student/dashboard.php'; ?>" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl shadow-lg">Dashboard</a>
        <?php else: ?>
            <a href="<?php echo $root_path; ?>login.php" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-primary-700 transition-all">Login</a>
        <?php endif; ?>
    </div>
</nav>
<div class="h-20"></div>
<?php endif; ?>
