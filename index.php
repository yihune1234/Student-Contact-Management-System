<?php
require_once 'includes/config.php';
$page_title = "Welcome - SCMS";
include 'includes/header.php';
?>

<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-slate-900">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-600/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl px-6 mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 glass text-primary-400 text-sm font-bold mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    System Online
                </div>
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-tight mb-6 tracking-tight">
                    Manage Student <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-purple-400">Contacts</span> with Ease.
                </h1>
                <p class="text-xl text-gray-400 mb-10 max-w-xl mx-auto lg:mx-0">
                    A secure, modern, and high-performance portal for students and administrators to manage educational records effortlessly.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    <a href="student/login.php" class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-500/20 transition-all hover:scale-105 flex items-center justify-center gap-2">
                        Student Portal
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="admin/login.php" class="w-full sm:w-auto px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 glass text-white font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                        Admin Panel
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </a>
                </div>
                <div class="mt-12 flex items-center justify-center lg:justify-start gap-3">
                    <p class="text-gray-500 font-medium">New student?</p>
                    <a href="student/register.php" class="text-primary-400 font-bold hover:underline underline-offset-4 decoration-2">Create an account</a>
                </div>
            </div>

            <!-- Visual Component -->
            <div class="hidden lg:block relative">
                <div class="relative z-20 bg-white/5 glass border border-white/10 p-8 rounded-[3rem] shadow-2xl">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <div class="space-y-6">
                        <div class="h-8 bg-white/10 rounded-xl w-3/4"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="h-32 bg-white/5 rounded-2xl border border-white/5 p-4 flex flex-col justify-end gap-2">
                                <div class="h-4 bg-primary-500/50 rounded w-1/2"></div>
                                <div class="h-4 bg-primary-500/20 rounded w-3/4"></div>
                            </div>
                            <div class="h-32 bg-white/5 rounded-2xl border border-white/5 p-4 flex flex-col justify-end gap-2">
                                <div class="h-4 bg-purple-500/50 rounded w-1/2"></div>
                                <div class="h-4 bg-purple-500/20 rounded w-3/4"></div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-4 bg-white/5 rounded w-full"></div>
                            <div class="h-4 bg-white/5 rounded w-full"></div>
                            <div class="h-4 bg-white/5 rounded w-2/3"></div>
                        </div>
                    </div>
                </div>
                <!-- Floating Icons -->
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-primary-600/40 rounded-3xl blur-2xl animate-pulse"></div>
                <div class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-purple-600/30 rounded-full blur-[80px]"></div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>