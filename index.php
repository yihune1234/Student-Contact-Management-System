<?php
require_once 'includes/config.php';
$page_title = "SCMS - Institutional Intelligence";
include 'includes/header.php';
?>

<section class="min-h-[calc(100vh-80px)] flex items-center justify-center relative overflow-hidden px-10">
    <!-- Grid Decoration -->
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
        <div class="h-full w-full" style="background-image: radial-gradient(circle, #444 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto text-center lg:text-left grid lg:grid-cols-2 gap-20 items-center">
        <div>
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-primary-600/10 border border-primary-600/20 rounded-full text-primary-600 font-black text-[10px] uppercase tracking-[0.3em] mb-8 italic">
                <span class="w-2 h-2 rounded-full bg-primary-600 animate-pulse"></span>
                System Operational
            </div>
            <h1 class="text-6xl lg:text-8xl font-black text-gray-950 dark:text-white leading-[0.9] tracking-tighter mb-10">CORE<br><span class="text-primary-600">STUDENT</span><br>MATRIX.</h1>
            <p class="text-xl text-gray-500 font-medium italic mb-12 max-w-lg leading-relaxed">
                A unified administrative interface for high-fidelity student contact management and academic identity synchronization.
            </p>
            <div class="flex flex-col sm:flex-row gap-6">
                <a href="login.php" class="px-10 py-5 bg-primary-600 hover:bg-primary-700 text-white font-black text-lg rounded-[2rem] shadow-2xl shadow-primary-500/40 transition-all hover:scale-105 active:scale-[0.98] flex items-center justify-center gap-3">
                    Access Portal
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="about.php" class="px-10 py-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white font-black text-lg rounded-[2rem] shadow-sm hover:shadow-xl transition-all flex items-center justify-center">
                    Technical Specs
                </a>
            </div>
        </div>

        <div class="hidden lg:block">
            <div class="grid grid-cols-2 gap-6 relative">
                 <div class="bg-gray-950 p-8 rounded-[3rem] shadow-2xl border border-gray-800 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                     <i data-lucide="shield" class="w-12 h-12 text-primary-500 mb-6"></i>
                     <p class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-2">Security</p>
                     <p class="text-xl font-bold text-white italic">Hardware-Grade Encryption</p>
                 </div>
                 <div class="bg-primary-600 p-8 rounded-[3rem] shadow-2xl transform rotate-6 translate-y-20 hover:rotate-0 transition-transform duration-500">
                     <i data-lucide="zap" class="w-12 h-12 text-white mb-6"></i>
                     <p class="text-[10px] font-black uppercase text-primary-200 tracking-widest mb-2">Speed</p>
                     <p class="text-xl font-bold text-white italic">Frictionless Operations</p>
                 </div>
                 <div class="col-span-2 bg-white dark:bg-gray-900 p-10 rounded-[4rem] shadow-2xl border border-gray-100 dark:border-gray-800 mt-20">
                     <div class="flex items-center gap-6">
                         <div class="w-16 h-16 rounded-3xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-primary-600">
                             <i data-lucide="users" class="w-8 h-8"></i>
                         </div>
                         <div>
                             <p class="text-2xl font-black text-gray-900 dark:text-white leading-none">Unified Identity</p>
                             <p class="text-xs text-gray-500 mt-1 font-bold italic">Centralized Student Repository</p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>

    <!-- Background Blobs -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
</section>

<?php include 'includes/footer.php'; ?>
