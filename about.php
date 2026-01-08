<?php
require_once 'includes/config.php';
$page_title = "Platform Specifications - Nexus SCMS";
include 'includes/header.php';
?>

<!-- About Hero -->
<section class="py-24 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-8 italic text-left">
                <h2 class="text-[10px] font-black text-primary-600 uppercase tracking-[0.4em] mb-4">The Specification</h2>
                <h1 class="text-4xl lg:text-6xl font-black text-gray-950 dark:text-white leading-tight tracking-tighter uppercase italic">
                    About the <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-600 font-black">Student Contact</span> <br>
                    Management System
                </h1>
                <div class="flex items-center gap-6 p-8 bg-white dark:bg-gray-950 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-xl overflow-hidden relative group">
                    <div class="absolute top-0 left-0 w-2 h-full bg-primary-600"></div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Defining the System</p>
                        <p class="text-lg font-black text-gray-950 dark:text-white leading-relaxed">
                            A digital platform designed to store, update, and organize student information inside the college. 
                            It replaces paper files and scattered spreadsheets with one secure online architecture.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="relative flex justify-center">
                <div class="w-full max-w-md aspect-square rounded-[3rem] bg-gray-200 dark:bg-gray-800 overflow-hidden relative shadow-2xl">
                    <!-- Premium Visual Replaceable with real images -->
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1454165833767-027ffea7028b?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-70"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/40 to-transparent"></div>
                    
                    <div class="absolute bottom-8 left-8 right-8 p-6 bg-white/20 backdrop-blur-3xl border border-white/20 rounded-3xl text-white italic">
                        <p class="text-xs font-black uppercase tracking-[0.2em] mb-1">Architecture v2.0</p>
                        <p class="text-lg font-black leading-tight uppercase">High Availability <br> Data Clusters</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Provision -->
<section class="py-24 bg-white dark:bg-gray-950 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="mb-20 space-y-4">
            <h2 class="text-4xl font-black text-gray-950 dark:text-white uppercase tracking-tighter italic">Operational Capabilities</h2>
            <p class="text-gray-500 font-bold italic uppercase tracking-widest text-[10px]">What the protocol provides</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php 
            $provisions = [
                ['icon' => 'user-square', 'title' => 'Personal Profiles', 'desc' => 'Complete student personal profile management.'],
                ['icon' => 'shield-check', 'title' => 'Emergency Contacts', 'desc' => 'Guardian and emergency contacts storage.'],
                ['icon' => 'book-open', 'title' => 'Academic Alignment', 'desc' => 'Department, program, batch, and semester tracking.'],
                ['icon' => 'map-pin', 'title' => 'Geo-Location', 'desc' => 'Location tracking for all enrolled students.'],
                ['icon' => 'search', 'title' => 'Staff Tools', 'desc' => 'Search and filter tools for staff access.'],
                ['icon' => 'radio', 'title' => 'Messaging Nexus', 'desc' => 'Unified messaging and announcement support.'],
                ['icon' => 'pie-chart', 'title' => 'Data Reports', 'desc' => 'Detailed reports and statistics for decisions.'],
                ['icon' => 'globe', 'title' => 'Remote Access', 'desc' => 'Secure access from anywhere, anytime.']
            ];
            foreach($provisions as $p):
            ?>
            <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 hover:border-primary-500/40 transition-all italic h-full">
                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-primary-600 mb-6 shadow-sm border border-gray-100 dark:border-gray-800">
                    <i data-lucide="<?php echo $p['icon']; ?>" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-black text-gray-950 dark:text-white mb-3 uppercase tracking-tight"><?php echo $p['title']; ?></h4>
                <p class="text-[11px] font-bold text-gray-400 leading-relaxed"><?php echo $p['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Logic Users & Rationale -->
<section class="py-24 bg-gray-50 dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-20 grid grid-cols-1 lg:grid-cols-2 gap-20">
        <!-- Who Uses It -->
        <div class="space-y-12 italic">
            <div>
                <h3 class="text-3xl font-black text-gray-950 dark:text-white uppercase tracking-tighter mb-8 italic">Authorized Nodes</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-6 bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white"><i data-lucide="shield-alert" class="w-5 h-5"></i></div>
                        <p class="font-black text-gray-950 dark:text-white uppercase tracking-tight text-sm">Administrators and Registrars</p>
                    </div>
                    <div class="flex items-center gap-4 p-6 bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white"><i data-lucide="graduation-cap" class="w-5 h-5"></i></div>
                        <p class="font-black text-gray-950 dark:text-white uppercase tracking-tight text-sm">Department Officers</p>
                    </div>
                    <div class="flex items-center gap-4 p-6 bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm opacity-60">
                        <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center text-white"><i data-lucide="users" class="w-5 h-5"></i></div>
                        <p class="font-black text-gray-950 dark:text-white uppercase tracking-tight text-sm">Students (Limited Access)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why It Matters -->
        <div class="space-y-12 italic">
             <div>
                <h3 class="text-3xl font-black text-gray-950 dark:text-white uppercase tracking-tighter mb-8 italic">Mission Rationale</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <p class="text-xs font-black text-primary-600 uppercase tracking-widest">Temporal Efficiency</p>
                        <p class="text-sm font-bold text-gray-500">Helps save time retrieving student information for academic operations.</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-black text-red-600 uppercase tracking-widest">Critical Response</p>
                        <p class="text-sm font-bold text-gray-500">Enables quick communication in urgent or emergency situations.</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">Data Precision</p>
                        <p class="text-sm font-bold text-gray-500">Improves accuracy of college records through central validation.</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-black text-amber-600 uppercase tracking-widest">Global Ingress</p>
                        <p class="text-sm font-bold text-gray-500">Makes student data accessible anytime, anywhere via secure web protocols.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Closing -->
<section class="py-24 bg-white dark:bg-gray-950 text-center italic">
    <div class="max-w-4xl mx-auto px-6">
        <h4 class="text-2xl lg:text-3xl font-black text-gray-950 dark:text-white mb-8 leading-tight tracking-tighter uppercase italic">
            Built to support smooth academic operations and to strengthen communication between the college and its students.
        </h4>
        <div class="w-20 h-1 bg-primary-600 mx-auto rounded-full"></div>
    </div>
</section>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>
