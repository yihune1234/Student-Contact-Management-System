<?php
require_once 'includes/config.php';
$page_title = "SCMS - Nexus of Institutional Intelligence";
include 'includes/header.php';
?>

<div class="relative min-h-screen overflow-hidden bg-slate-50 dark:bg-gray-950 font-sans selection:bg-primary-500/30">
    <!-- Animated Ambient Background -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] bg-indigo-500/10 rounded-full blur-[100px] animate-bounce-slow"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[150px] animate-pulse-slow"></div>
        
        <!-- Interactive Grid -->
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.07]" 
             style="background-image: linear-gradient(#444 1px, transparent 1px), linear-gradient(90deg, #444 1px, transparent 1px); background-size: 50px 50px;">
        </div>
    </div>

    <!-- Main Hero Section -->
    <section class="relative z-10 min-h-screen flex items-center justify-center px-6 lg:px-20 py-32">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
            
            <!-- Left Content: Value Proposition -->
            <div class="text-center lg:text-left space-y-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/50 dark:bg-white/5 border border-primary-500/20 rounded-full backdrop-blur-md shadow-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-600 dark:text-primary-400 italic">Central Matrix Operational</span>
                </div>

                <div class="space-y-4">
                    <h1 class="text-7xl lg:text-9xl font-black text-gray-950 dark:text-white leading-[0.85] tracking-tighter">
                        NEXUS<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-500">MATRIX.</span>
                    </h1>
                    <p class="text-lg lg:text-2xl text-gray-500 dark:text-gray-400 font-medium italic max-w-xl leading-relaxed mx-auto lg:mx-0">
                        Synchronizing institutional logic and student synchronization through a high-fidelity administrative portal.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-6 justify-center lg:justify-start pt-4">
                    <a href="login.php" class="group relative px-10 py-6 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black text-lg rounded-[2.5rem] shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 overflow-hidden flex items-center justify-center gap-4">
                        <span class="relative z-10">Access Central Portal</span>
                        <i data-lucide="arrow-right" class="w-6 h-6 relative z-10 group-hover:translate-x-2 transition-transform"></i>
                        <div class="absolute inset-0 bg-primary-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </a>
                    <a href="about.php" class="px-10 py-6 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 text-gray-900 dark:text-white font-black text-lg rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                        Technical Specs
                    </a>
                </div>

                <!-- Trust Badges / Stats -->
                <div class="pt-12 grid grid-cols-3 gap-8 opacity-40 grayscale group-hover:grayscale-0 transition-all">
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="text-2xl font-black text-gray-950 dark:text-white mb-1">0.05s</span>
                        <span class="text-[8px] font-bold uppercase tracking-widest leading-none">Latency</span>
                    </div>
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="text-2xl font-black text-gray-950 dark:text-white mb-1">AES</span>
                        <span class="text-[8px] font-bold uppercase tracking-widest leading-none">Security</span>
                    </div>
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="text-2xl font-black text-gray-950 dark:text-white mb-1">∞</span>
                        <span class="text-[8px] font-bold uppercase tracking-widest leading-none">Scalability</span>
                    </div>
                </div>
            </div>

            <!-- Right Content: Visual Showcase -->
            <div class="relative hidden lg:block">
                <!-- Main Visual Element -->
                <div class="relative z-10 w-full aspect-square bg-white dark:bg-gray-900 rounded-[5rem] border border-gray-100 dark:border-gray-800 shadow-2xl overflow-hidden p-12 flex items-center justify-center group">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-600/5 to-indigo-600/5 transition-opacity group-hover:opacity-100 opacity-50"></div>
                    
                    <div class="relative z-20 text-center space-y-8">
                        <div class="w-32 h-32 bg-primary-600 rounded-[3rem] mx-auto flex items-center justify-center text-white shadow-2xl shadow-primary-500/40 transform group-hover:scale-110 group-hover:rotate-12 transition-all duration-700">
                            <i data-lucide="shield" class="w-16 h-16"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-3xl font-black text-gray-950 dark:text-white italic tracking-tighter">SECURE ARCHITECTURE</h3>
                            <p class="text-xs font-black uppercase tracking-[0.4em] text-gray-400 italic leading-relaxed">Encrypted Student Data Matrix</p>
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute top-12 left-12 p-4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-50 dark:border-gray-700 animate-bounce-slow">
                        <i data-lucide="database" class="w-6 h-6 text-indigo-500"></i>
                    </div>
                    <div class="absolute bottom-20 right-10 p-4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-50 dark:border-gray-700 animate-pulse-slow">
                        <i data-lucide="zap" class="w-6 h-6 text-yellow-500"></i>
                    </div>
                </div>

                <!-- Decorative Background Rings -->
                <div class="absolute -top-20 -right-20 w-80 h-80 border-[40px] border-primary-500/5 rounded-full blur-[2px]"></div>
                <div class="absolute -bottom-20 -left-20 w-60 h-60 border-[30px] border-indigo-500/5 rounded-full blur-[1px]"></div>
            </div>

        </div>
    </section>

    <!-- Core Features Preview -->
    <section class="relative z-10 py-32 px-6 lg:px-20 bg-white dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-24 max-w-2xl mx-auto space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-[0.5em] text-primary-500 italic">Functional Nodes</h4>
                <h2 class="text-5xl font-black text-gray-950 dark:text-white tracking-tighter leading-none italic uppercase">System<br>Capabilities.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <!-- Feature 1 -->
                <div class="p-10 rounded-[3rem] bg-slate-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 border border-transparent hover:border-gray-100 dark:hover:border-gray-700 shadow-sm hover:shadow-2xl group">
                    <div class="w-16 h-16 rounded-2xl bg-primary-600/10 flex items-center justify-center text-primary-600 mb-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-950 dark:text-white mb-4 italic tracking-tight">Identity Hub</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Centralized biological identification and contact synchronization portal for entire student bodies.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-10 rounded-[3rem] bg-slate-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 border border-transparent hover:border-gray-100 dark:hover:border-gray-700 shadow-sm hover:shadow-2xl group">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600 mb-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-950 dark:text-white mb-4 italic tracking-tight">Request Matrix</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Seamless logic transmission for applications, requests, and institutional communication logs.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-10 rounded-[3rem] bg-slate-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 border border-transparent hover:border-gray-100 dark:hover:border-gray-700 shadow-sm hover:shadow-2xl group">
                    <div class="w-16 h-16 rounded-2xl bg-purple-600/10 flex items-center justify-center text-purple-600 mb-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-950 dark:text-white mb-4 italic tracking-tight">Core Security</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Military-grade cryptographic secret handling and institutional logic isolation protocols.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Stats / CTA -->
    <section class="relative z-10 py-32 px-6 lg:px-20 text-center">
        <div class="max-w-4xl mx-auto space-y-12">
            <h2 class="text-5xl lg:text-7xl font-black text-gray-950 dark:text-white tracking-tighter italic leading-none uppercase">Ready to enter<br>the <span class="text-primary-600">Central Portal?</span></h2>
            <div class="flex flex-wrap justify-center gap-10 opacity-30">
                 <div class="flex items-center gap-3">
                     <i data-lucide="monitor" class="w-5 h-5"></i>
                     <span class="text-xs font-black uppercase tracking-widest">Cross-Platform</span>
                 </div>
                 <div class="flex items-center gap-3">
                     <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                     <span class="text-xs font-black uppercase tracking-widest">Cloud Sync</span>
                 </div>
                 <div class="flex items-center gap-3">
                     <i data-lucide="smartphone" class="w-5 h-5"></i>
                     <span class="text-xs font-black uppercase tracking-widest">Mobile Optimized</span>
                 </div>
            </div>
            <a href="login.php" class="inline-flex px-12 py-6 bg-primary-600 hover:bg-primary-700 text-white font-black text-xl rounded-full shadow-2xl shadow-primary-500/40 transition-all hover:scale-105 active:scale-95 uppercase tracking-widest italic">
                Initialize Login Protocol
            </a>
        </div>
    </section>
</div>

<style>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-30px); }
}
@keyframes pulse-slow {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.1); }
}
.animate-bounce-slow {
    animation: bounce-slow 6s ease-in-out infinite;
}
.animate-pulse-slow {
    animation: pulse-slow 8s ease-in-out infinite;
}
</style>

<?php include 'includes/footer.php'; ?>
