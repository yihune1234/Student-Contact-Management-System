<?php
$host = 'localhost';
$db   = 'student_contact_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 1. First connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    
    // 2. Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci");
    
    // 3. Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);

    // 4. Create Tables if they don't exist
    
    // Departments Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        department_name VARCHAR(100) NOT NULL UNIQUE
    )");

    // Students Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        department_id INT,
        phone VARCHAR(20),
        email VARCHAR(100),
        address TEXT,
        profile_photo VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
    )");

    // Migration: Add profile_photo to students if not exists
    try {
        $pdo->exec("ALTER TABLE students ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL AFTER address");
    } catch (Exception $e) {
        // Column probably already exists
    }
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'student') NOT NULL,
        student_id VARCHAR(50) DEFAULT NULL,
        FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
    )");

    // Applications Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
    )");

    // 5. Seed Initial Data if tables are empty
    
    // Seed Departments
    $checkDepts = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
    if ($checkDepts == 0) {
        $pdo->exec("INSERT INTO departments (department_name) VALUES 
            ('Computer Science'),
            ('Information Technology'),
            ('Software Engineering'),
            ('Business Administration'),
            ('Electrical Engineering')");
    }

    // Seed Admin (password: admin123)
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
    if ($checkAdmin == 0) {
        $hashed_pass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
        $stmt->execute(['admin', $hashed_pass]);
    }

} catch (\PDOException $e) {
    die("Database Connection/Setup Failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
