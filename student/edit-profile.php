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

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar (Omitted for brevity, using existing sidebar logic) -->
    <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 hidden md:flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 text-primary-600 dark:text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                <span>SCMS</span>
            </div>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profile Node
            </a>
            <a href="edit-profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Edit Contact
            </a>
            <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Security
            </a>
        </nav>
        <div class="p-4 border-t border-gray-50 dark:border-gray-700">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-gray-950 flex flex-col">
        <header class="h-20 bg-white/70 dark:bg-gray-900/70 glass sticky top-0 z-10 px-10 flex items-center justify-between">
            <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">MODIFY ENTITY ATTRIBUTES</h2>
            <a href="profile.php" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-black rounded-xl hover:bg-gray-200 transition-all uppercase tracking-widest">
                Cancel
            </a>
        </header>

        <div class="p-10 max-w-4xl mx-auto w-full">
            <?php if ($success): ?>
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 text-green-600 p-5 rounded-2xl mb-8 font-bold text-xs italic">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 text-red-600 p-5 rounded-2xl mb-8 font-bold text-xs italic">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-12 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <form action="edit-profile.php" method="POST" enctype="multipart/form-data" class="space-y-12 relative z-10 italic">
                    
                    <!-- Profile Photo Selection -->
                    <section class="flex flex-col items-center text-center">
                        <div class="relative group">
                            <div class="w-40 h-40 rounded-[3rem] overflow-hidden border-4 border-white dark:border-gray-800 shadow-2xl relative">
                                <?php if ($student['profile_photo']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($student['profile_photo']); ?>" class="w-full h-full object-cover" id="photo-preview">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400" id="photo-placeholder">
                                        <i data-lucide="user" class="w-16 h-16"></i>
                                    </div>
                                    <img src="" class="w-full h-full object-cover hidden" id="photo-preview">
                                <?php endif; ?>
                            </div>
                            <label class="absolute bottom-2 right-2 w-12 h-12 bg-primary-600 text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-xl hover:scale-110 active:scale-95 transition-all">
                                <i data-lucide="camera" class="w-6 h-6"></i>
                                <input type="file" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        <p class="mt-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Biological Identification Vector</p>
                    </section>

                    <section class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-6 italic">Permanent Logic</h4>
                            <div class="space-y-4 opacity-50">
                                <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl">
                                    <p class="text-[8px] font-black uppercase text-gray-400 mb-1">Entity ID</p>
                                    <p class="font-bold text-gray-950 dark:text-white uppercase"><?php echo htmlspecialchars($student['student_id']); ?></p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl">
                                    <p class="text-[8px] font-black uppercase text-gray-400 mb-1">Full Legal Name</p>
                                    <p class="font-bold text-gray-950 dark:text-white"><?php echo htmlspecialchars($student['full_name']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-6 italic">Communication Ports</h4>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Phone Matrix</label>
                                    <input type="text" name="phone" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" value="<?php echo htmlspecialchars($student['phone']); ?>">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1">Digital Mail</label>
                                    <input type="email" name="email" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold" value="<?php echo htmlspecialchars($student['email']); ?>">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-6 italic">Physical Coordinates</h4>
                        <textarea name="address" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 transition-all font-bold"><?php echo htmlspecialchars($student['address']); ?></textarea>
                    </section>

                    <button type="submit" class="w-full py-5 bg-primary-600 text-white font-black rounded-3xl shadow-2xl transition-all hover:scale-[1.02] active:scale-[0.98] uppercase tracking-widest text-sm italic">
                        Commit Repository Revisions
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

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
