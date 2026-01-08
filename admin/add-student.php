<?php
require_once '../includes/config.php';

// Role Check
$allowed_roles = ['admin', 'registrar', 'department officer'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Fetch all required master data for dropdowns
$faculties = $pdo->query("SELECT * FROM faculties ORDER BY faculty_name ASC")->fetchAll();
$departments = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll();
$programs = $pdo->query("SELECT * FROM programs ORDER BY program_name ASC")->fetchAll();
$academic_years = $pdo->query("SELECT * FROM academic_years ORDER BY year_label DESC")->fetchAll();
$semesters = $pdo->query("SELECT * FROM semesters ORDER BY id ASC")->fetchAll();
$sections = $pdo->query("SELECT * FROM sections ORDER BY section_name ASC")->fetchAll();
$advisors = $pdo->query("SELECT * FROM advisors ORDER BY full_name ASC")->fetchAll();
$statuses = $pdo->query("SELECT * FROM enrollment_statuses ORDER BY id ASC")->fetchAll();
$roles = $pdo->query("SELECT * FROM roles ORDER BY role_name ASC")->fetchAll();

// Fetch location data (simplified for now, usually AJAX would handle deep cascades)
$kebeles = $pdo->query("SELECT k.*, w.name as woreda_name FROM kebeles k JOIN woredas w ON k.woreda_id = w.id ORDER BY k.name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_id = $_POST['role_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    
    // Academic
    $student_id = $_POST['student_id'] ?? null;
    $dept_id = $_POST['department_id'] ?? null;
    $prog_id = $_POST['program_id'] ?? null;
    $batch_id = $_POST['batch_id'] ?? null;
    $sem_id = $_POST['semester_id'] ?? null;
    $sec_id = $_POST['section_id'] ?? null;
    $adv_id = $_POST['advisor_id'] ?? null;
    $estatus_id = $_POST['enrollment_status_id'] ?? null;

    // Contact & Location
    $phone = $_POST['phone'] ?? null;
    $secondary_phone = $_POST['secondary_phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $kebele_id = $_POST['kebele_id'] ?? null;
    $address_detail = $_POST['address_detail'] ?? null;

    // Guardian
    $g_name = $_POST['g_full_name'] ?? null;
    $g_relation = $_POST['g_relation'] ?? null;
    $g_phone = $_POST['g_phone'] ?? null;
    $g_email = $_POST['g_email'] ?? null;
    $g_address = $_POST['g_address'] ?? null;

    if (empty($username) || empty($password) || empty($full_name)) {
        $error = "Base identity logic requires Name, Username and Password.";
    } else {
        try {
            $pdo->beginTransaction();
            
            // 1. Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                throw new Exception("Proposed username already exists in registry.");
            }

            $current_student_internal_id = null;

            // 2. Insert Student if role is Student
            $target_role = $pdo->query("SELECT role_name FROM roles WHERE id = $role_id")->fetchColumn();
            
            if (strtolower($target_role) === 'student') {
                if (empty($student_id)) throw new Exception("Student Serial ID is required for student entities.");
                
                $sql = "INSERT INTO students (student_id, full_name, gender, nationality, email, phone, secondary_phone, 
                        department_id, program_id, batch_id, semester_id, section_id, advisor_id, enrollment_status_id, 
                        kebele_id, address_detail) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $student_id, $full_name, $gender, $nationality, $email, $phone, $secondary_phone,
                    $dept_id, $prog_id, $batch_id, $sem_id, $sec_id, $adv_id, $estatus_id,
                    $kebele_id, $address_detail
                ]);
                $current_student_internal_id = $pdo->lastInsertId();

                // 3. Insert Guardian
                if (!empty($g_name)) {
                    $stmt = $pdo->prepare("INSERT INTO guardians (student_id, relation, full_name, phone, email, address) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$current_student_internal_id, $g_relation, $g_name, $g_phone, $g_email, $g_address]);
                }
            }

            // 4. Create User Account
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id, student_link_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_pass, $role_id, $current_student_internal_id]);

            $pdo->commit();
            $success = "New entry successfully synthesized and linked.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Synthesis Failure: " . $e->getMessage();
        }
    }
}

$page_title = "Entity Ingress - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter italic">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] italic">Core Modules</div>
        <nav class="flex-1 px-4 space-y-1 mb-8">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-primary-600 text-white shadow-lg shadow-primary-500/20">
                <i data-lucide="users" class="w-5 h-5"></i>
                Manage Students
            </a>
        </nav>
        <div class="p-6 border-t border-white/5 mt-auto">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-red-400 hover:bg-red-400/10 transition-all">
                <i data-lucide="power" class="w-5 h-5"></i>
                Exit Protocol
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto flex flex-col">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight italic">ENTITY SYNTHESIS</h2>
            </div>
            <a href="students.php" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-[10px] font-black rounded-xl uppercase tracking-widest italic">Return to Registry</a>
        </header>

        <div class="p-4 sm:p-10 max-w-5xl mx-auto w-full">
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-[2rem] mb-10 font-bold text-sm italic flex items-center gap-4">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-50 border border-green-100 text-green-600 p-6 rounded-[2rem] mb-10 font-bold text-sm italic flex items-center gap-4">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="add-student.php" method="POST" class="space-y-10 pb-20 italic">
                <!-- Step 1: Accountability & Access -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm space-y-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white"><i data-lucide="lock" class="w-5 h-5"></i></div>
                        <h4 class="text-lg font-black text-gray-950 dark:text-white uppercase tracking-tight">Security & Role Assignment</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Logic Class (Role)</label>
                            <select name="role_id" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <?php foreach($roles as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" <?php echo strtolower($r['role_name']) == 'student' ? 'selected' : ''; ?>><?php echo htmlspecialchars($r['role_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Identity Handle (Username)</label>
                            <input type="text" name="username" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Initial Security Secret (Password)</label>
                        <input type="password" name="password" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                    </div>
                </div>

                <!-- Step 2: Personal Profile -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm space-y-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white"><i data-lucide="user" class="w-5 h-5"></i></div>
                        <h4 class="text-lg font-black text-gray-950 dark:text-white uppercase tracking-tight">Personal Identity Profile</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Full Legal Designation (Name)</label>
                            <input type="text" name="full_name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Gender</label>
                            <select name="gender" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Nationality / Citizenry</label>
                        <input type="text" name="nationality" value="Ethiopian" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                    </div>
                </div>

                <!-- Step 3: Academic Placement (Only for Student Role) -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm space-y-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center text-white"><i data-lucide="graduation-cap" class="w-5 h-5"></i></div>
                        <h4 class="text-lg font-black text-gray-950 dark:text-white uppercase tracking-tight">Academic Calibration</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Student Serial ID*</label>
                            <input type="text" name="student_id" placeholder="e.g. ETS/1234/15" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Departmental Node</label>
                            <select name="department_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <option value="">Select Department</option>
                                <?php foreach($departments as $d): ?>
                                    <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['department_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Academic Program</label>
                            <select name="program_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <option value="">Select Program</option>
                                <?php foreach($programs as $p): ?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['program_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Batch / Entry Year</label>
                            <select name="batch_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <option value="">Select Batch</option>
                                <?php foreach($academic_years as $ay): ?>
                                    <option value="<?php echo $ay['id']; ?>"><?php echo htmlspecialchars($ay['year_label']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Current Semester</label>
                            <select name="semester_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <?php foreach($semesters as $s): ?>
                                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['semester_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Connectivity & Geo-Location -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm space-y-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white"><i data-lucide="map-pin" class="w-5 h-5"></i></div>
                        <h4 class="text-lg font-black text-gray-950 dark:text-white uppercase tracking-tight">Connectivity & Geo-Location</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Primary Mobile Port</label>
                            <input type="text" name="phone" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Electronic Mail Address</label>
                            <input type="email" name="email" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Local Kebele / Residential Area</label>
                        <select name="kebele_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                            <option value="">Select Location</option>
                            <?php foreach($kebeles as $k): ?>
                                <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['name'] . ' (' . $k['woreda_name'] . ')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Step 5: Guardian Guard -->
                <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm space-y-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-600 flex items-center justify-center text-white"><i data-lucide="shield" class="w-5 h-5"></i></div>
                        <h4 class="text-lg font-black text-gray-950 dark:text-white uppercase tracking-tight">Guardian / Emergency Shield</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Guardian Full Designation</label>
                            <input type="text" name="g_full_name" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Relation Matrix</label>
                            <select name="g_relation" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold appearance-none">
                                <option value="Mother">Mother</option>
                                <option value="Father">Father</option>
                                <option value="Guardian">Legal Guardian</option>
                                <option value="Sponsor">Sponsor</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 ml-1 tracking-widest">Guardian Mobile Interface</label>
                        <input type="text" name="g_phone" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-primary-600 font-bold">
                    </div>
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full py-6 bg-gray-950 dark:bg-white text-white dark:text-gray-950 font-black rounded-3xl shadow-2xl transition-all hover:scale-[1.01] active:scale-[0.99] uppercase tracking-[0.2em] text-sm flex items-center justify-center gap-4">
                        <span>Initialize Protocol Synthesis</span>
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
