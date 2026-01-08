<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Nexus SCMS'; ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media',
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
                    'body': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        @media (prefers-color-scheme: dark) {
            .glass {
                background: rgba(3, 7, 18, 0.7);
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
        }
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #2563eb;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-gray-950 font-sans text-gray-900 dark:text-gray-100 antialiased overflow-x-hidden">
<?php 
$current_page = $_SERVER['PHP_SELF'];
$is_admin = strpos($current_page, '/admin/') !== false;
$is_student = strpos($current_page, '/student/') !== false;
$show_public_nav = !$is_admin && !$is_student;
$root_path = ($is_admin || $is_student) ? '../' : './';

if ($show_public_nav): 
?>
<!-- Dynamic Navbar -->
<nav class="fixed top-0 left-0 w-full z-[100] glass px-6 lg:px-20 h-20 flex items-center justify-between">
    <a href="<?php echo $root_path; ?>index.php" class="flex items-center gap-3 text-gray-950 dark:text-white font-black text-2xl tracking-tighter uppercase italic group">
        <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform">
            <i data-lucide="graduation-cap" class="w-6 h-6"></i>
        </div>
        <span class="hidden sm:block">Nexus<span class="text-primary-600">SCMS</span></span>
    </a>

    <div class="flex items-center gap-8 lg:gap-12">
        <a href="<?php echo $root_path; ?>index.php" class="nav-link text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-950 dark:hover:text-white transition-colors italic">Home</a>
        <a href="<?php echo $root_path; ?>about.php" class="nav-link text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-950 dark:hover:text-white transition-colors italic">Specs</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $_SESSION['role'] == 'admin' ? $root_path.'admin/dashboard.php' : $root_path.'student/dashboard.php'; ?>" class="px-8 py-3 bg-gray-950 dark:bg-white text-white dark:text-gray-950 text-xs font-black rounded-full shadow-xl hover:scale-105 transition-all uppercase tracking-widest italic">
                Dashboard
            </a>
        <?php else: ?>
            <div class="flex items-center gap-4">
                <a href="<?php echo $root_path; ?>login.php" class="group px-8 py-3 bg-primary-600 text-white text-xs font-black rounded-full shadow-xl shadow-primary-500/20 hover:bg-primary-700 hover:scale-105 transition-all flex items-center gap-2 uppercase tracking-widest italic">
                    Login Protocol
                    <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="h-20"></div>
<?php endif; ?>
