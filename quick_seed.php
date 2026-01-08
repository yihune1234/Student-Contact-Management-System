<?php
require_once 'includes/config.php';

try {
    // 1. Ensure the 'Admin' role exists and get its ID
    $adminRole = $pdo->query("SELECT id FROM roles WHERE role_name = 'Admin'")->fetch();
    
    if (!$adminRole) {
        die("Fatal: 'Admin' role not found in database. Please run config.php first.");
    }

    $new_admin_username = 'superadmin';
    $new_admin_password = 'Security@2026';
    $hashed_pass = password_hash($new_admin_password, PASSWORD_DEFAULT);

    // 2. Check if this specific user already exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$new_admin_username]);
    
    if ($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
        $stmt->execute([$new_admin_username, $hashed_pass, $adminRole['id']]);
        
        echo "✅ SUCCESS: Admin user '{$new_admin_username}' synthesized into the matrix.\n";
        echo "🔑 Access Key: {$new_admin_password}\n";
    } else {
        echo "ℹ️ INFO: User '{$new_admin_username}' already exists in the archive.\n";
    }

} catch (PDOException $e) {
    echo "❌ ERROR: Synthesis failed: " . $e->getMessage();
}
?>
