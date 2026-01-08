<?php
require_once 'includes/config.php';
$page_title = "About Us - SCMS Architecture";
include 'includes/header.php';
?>

<section class="min-h-[calc(100vh-80px)] py-20 px-10">
    <div class="max-w-6xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-20 items-center">
            <div>
                <h4 class="text-xs font-black uppercase tracking-[0.5em] text-primary-500 mb-6 italic">Engineering Excellence</h4>
                <h1 class="text-5xl lg:text-7xl font-black text-gray-900 dark:text-white leading-none tracking-tighter mb-10">CORE PURPOSE &<br><span class="text-primary-600">MISSION.</span></h1>
                
                <div class="space-y-8 text-gray-600 dark:text-gray-400 font-medium leading-relaxed italic">
                    <p>
                        The Student Contact Management System (SCMS) is a next-generation administrative matrix designed to synchronize student records with institutional intelligence. Our architecture focuses on high-fidelity data integrity and frictionless user interaction.
                    </p>
                    <p>
                        Developed with modern cryptographic standards and a high-contrast visual language, SCMS provides a secure environment for students to manage their academic identity while allowing administrators to maintain complete operational oversight.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-2 gap-10">
                    <div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">0.02ms</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Response Latency</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">99.9%</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">System Uptime</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-[4rem] aspect-square overflow-hidden border-8 border-white dark:border-gray-950 shadow-2xl relative z-10">
                     <div class="absolute inset-0 bg-gradient-to-tr from-primary-600/20 to-purple-600/20"></div>
                     <div class="flex flex-col items-center justify-center h-full p-20 text-center">
                         <i data-lucide="shield-check" class="w-24 h-24 text-primary-600 mb-10"></i>
                         <p class="text-xs font-black uppercase tracking-[0.5em] text-gray-400 mb-4">Security Protocol</p>
                         <p class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter leading-tight italic">ENCRYPTED PORTAL FOR ACADEMIC LOGISTICS</p>
                     </div>
                </div>
                <!-- Decoration -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary-600/30 rounded-full blur-[80px]"></div>
                <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-purple-600/20 rounded-full blur-[100px]"></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
