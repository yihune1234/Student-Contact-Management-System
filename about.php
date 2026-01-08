<?php
require_once 'includes/config.php';
$page_title = "Technical Specifications - SCMS Architecture";
include 'includes/header.php';
?>

<div class="relative min-h-screen overflow-hidden bg-slate-50 dark:bg-gray-950 font-sans selection:bg-primary-500/30">
    <!-- Background Decor (Consistent with Home) -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-[20%] left-[60%] w-[40%] h-[40%] bg-primary-600/10 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 30px 30px;"></div>
    </div>

    <section class="relative z-10 pt-32 pb-24 px-6 lg:px-20 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-24 space-y-6">
            <h4 class="text-[10px] font-black uppercase tracking-[0.5em] text-primary-500 italic">Core Mission & Logic</h4>
            <h1 class="text-6xl lg:text-8xl font-black text-gray-950 dark:text-white tracking-tighter leading-none italic uppercase">
                Architecture<br>of <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-500">Excellence.</span>
            </h1>
        </div>

        <div class="grid lg:grid-cols-12 gap-16 lg:gap-24">
            <!-- Left Side: Main Narrative -->
            <div class="lg:col-span-7 space-y-12">
                <div class="space-y-8 text-xl lg:text-2xl text-gray-600 dark:text-gray-400 font-medium leading-relaxed italic">
                    <p>
                        The Student Contact Management System (SCMS) is engineered to be the definitive institutional node for student information synchronization.
                    </p>
                    <p>
                        By combining high-fidelity administrative logic with a frictionless user interface, SCMS eliminates the traditional bottlenecks of student data management.
                    </p>
                </div>

                <!-- Spec Grid -->
                <div class="grid sm:grid-cols-2 gap-8 pt-10">
                    <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                        <i data-lucide="zap" class="w-8 h-8 text-yellow-500 mb-6"></i>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white mb-2 italic">Low Latency</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Optimized SQL queries ensure sub-0.05ms record retrieval times.</p>
                    </div>
                    <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                        <i data-lucide="lock" class="w-8 h-8 text-primary-600 mb-6"></i>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white mb-2 italic">Atomic Security</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Role-based access controls and encrypted secret handling protocols.</p>
                    </div>
                    <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                        <i data-lucide="layers" class="w-8 h-8 text-indigo-500 mb-6"></i>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white mb-2 italic">Modular Core</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Scalable departmental nodes allowing for infinite institutional growth.</p>
                    </div>
                    <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                        <i data-lucide="cpu" class="w-8 h-8 text-purple-600 mb-6"></i>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white mb-2 italic">Automated Sync</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed italic">Real-time profile updates across all administrative access points.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Stats / Visuals -->
            <div class="lg:col-span-5 space-y-12">
                <div class="bg-gray-950 p-12 rounded-[4rem] text-white space-y-10 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-primary-600/10 group-hover:bg-primary-600/20 transition-all"></div>
                    
                    <div class="relative z-10 space-y-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-primary-500 italic">Operational Status</h3>
                        <p class="text-4xl font-black italic tracking-tighter leading-none">SYSTEM<br>UPTIME: 99.9%</p>
                    </div>

                    <div class="relative z-10 pt-10 border-t border-white/10 space-y-8">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Database Integrity</span>
                            <span class="text-xs font-black text-primary-500 uppercase tracking-widest">Verified</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Logic Synchronization</span>
                            <span class="text-xs font-black text-primary-500 uppercase tracking-widest">Active</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Encrypted Tunnels</span>
                            <span class="text-xs font-black text-primary-500 uppercase tracking-widest">Enabled</span>
                        </div>
                    </div>

                    <div class="relative z-10 pt-10">
                        <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                            <div class="w-[85%] h-full bg-primary-600 animate-pulse"></div>
                        </div>
                        <p class="mt-4 text-[8px] font-black uppercase tracking-widest text-center text-gray-500">Institutional Processing Load: Nominal</p>
                    </div>
                </div>

                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-xl space-y-8 text-center italic">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-3xl mx-auto flex items-center justify-center text-primary-600">
                        <i data-lucide="graduation-cap" class="w-10 h-10"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed">SCMS is more than a database; it is the institutional nervous system for student-faculty interaction.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Tech Stack Block -->
    <section class="relative z-10 py-24 px-6 lg:px-20 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-primary-600/5 rounded-full blur-[100px] -mr-48 -mt-48"></div>
        
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-20 items-center">
             <div>
                 <h2 class="text-4xl font-black text-gray-950 dark:text-white tracking-tighter italic uppercase mb-8">Technical Matrix.</h2>
                 <div class="space-y-6">
                     <div class="flex gap-4">
                         <div class="w-6 h-6 rounded-full bg-primary-600/20 flex items-center justify-center mt-1">
                             <div class="w-2 h-2 rounded-full bg-primary-600"></div>
                         </div>
                         <div>
                             <p class="font-black text-gray-950 dark:text-white uppercase text-xs italic mb-1">Backend Logic</p>
                             <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic">High-performance PHP with PDO abstraction layers for bulletproof record handling.</p>
                         </div>
                     </div>
                     <div class="flex gap-4">
                         <div class="w-6 h-6 rounded-full bg-indigo-600/20 flex items-center justify-center mt-1">
                             <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                         </div>
                         <div>
                             <p class="font-black text-gray-950 dark:text-white uppercase text-xs italic mb-1">Data Storage</p>
                             <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic">MySQL relational database system optimized for institutional-scale indexing.</p>
                         </div>
                     </div>
                     <div class="flex gap-4">
                         <div class="w-6 h-6 rounded-full bg-purple-600/20 flex items-center justify-center mt-1">
                             <div class="w-2 h-2 rounded-full bg-purple-600"></div>
                         </div>
                         <div>
                             <p class="font-black text-gray-950 dark:text-white uppercase text-xs italic mb-1">Interface Engine</p>
                             <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic">Tailwind CSS utility matrix ensuring ultra-responsive high-fidelity visuals.</p>
                         </div>
                     </div>
                 </div>
             </div>
             
             <div class="bg-slate-50 dark:bg-gray-800/30 p-12 rounded-[4rem] border border-dashed border-gray-200 dark:border-gray-700 text-center space-y-8">
                 <i data-lucide="shield-check" class="w-16 h-16 text-primary-500 mx-auto opacity-50"></i>
                 <p class="text-xs font-black uppercase tracking-[0.3em] text-gray-400 italic">Security Audit: PASS</p>
                 <h3 class="text-2xl font-black text-gray-950 dark:text-white italic tracking-tighter uppercase">Ready for Deployment</h3>
                 <a href="login.php" class="inline-block px-10 py-4 bg-primary-600 text-white font-black text-sm rounded-2xl shadow-xl hover:scale-105 transition-all uppercase tracking-widest">
                     Enter Gateway
                 </a>
             </div>
        </div>
    </section>
</div>

<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.1); }
}
.animate-pulse-slow {
    animation: pulse-slow 8s ease-in-out infinite;
}
</style>

<?php include 'includes/footer.php'; ?>
