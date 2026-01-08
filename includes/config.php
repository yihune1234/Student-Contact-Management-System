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
    // 1. Connect and Create Database
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci");
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);

    // 2. Core Academic Structure
    $pdo->exec("CREATE TABLE IF NOT EXISTS faculties (
        id INT AUTO_INCREMENT PRIMARY KEY,
        faculty_name VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        faculty_id INT,
        department_name VARCHAR(100) NOT NULL UNIQUE,
        FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE SET NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS programs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        department_id INT,
        program_name VARCHAR(100) NOT NULL,
        degree_type ENUM('Degree', 'Masters', 'PhD', 'Diploma') DEFAULT 'Degree',
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS advisors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        department_id INT,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS academic_years (
        id INT AUTO_INCREMENT PRIMARY KEY,
        year_label VARCHAR(20) NOT NULL UNIQUE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS semesters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        semester_name VARCHAR(50) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sections (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section_name VARCHAR(20) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS enrollment_statuses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        status_name VARCHAR(50) NOT NULL UNIQUE
    )");

    // 3. Location Structure
    $pdo->exec("CREATE TABLE IF NOT EXISTS countries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        code VARCHAR(5)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS regions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        country_id INT,
        name VARCHAR(100) NOT NULL,
        FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS zones (
        id INT AUTO_INCREMENT PRIMARY KEY,
        region_id INT,
        name VARCHAR(100) NOT NULL,
        FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS woredas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        zone_id INT,
        name VARCHAR(100) NOT NULL,
        FOREIGN KEY (zone_id) REFERENCES zones(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS kebeles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        woreda_id INT,
        name VARCHAR(100) NOT NULL,
        FOREIGN KEY (woreda_id) REFERENCES woredas(id) ON DELETE CASCADE
    )");

    // 4. Students & Guardians
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        nationality VARCHAR(50) DEFAULT 'Ethiopian',
        email VARCHAR(100),
        phone VARCHAR(20),
        secondary_phone VARCHAR(20),
        
        department_id INT,
        program_id INT,
        batch_id INT,
        semester_id INT,
        section_id INT,
        advisor_id INT,
        enrollment_status_id INT,
        
        kebele_id INT,
        address_detail TEXT,
        profile_photo VARCHAR(255) DEFAULT NULL,
        
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
        FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL,
        FOREIGN KEY (batch_id) REFERENCES academic_years(id) ON DELETE SET NULL,
        FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE SET NULL,
        FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL,
        FOREIGN KEY (advisor_id) REFERENCES advisors(id) ON DELETE SET NULL,
        FOREIGN KEY (enrollment_status_id) REFERENCES enrollment_statuses(id) ON DELETE SET NULL,
        FOREIGN KEY (kebele_id) REFERENCES kebeles(id) ON DELETE SET NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS guardians (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        relation VARCHAR(50) NOT NULL, -- Mother, Father, Guardian, Sponsor
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(100),
        address TEXT,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )");

    // 5. Users & Roles
    $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL UNIQUE
    )");

    // Seed Roles
    $checkRoles = $pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
    if ($checkRoles == 0) {
        $pdo->exec("INSERT INTO roles (role_name) VALUES ('Admin'), ('Student'), ('Registrar'), ('Department Officer')");
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role_id INT,
        student_link_id INT DEFAULT NULL,
        department_link_id INT DEFAULT NULL,
        is_active TINYINT(1) DEFAULT 1,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
        FOREIGN KEY (student_link_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (department_link_id) REFERENCES departments(id) ON DELETE SET NULL
    )");

    // 6. Communication & Logs
    $pdo->exec("CREATE TABLE IF NOT EXISTS message_templates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100),
        channel ENUM('SMS', 'Email', 'WhatsApp', 'Portal') DEFAULT 'Portal',
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS message_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT,
        receiver_id INT, -- student id
        channel VARCHAR(20),
        content TEXT,
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (receiver_id) REFERENCES students(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(100),
        description TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");

    // 7. Requests 
    $pdo->exec("CREATE TABLE IF NOT EXISTS update_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        field_name VARCHAR(50),
        old_value TEXT,
        new_value TEXT,
        status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
        requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        reviewed_by INT,
        reviewed_at TIMESTAMP NULL,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
    )");

    // 8. Seeding Initial Data
    $checkFaculties = $pdo->query("SELECT COUNT(*) FROM faculties")->fetchColumn();
    if ($checkFaculties == 0) {
        $pdo->exec("INSERT INTO faculties (faculty_name) VALUES ('Engineering'), ('Medicine'), ('Business'), ('Social Sciences')");
        $pdo->exec("INSERT INTO departments (faculty_id, department_name) VALUES 
            (1, 'Software Engineering'), (1, 'Civil Engineering'), (2, 'Nursing'), (3, 'Accounting')");
        $pdo->exec("INSERT INTO academic_years (year_label) VALUES ('2023/24'), ('2024/25')");
        $pdo->exec("INSERT INTO semesters (semester_name) VALUES ('Semester 1'), ('Semester 2')");
        $pdo->exec("INSERT INTO sections (section_name) VALUES ('Section A'), ('Section B')");
        $pdo->exec("INSERT INTO enrollment_statuses (status_name) VALUES ('Active'), ('Graduated'), ('Withdrawn'), ('Suspended')");
    }

    // Seed Admin (if not exists)
    $adminRoleQuery = $pdo->query("SELECT id FROM roles WHERE role_name = 'Admin'")->fetch();
    $adminRoleId = $adminRoleQuery['id'];
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'admin'")->fetchColumn();
    if ($checkAdmin == 0) {
        $hashed_pass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $hashed_pass, $adminRoleId]);
    }

} catch (\PDOException $e) {
    die("Database Connection/Setup Failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
