<?php
require_once '../includes/config.php';

// Role Check
$allowed_roles = ['admin', 'registrar'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';

// Handle Additions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type']; 
    try {
        if ($type === 'region') {
            $name = $_POST['name'];
            $stmt = $pdo->prepare("INSERT INTO regions (name, country_id) VALUES (?, 1)"); // Default country 1
            $stmt->execute([$name]);
        } elseif ($type === 'zone') {
            $name = $_POST['name'];
            $r_id = $_POST['region_id'];
            $stmt = $pdo->prepare("INSERT INTO zones (region_id, name) VALUES (?, ?)");
            $stmt->execute([$r_id, $name]);
        } elseif ($type === 'woreda') {
            $name = $_POST['name'];
            $z_id = $_POST['zone_id'];
            $stmt = $pdo->prepare("INSERT INTO woredas (zone_id, name) VALUES (?, ?)");
            $stmt->execute([$z_id, $name]);
        } elseif ($type === 'kebele') {
            $name = $_POST['name'];
            $w_id = $_POST['woreda_id'];
            $stmt = $pdo->prepare("INSERT INTO kebeles (woreda_id, name) VALUES (?, ?)");
            $stmt->execute([$w_id, $name]);
        }
        $success = "Geo-node " . strtoupper($type) . " mapped.";
    } catch (Exception $e) {
        $error = "Mapping rejection: " . $e->getMessage();
    }
}

// Data Fetching
$regions = $pdo->query("SELECT * FROM regions ORDER BY name ASC")->fetchAll();
$zones = $pdo->query("SELECT z.*, r.name as rname FROM zones z JOIN regions r ON z.region_id = r.id ORDER BY r.name, z.name ASC")->fetchAll();
$woredas = $pdo->query("SELECT w.*, z.name as zname FROM woredas w JOIN zones z ON w.zone_id = z.id ORDER BY z.name, w.name ASC")->fetchAll();
$kebeles = $pdo->query("SELECT k.*, w.name as wname FROM kebeles k JOIN woredas w ON k.woreda_id = w.id ORDER BY w.name, k.name ASC")->fetchAll();

$page_title = "Geo-Location Mapping - Nexus SCMS";
include '../includes/header.php';
?>

<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false, tab: 'regions' }">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto italic">
        <div class="p-8 flex items-center justify-between">
            <div class="flex items-center gap-3 text-primary-400 font-black text-2xl tracking-tighter">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
                <span>ADMIN PANEL</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Core Modules</div>
        <nav class="flex-1 px-4 space-y-1 mb-8">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <a href="students.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Manage Students
            </a>
        </nav>

        <div class="px-6 mb-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Academic & Location</div>
        <nav class="px-4 space-y-1 mb-8">
            <a href="academic.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                Aca. Structure
            </a>
            <a href="locations.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-500/20">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                Geo-Location
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden italic">
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">GEO-LOCATION MAPPING</h2>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-10">
            <div class="max-w-6xl mx-auto space-y-10">
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-100 text-green-600 p-6 rounded-[2rem] font-bold text-sm flex items-center gap-4">
                        <i data-lucide="map" class="w-6 h-6"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <!-- Tab Navigation -->
                <div class="flex flex-wrap gap-4 p-2 bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm w-fit">
                    <button @click="tab = 'regions'" :class="tab === 'regions' ? 'bg-emerald-600 text-white' : 'text-gray-400'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Regions</button>
                    <button @click="tab = 'zones'" :class="tab === 'zones' ? 'bg-emerald-600 text-white' : 'text-gray-400'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Zones</button>
                    <button @click="tab = 'woredas'" :class="tab === 'woredas' ? 'bg-emerald-600 text-white' : 'text-gray-400'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Woredas</button>
                    <button @click="tab = 'kebeles'" :class="tab === 'kebeles' ? 'bg-emerald-600 text-white' : 'text-gray-400'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Kebeles</button>
                </div>

                <!-- Regions Panel -->
                <div x-show="tab === 'regions'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Create Region</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="region">
                                <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-emerald-600 font-bold" placeholder="Region Name">
                                <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest">Map Region</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php foreach($regions as $r): ?>
                                    <tr>
                                        <td class="py-4 font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($r['name']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Zones Panel (Implicitly filtered or shown together) -->
                <div x-show="tab === 'zones'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Create Zone</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="zone">
                                <select name="region_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl">
                                    <?php foreach($regions as $r): ?><option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></option><?php endforeach; ?>
                                </select>
                                <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-emerald-600 font-bold" placeholder="Zone Name">
                                <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest">Map Zone</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800">
                           <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase text-gray-400"><th class="pb-4">Zone</th><th class="pb-4">Region</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php foreach($zones as $z): ?>
                                    <tr><td class="py-4 font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($z['name']); ?></td><td class="py-4 text-xs font-bold text-gray-400 uppercase"><?php echo htmlspecialchars($z['rname']); ?></td></tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Woredas Panel -->
                <div x-show="tab === 'woredas'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Create Woreda</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="woreda">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Parent Zone</label>
                                    <select name="zone_id" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-emerald-600 font-bold appearance-none">
                                        <?php foreach($zones as $z): ?>
                                            <option value="<?php echo $z['id']; ?>"><?php echo htmlspecialchars($z['name'] . ' (' . $z['rname'] . ')'); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Woreda Designation</label>
                                    <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-emerald-600 font-bold" placeholder="Woreda Name">
                                </div>
                                <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 transition-all">Map Woreda</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase text-gray-400 border-b border-gray-50 dark:border-gray-800">
                                        <th class="pb-4">Woreda Node</th>
                                        <th class="pb-4">Zone Parent</th>
                                        <th class="pb-4 text-right">Region Origin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php 
                                    $w_query = "SELECT w.*, z.name as zname, r.name as rname 
                                               FROM woredas w 
                                               JOIN zones z ON w.zone_id = z.id 
                                               JOIN regions r ON z.region_id = r.id 
                                               ORDER BY r.name, z.name, w.name ASC";
                                    foreach($pdo->query($w_query)->fetchAll() as $w): 
                                    ?>
                                    <tr>
                                        <td class="py-4 font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($w['name']); ?></td>
                                        <td class="py-4 text-xs font-bold text-gray-500 uppercase"><?php echo htmlspecialchars($w['zname']); ?></td>
                                        <td class="py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest"><?php echo htmlspecialchars($w['rname']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Kebeles Panel -->
                <div x-show="tab === 'kebeles'" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-cloak>
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Create Kebele</h4>
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="type" value="kebele">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Parent Woreda</label>
                                    <select name="woreda_id" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-emerald-600 font-bold appearance-none">
                                        <?php 
                                        $w_list = $pdo->query("SELECT w.*, z.name as zname FROM woredas w JOIN zones z ON w.zone_id = z.id ORDER BY w.name ASC")->fetchAll();
                                        foreach($w_list as $wl): 
                                        ?>
                                            <option value="<?php echo $wl['id']; ?>"><?php echo htmlspecialchars($wl['name'] . ' (' . $wl['zname'] . ')'); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Kebele Designation</label>
                                    <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl outline-none focus:ring-2 focus:ring-emerald-600 font-bold" placeholder="Kebele Name/Number">
                                </div>
                                <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 transition-all">Map Kebele</button>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase text-gray-400 border-b border-gray-50 dark:border-gray-800">
                                        <th class="pb-4">Kebele Node</th>
                                        <th class="pb-4">Woreda Parent</th>
                                        <th class="pb-4 text-right">Zone context</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    <?php 
                                    $k_query = "SELECT k.*, w.name as wname, z.name as zname 
                                               FROM kebeles k 
                                               JOIN woredas w ON k.woreda_id = w.id 
                                               JOIN zones z ON w.zone_id = z.id 
                                               ORDER BY w.name, k.name ASC";
                                    foreach($pdo->query($k_query)->fetchAll() as $k): 
                                    ?>
                                    <tr>
                                        <td class="py-4 font-black text-gray-950 dark:text-white"><?php echo htmlspecialchars($k['name']); ?></td>
                                        <td class="py-4 text-xs font-bold text-gray-500 uppercase"><?php echo htmlspecialchars($k['wname']); ?></td>
                                        <td class="py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest"><?php echo htmlspecialchars($k['zname']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php include '../includes/footer.php'; ?>
