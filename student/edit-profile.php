<?php
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $profile_photo = null;

    // Handle File Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['profile_photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = "stu_" . $student_id . "_" . time() . "." . $ext;
            $upload_path = "../uploads/" . $new_name;

            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                $profile_photo = $new_name;
            } else {
                $error = "Failed to upload photo to local storage.";
            }
        } else {
            $error = "Invalid file type. Only JPG, PNG, and WebP are allowed.";
        }
    }

    if (!$error) {
        try {
            if ($profile_photo) {
                // Delete old photo if exists
                $stmt = $pdo->prepare("SELECT profile_photo FROM students WHERE student_id = ?");
                $stmt->execute([$student_id]);
                $old_photo = $stmt->fetchColumn();
                if ($old_photo && file_exists("../uploads/" . $old_photo)) {
                    unlink("../uploads/" . $old_photo);
                }

                $stmt = $pdo->prepare("UPDATE students SET phone = ?, email = ?, address = ?, profile_photo = ? WHERE student_id = ?");
                $stmt->execute([$phone, $email, $address, $profile_photo, $student_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE students SET phone = ?, email = ?, address = ? WHERE student_id = ?");
                $stmt->execute([$phone, $email, $address, $student_id]);
            }
            $success = "Profile synthesized with new data nodes.";
        } catch (Exception $e) {
            $error = "Logic update failure: " . $e->getMessage();
        }
    }
}

$stmt = $pdo->prepare("SELECT s.*, d.department_name FROM students s LEFT JOIN departments d ON s.department_id = d.id WHERE s.student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

$page_title = "Edit Profile - Student SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-gray-950/60 backdrop-blur-sm lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-primary-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto italic">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all uppercase tracking-widest">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all uppercase tracking-widest">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profile Node
            </a>
            <a href="apply.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Applications
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-black rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20 uppercase tracking-widest">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security Node
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 italic mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Logout Protocol
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Nav -->
        <header class="h-20 bg-white/70 dark:bg-gray-900/70 glass sticky top-0 z-10 px-6 lg:px-10 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4 text-center">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-primary-600 transition-all">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-base lg:text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">MODIFY ATTRIBUTES</h2>
            </div>
            
            <a href="profile.php" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-[10px] sm:text-xs font-black rounded-xl hover:bg-gray-200 transition-all uppercase tracking-widest italic border border-transparent hover:border-gray-200">
                Cancel
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10">
            <div class="max-w-4xl mx-auto w-full space-y-8">
                <?php if ($success): ?>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-6 rounded-[2rem] font-bold text-xs italic flex items-center gap-4">
                        <i data-lucide="check" class="w-6 h-6"></i>
                        <p><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 p-6 rounded-[2rem] font-bold text-xs italic flex items-center gap-4">
                        <i data-lucide="shield-alert" class="w-6 h-6 text-red-600"></i>
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] sm:rounded-[3rem] p-6 sm:p-10 lg:p-12 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                    <form action="edit-profile.php" method="POST" enctype="multipart/form-data" class="space-y-10 lg:space-y-12 relative z-10 italic">
                        
                        <!-- Profile Photo Matrix -->
                        <section class="flex flex-col items-center text-center">
                            <div class="relative">
                                <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-[2.5rem] sm:rounded-[3rem] overflow-hidden border-4 border-white dark:border-gray-800 shadow-2xl relative">
                                    <?php if ($student['profile_photo']): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover" id="photo-preview">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-300" id="photo-placeholder">
                                            <i data-lucide="user" class="w-12 h-12 sm:w-16 sm:h-16"></i>
                                        </div>
                                        <img src="" class="w-full h-full object-cover hidden" id="photo-preview">
                                    <?php endif; ?>
                                </div>
                                <label class="absolute -bottom-2 -right-2 w-10 h-10 sm:w-12 sm:h-12 bg-primary-600 text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-xl hover:scale-110 active:scale-95 transition-all">
                                    <i data-lucide="camera" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                                    <input type="file" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <p class="mt-6 text-[9px] font-black uppercase tracking-[0.3em] text-gray-400 italic">Biological Identification Vector</p>
                        </section>

                        <section class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10 border-t border-gray-50 dark:border-gray-800 pt-10 sm:pt-12">
                            <div class="space-y-8">
                                <h4 class="text-[10px] sm:text-xs font-black uppercase tracking-[0.3em] text-primary-500 italic">Static Attributes</h4>
                                <div class="space-y-6 opacity-60">
                                    <div class="p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-transparent shadow-inner">
                                        <p class="text-[8px] font-black uppercase text-gray-400 mb-1 tracking-widest leading-none">Entity Identifier</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight"><?php echo htmlspecialchars($student['student_id']); ?></p>
                                    </div>
                                    <div class="p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-transparent shadow-inner">
                                        <p class="text-[8px] font-black uppercase text-gray-400 mb-1 tracking-widest leading-none">Full Synchronized Name</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white truncate"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-8">
                                <h4 class="text-[10px] sm:text-xs font-black uppercase tracking-[0.3em] text-primary-500 italic">Synchronous Nodes</h4>
                                <div class="space-y-6">
                                    <div class="group">
                                        <label class="block text-[8px] font-black uppercase text-gray-400 mb-3 ml-2 group-focus-within:text-primary-600 transition-colors tracking-widest italic">Electronic Mail Address</label>
                                        <div class="relative">
                                            <i data-lucide="at-sign" class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300 group-focus-within:text-primary-600 transition-all"></i>
                                            <input type="email" name="email" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-600 transition-all font-bold text-sm rounded-2xl outline-none" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="group">
                                        <label class="block text-[8px] font-black uppercase text-gray-400 mb-3 ml-2 group-focus-within:text-primary-600 transition-colors tracking-widest italic">Signal Transmission Line</label>
                                        <div class="relative">
                                            <i data-lucide="phone" class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300 group-focus-within:text-primary-600 transition-all"></i>
                                            <input type="text" name="phone" class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-800 border border-transparent focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-600 transition-all font-bold text-sm rounded-2xl outline-none" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="space-y-6">
                            <h4 class="text-[10px] sm:text-xs font-black uppercase tracking-[0.3em] text-primary-500 italic">Physical Spatial Coordinates</h4>
                            <div class="relative group">
                                <i data-lucide="map-pin" class="absolute left-6 top-6 w-5 h-5 text-gray-300 group-focus-within:text-primary-600 transition-all"></i>
                                <textarea name="address" rows="3" class="w-full pl-16 pr-8 py-6 bg-gray-50 dark:bg-gray-800 border border-transparent focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-600 transition-all font-bold text-sm rounded-[2rem] outline-none" placeholder="Provide geographical address for the registry..."><?php echo htmlspecialchars($student['address']); ?></textarea>
                            </div>
                        </section>

                        <div class="pt-6 sm:pt-8">
                            <button type="submit" class="w-full py-6 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.03] active:scale-[0.98] uppercase tracking-[0.3em] text-xs sm:text-sm italic flex items-center justify-center gap-4 group">
                                <span>Commit Repository Revisions</span>
                                <i data-lucide="zap" class="w-5 h-5 group-hover:fill-current group-hover:scale-125 transition-all"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-preview').src = e.target.result;
            document.getElementById('photo-preview').classList.remove('hidden');
            if (document.getElementById('photo-placeholder')) {
                document.getElementById('photo-placeholder').classList.add('hidden');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../includes/footer.php'; ?>
