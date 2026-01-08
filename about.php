<?php
require_once 'includes/config.php';
$page_title = "Vision & Architecture - SCMS Institutional Nexus";
include 'includes/header.php';
?>

<div class="relative min-h-screen overflow-hidden bg-slate-50 dark:bg-gray-950 font-sans selection:bg-primary-500/30">
    <!-- Ambient Neural Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[50%] h-[50%] bg-primary-600/5 rounded-full blur-[150px] animate-pulse-slow"></div>
        <div class="absolute bottom-0 left-0 w-[40%] h-[40%] bg-indigo-600/5 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.08]" style="background-image: radial-gradient(#444 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <!-- Hero Section -->
    <section class="relative z-10 pt-32 pb-20 px-6 lg:px-20 max-w-7xl mx-auto">
        <div class="mb-24 space-y-8">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-primary-600/10 border border-primary-500/20 rounded-full">
                <span class="w-2 h-2 rounded-full bg-primary-600 animate-ping"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-primary-600 italic">Institutional Dossier v2.0</span>
            </div>
            <h1 class="text-6xl lg:text-9xl font-black text-gray-950 dark:text-white tracking-tighter leading-[0.85] italic uppercase">
                The Nexus of<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-500">Intelligence.</span>
            </h1>
            <p class="text-xl lg:text-3xl text-gray-500 dark:text-gray-400 font-medium italic max-w-3xl leading-relaxed">
                SCMS is the definitive architectural solution for synchronizing student identities and institutional communication loops.
            </p>
        </div>

        <!-- Vision & Mission Matrix -->
        <div class="grid lg:grid-cols-2 gap-10 mb-32">
            <div class="bg-white dark:bg-gray-900 p-12 rounded-[3.5rem] border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-600/5 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-primary-600/10 transition-colors"></div>
                <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-primary-500 mb-8 italic">Project Vision</h3>
                <p class="text-2xl font-black text-gray-950 dark:text-white italic tracking-tight leading-snug">
                    To become the global gold standard for <span class="text-primary-600">frictionless student information flow</span>, where data is not just stored, but intelligently synchronized across all institutional touchpoints.
                </p>
            </div>
            <div class="bg-gray-950 p-12 rounded-[3.5rem] text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-600/10 rounded-full blur-2xl -ml-16 -mb-16"></div>
                <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-indigo-400 mb-8 italic">Project Mission</h3>
                <p class="text-2xl font-black italic tracking-tight leading-snug">
                    Our mission is to empower educational institutions with <span class="text-indigo-400">high-fidelity digital infrastructure</span> that protects student privacy while maximizing administrative transparency and speed.
                </p>
            </div>
        </div>

        <!-- Advanced Features Matrix -->
        <div class="space-y-16">
            <div class="text-center space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-[0.5em] text-primary-500 italic">Functional Ecosystem</h4>
                <h2 class="text-5xl font-black text-gray-950 dark:text-white tracking-tighter italic uppercase">Feature Set.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="activity" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-950 dark:text-white mb-4 italic uppercase tracking-tight">Request Logs</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic leading-relaxed">A specialized transmission engine for students to send signals (applications) directly to the central administrative core.</p>
                </div>

                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/20 rounded-2xl flex items-center justify-center text-purple-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="fingerprint" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-950 dark:text-white mb-4 italic uppercase tracking-tight">Identity Vault</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic leading-relaxed">Encrypted storage for biological identification photos and contact nodes, ensuring zero-leak institutional security.</p>
                </div>

                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-14 h-14 bg-orange-50 dark:bg-orange-900/20 rounded-2xl flex items-center justify-center text-orange-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="database" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-950 dark:text-white mb-4 italic uppercase tracking-tight">CSV Extraction</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic leading-relaxed">One-click digital extraction of student databases for physical archival or advanced institutional analytics.</p>
                </div>

                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-14 h-14 bg-green-50 dark:bg-green-900/20 rounded-2xl flex items-center justify-center text-green-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="layers" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-950 dark:text-white mb-4 italic uppercase tracking-tight">Unit Mapping</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic leading-relaxed">Sophisticated departmental mapping that isolates and routes students to their specific institutional nodes.</p>
                </div>

                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center text-indigo-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="smartphone" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-950 dark:text-white mb-4 italic uppercase tracking-tight">Adaptive UI</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic leading-relaxed">A precision-engineered interface that reconfigures its logic matrix for mobile, tablet, and desktop environments.</p>
                </div>

                <div class="p-10 bg-white dark:bg-gray-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-14 h-14 bg-red-50 dark:bg-red-900/20 rounded-2xl flex items-center justify-center text-red-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="shield-check" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-950 dark:text-white mb-4 italic uppercase tracking-tight">Security Node</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium italic leading-relaxed">Multi-layer role authentication providing separate operational environments for Students and Administrators.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Technology Core Section -->
    <section class="relative z-10 py-32 px-6 lg:px-20 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-20 items-center">
            <div class="space-y-12">
                <div class="space-y-4">
                    <h2 class="text-5xl font-black text-gray-950 dark:text-white tracking-tighter italic uppercase">Technical Power.</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium italic text-lg">The engine behind the SCMS institution.</p>
                </div>
                
                <div class="space-y-10">
                    <div class="flex gap-6 italic group">
                        <div class="w-12 h-12 bg-primary-600/10 rounded-2xl flex items-center justify-center text-primary-600 flex-shrink-0 shadow-inner group-hover:bg-primary-600 group-hover:text-white transition-all">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-950 dark:text-white uppercase tracking-widest mb-1">PDO Relational Engine</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Secures the SCMS database against injection attacks while providing sub-millisecond query performance.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 italic group">
                        <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 flex-shrink-0 shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <i data-lucide="code-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-950 dark:text-white uppercase tracking-widest mb-1">Tailwind Logic Matrix</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">A utility-first CSS framework ensuring SCMS remains lightweight, fast, and visually consistent across all portals.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 italic group">
                        <div class="w-12 h-12 bg-purple-600/10 rounded-2xl flex items-center justify-center text-purple-600 flex-shrink-0 shadow-inner group-hover:bg-purple-600 group-hover:text-white transition-all">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-950 dark:text-white uppercase tracking-widest mb-1">Alpine Node State</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Powers interactive UI elements with zero performance overhead, keeping the interface snappy and alive.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="bg-gray-950 p-16 rounded-[4rem] text-center space-y-8 relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-primary-600/10 animate-pulse"></div>
                    <div class="relative z-10 w-24 h-24 bg-white/10 rounded-[2.5rem] flex items-center justify-center mx-auto text-primary-500 border border-white/10 shadow-2xl">
                        <i data-lucide="shield-check" class="w-12 h-12"></i>
                    </div>
                    <div class="relative z-10 space-y-2">
                        <h3 class="text-3xl font-black text-white italic tracking-tighter uppercase leading-none">Ready for<br>Deployment.</h3>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.5em] italic">Institutional Clearance: GRANTED</p>
                    </div>
                    <a href="login.php" class="relative z-10 inline-block px-12 py-5 bg-primary-600 text-white font-black text-sm rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all uppercase tracking-widest italic group">
                        Enter Matrix
                    </a>
                </div>
                <!-- Decorative Orbit -->
                <div class="absolute -inset-10 border border-primary-500/20 rounded-[5rem] animate-spin-slow pointer-events-none"></div>
                <div class="absolute -inset-20 border border-indigo-500/10 rounded-[6rem] animate-spin-reverse pointer-events-none"></div>
            </div>
        </div>
    </section>
</div>

<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.1); }
}
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
@keyframes spin-reverse {
    from { transform: rotate(360deg); }
    to { transform: rotate(0deg); }
}
.animate-pulse-slow {
    animation: pulse-slow 8s ease-in-out infinite;
}
.animate-spin-slow {
    animation: spin-slow 20s linear infinite;
}
.animate-spin-reverse {
    animation: spin-reverse 25s linear infinite;
}
</style>

<?php include 'includes/footer.php'; ?>
