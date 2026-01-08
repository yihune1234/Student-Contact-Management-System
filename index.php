<?php
require_once 'includes/config.php';
$page_title = "Welcome to Nexus SCMS";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden py-20">
    <!-- Background Decor -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-primary-500/10 blur-[120px] rounded-full -z-10 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 blur-[100px] rounded-full -z-10"></div>
    
    <div class="max-w-7xl mx-auto px-6 lg:px-20 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-10" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-[0.25em] italic"
                 :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" class="transition-all duration-700">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Security Protocol Active
            </div>
            
            <h1 class="text-5xl lg:text-7xl font-black text-gray-950 dark:text-white leading-[1.1] tracking-tighter italic"
                :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-1000 delay-100">
                Welcome to the <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-600">Student Contact</span> <br>
                Management System
            </h1>
            
            <p class="text-lg text-gray-500 dark:text-gray-400 font-bold max-w-xl leading-relaxed italic"
               :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-1000 delay-200">
                This system helps our college securely manage student profiles, contact information, and emergency details. 
                It supports fast access, accurate records, and better communication between students and the administration.
            </p>
            
            <div class="flex flex-wrap gap-6" :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-1000 delay-300">
                <a href="login.php" class="px-10 py-5 bg-primary-600 text-white font-black rounded-2xl shadow-2xl shadow-primary-500/40 hover:scale-105 active:scale-95 transition-all text-sm uppercase tracking-[0.2em] italic flex items-center gap-3">
                    Dashboard Login
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="about.php" class="px-10 py-5 bg-white dark:bg-gray-900 text-gray-950 dark:text-white border border-gray-100 dark:border-gray-800 font-black rounded-2xl shadow-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all text-sm uppercase tracking-[0.2em] italic">
                    Specifications
                </a>
            </div>
        </div>

        <!-- Visual Element -->
        <div class="relative lg:h-[600px] flex items-center justify-center">
            <div class="relative w-full max-w-md aspect-square bg-gradient-to-br from-primary-600 to-indigo-700 rounded-[3rem] shadow-2xl overflow-hidden group">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center mix-blend-overlay opacity-60 group-hover:scale-110 transition-transform duration-1000"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 to-transparent"></div>
                
                <div class="absolute bottom-10 left-10 space-y-2">
                    <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest italic">Live Statistics</p>
                    <p class="text-4xl font-black text-white italic">Optimized <br> Architecture</p>
                </div>
                
                <!-- Floating Tags -->
                <div class="absolute top-10 right-10 bg-white/10 backdrop-blur-xl border border-white/20 p-4 rounded-2xl text-white italic animate-bounce shadow-2xl">
                    <i data-lucide="zap" class="w-6 h-6 mb-2 text-yellow-400"></i>
                    <p class="text-[10px] font-black uppercase tracking-tighter">Fast Access</p>
                </div>
            </div>
            <!-- Glass Cards -->
            <div class="absolute -bottom-6 -left-6 lg:-left-12 bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl border border-white/20 p-6 rounded-3xl shadow-2xl w-56 italic">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </div>
                    <p class="text-xs font-black uppercase text-gray-500">Verified</p>
                </div>
                <p class="text-sm font-black text-gray-950 dark:text-white leading-tight">Accurate Records Management System</p>
            </div>
        </div>
    </div>
</section>

<!-- Highlights Grid -->
<section class="py-24 bg-white dark:bg-gray-950 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="text-center mb-20 space-y-4">
            <h2 class="text-[10px] font-black text-primary-600 uppercase tracking-[0.4em] italic leading-none">The Core Matrix</h2>
            <h3 class="text-3xl lg:text-5xl font-black text-gray-950 dark:text-white tracking-tighter italic uppercase">Key Highlights</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group p-10 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-transparent hover:border-primary-500/30 transition-all hover:shadow-2xl hover:shadow-primary-500/5 italic">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-primary-600 shadow-sm mb-8 group-hover:bg-primary-600 group-hover:text-white transition-all">
                    <i data-lucide="folder-key" class="w-7 h-7"></i>
                </div>
                <h4 class="text-xl font-black text-gray-950 dark:text-white mb-4 uppercase tracking-tight">Centralized Info</h4>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">
                    Well-organized student information stored in a central, high-performance database cluster.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group p-10 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-transparent hover:border-indigo-500/30 transition-all hover:shadow-2xl hover:shadow-indigo-500/5 italic">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm mb-8 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i data-lucide="phone-call" class="w-7 h-7"></i>
                </div>
                <h4 class="text-xl font-black text-gray-950 dark:text-white mb-4 uppercase tracking-tight">Updated Contacts</h4>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">
                    Always-updated phone and email contacts ensure seamless connection with every entity.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group p-10 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-transparent hover:border-red-500/30 transition-all hover:shadow-2xl hover:shadow-red-500/5 italic">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-red-600 shadow-sm mb-8 group-hover:bg-red-600 group-hover:text-white transition-all">
                    <i data-lucide="shield-alert" class="w-7 h-7"></i>
                </div>
                <h4 class="text-xl font-black text-gray-950 dark:text-white mb-4 uppercase tracking-tight">Emergency Shield</h4>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">
                    Reliable emergency contact storage for guardians and sponsors, accessible instantly when required.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="group p-10 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-transparent hover:border-emerald-500/30 transition-all hover:shadow-2xl hover:shadow-emerald-500/5 italic">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i data-lucide="layers" class="w-7 h-7"></i>
                </div>
                <h4 class="text-xl font-black text-gray-950 dark:text-white mb-4 uppercase tracking-tight">Cluster Logic</h4>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">
                    Group students by department, batch, and semester for organized academic tracking.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="group p-10 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-transparent hover:border-amber-500/30 transition-all hover:shadow-2xl hover:shadow-amber-500/5 italic">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm mb-8 group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <i data-lucide="message-square" class="w-7 h-7"></i>
                </div>
                <h4 class="text-xl font-black text-gray-950 dark:text-white mb-4 uppercase tracking-tight">Rapid Feedback</h4>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">
                    Send announcements and messages quickly via a unified communication nexus.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="group p-10 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] border border-transparent hover:border-purple-500/30 transition-all hover:shadow-2xl hover:shadow-purple-500/5 italic">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-purple-600 shadow-sm mb-8 group-hover:bg-purple-600 group-hover:text-white transition-all">
                    <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                </div>
                <h4 class="text-xl font-black text-gray-950 dark:text-white mb-4 uppercase tracking-tight">Insight Engine</h4>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 leading-relaxed">
                    Comprehensive dashboard insights for administrators to analyze data distribution and integrity.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-gray-950 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 to-indigo-900/40 -z-10"></div>
    <div class="max-w-5xl mx-auto px-6 lg:px-20 text-center italic">
        <h2 class="text-4xl lg:text-6xl font-black mb-8 uppercase tracking-tighter italic">Ready to Initialize?</h2>
        <p class="text-xl text-gray-400 font-bold mb-12 max-w-2xl mx-auto leading-relaxed">
            Access secure protocols and manage student records with the university's premier management system.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="login.php" class="w-full sm:w-auto px-12 py-6 bg-white text-gray-950 font-black rounded-3xl shadow-2xl hover:scale-105 active:scale-95 transition-all text-sm uppercase tracking-[0.25em] flex items-center justify-center gap-3">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Initiate Login
            </a>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500">Security Audit Logs Enabled</p>
        </div>
    </div>
</section>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>
