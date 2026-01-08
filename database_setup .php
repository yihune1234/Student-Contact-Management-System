<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "HUSCMS";

try {
    // Connect to MySQL server (without specifying a database initially)
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Create Database with IF NOT EXISTS
    $sql = "CREATE DATABASE IF NOT EXISTS HUSCMS CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    if ($conn->query($sql) === TRUE) {
        echo "Database HUSCMS created or already exists.\n";
    } else {
        throw new Exception("Error creating database: " . $conn->error);
    }

    // Select the HUSCMS database
    $conn->select_db($dbname);

    // Drop stored procedure and view if they exist
    $conn->query("DROP PROCEDURE IF EXISTS GetGenderCountByDepartment");
    echo "Dropped existing GetGenderCountByDepartment procedure (if any).\n";
    $conn->query("DROP VIEW IF EXISTS MaleSENGStudents");
    echo "Dropped existing MaleSENGStudents view (if any).\n";

    // Drop tables in reverse order to avoid foreign key conflicts
    $conn->query("DROP TABLE IF EXISTS StudentAddress");
    $conn->query("DROP TABLE IF EXISTS Student");
    $conn->query("DROP TABLE IF EXISTS Campus");
    $conn->query("DROP TABLE IF EXISTS Department");

    // Create Department Table with IF NOT EXISTS
    $sql = "
        CREATE TABLE IF NOT EXISTS Department (
            department_id INT AUTO_INCREMENT PRIMARY KEY,
            department_name VARCHAR(50) NOT NULL UNIQUE,
            college VARCHAR(50) NOT NULL,
            INDEX idx_department_name (department_name)
        ) ENGINE=InnoDB
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Department table created or already exists.\n";
    } else {
        throw new Exception("Error creating Department table: " . $conn->error);
    }

    // Create Campus Table with IF NOT EXISTS
    $sql = "
        CREATE TABLE IF NOT EXISTS Campus (
            campus_id INT AUTO_INCREMENT PRIMARY KEY,
            campus_name VARCHAR(50) NOT NULL UNIQUE,
            INDEX idx_campus_name (campus_name)
        ) ENGINE=InnoDB
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Campus table created or already exists.\n";
    } else {
        throw new Exception("Error creating Campus table: " . $conn->error);
    }

    // Create Student Table with IF NOT EXISTS
    $sql = "
        CREATE TABLE IF NOT EXISTS Student (
            student_id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            department_id INT NOT NULL,
            campus_id INT NOT NULL,
            gender ENUM('Male', 'Female') NOT NULL,
            phone VARCHAR(15) NOT NULL UNIQUE,
            FOREIGN KEY (department_id) REFERENCES Department(department_id) ON DELETE RESTRICT,
            FOREIGN KEY (campus_id) REFERENCES Campus(campus_id) ON DELETE RESTRICT,
            INDEX idx_department_gender (department_id, gender),
            INDEX idx_phone (phone),
            INDEX idx_campus_id (campus_id)
        ) ENGINE=InnoDB
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Student table created or already exists.\n";
    } else {
        throw new Exception("Error creating Student table: " . $conn->error);
    }

    // Create StudentAddress Table with IF NOT EXISTS
    $sql = "
        CREATE TABLE IF NOT EXISTS StudentAddress (
            address_id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            region VARCHAR(50),
            zone VARCHAR(50),
            woreda VARCHAR(50),
            hometown VARCHAR(50),
            pob VARCHAR(50),
            dob DATE,
            FOREIGN KEY (student_id) REFERENCES Student(student_id) ON DELETE CASCADE,
            INDEX idx_student_id (student_id)
        ) ENGINE=InnoDB
    ";
    if ($conn->query($sql) === TRUE) {
        echo "StudentAddress table created or already exists.\n";
    } else {
        throw new Exception("Error creating StudentAddress table: " . $conn->error);
    }

    // Insert Sample Data into Department (skip duplicates)
    $sql = "
        INSERT IGNORE INTO Department (department_name, college)
        VALUES
            ('SENG', 'CCI'),
            ('MED', 'CHMS')
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted into Department table.\n";
    } else {
        throw new Exception("Error inserting data into Department table: " . $conn->error);
    }

    // Insert Sample Data into Campus (skip duplicates)
    $sql = "
        INSERT IGNORE INTO Campus (campus_name)
        VALUES
            ('MAIN'),
            ('HARAR')
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted into Campus table.\n";
    } else {
        throw new Exception("Error inserting data into Campus table: " . $conn->error);
    }

    // Insert Sample Data into Student (skip duplicates)
    $sql = "
        INSERT IGNORE INTO Student (first_name, last_name, department_id, campus_id, gender, phone)
        VALUES
            ('Ujulu', 'Obang', 1, 1, 'Male', '0945789658'),
            ('Parastamol', 'PainKiller', 2, 2, 'Female', '0789653252')
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted into Student table.\n";
    } else {
        throw new Exception("Error inserting data into Student table: " . $conn->error);
    }

    // Insert Sample Data into StudentAddress (skip duplicates)
    $sql = "
        INSERT IGNORE INTO StudentAddress (student_id, region, zone, woreda, hometown, pob, dob)
        VALUES
            (1, 'Oromia', 'East Hararghe', 'Haramaya', 'Haramaya', 'Haramaya', '2000-05-15'),
            (2, 'Harari', 'Harar City', 'Harar', 'Harar', 'Harar', '1999-08-22')
    ";
    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted into StudentAddress table.\n";
    } else {
        throw new Exception("Error inserting data into StudentAddress table: " . $conn->error);
    }

    // Create View for Male SENG Students
    $sql = "
        CREATE OR REPLACE VIEW MaleSENGStudents AS
        SELECT s.student_id, s.first_name, s.last_name, d.college, d.department_name, c.campus_name, s.gender, s.phone
        FROM Student s
        JOIN Department d ON s.department_id = d.department_id
        JOIN Campus c ON s.campus_id = c.campus_id
        WHERE s.gender = 'Male' AND d.department_name = 'SENG'
    ";
    if ($conn->query($sql) === TRUE) {
        echo "MaleSENGStudents view created or updated.\n";
    } else {
        throw new Exception("Error creating MaleSENGStudents view: " . $conn->error);
    }

    // Create Stored Procedure
    $sql = "
        CREATE PROCEDURE GetGenderCountByDepartment(IN dept_name VARCHAR(50), OUT male_count INT, OUT female_count INT)
        BEGIN
            SELECT COUNT(*) INTO male_count
            FROM Student s
            JOIN Department d ON s.department_id = d.department_id
            WHERE d.department_name = dept_name AND s.gender = 'Male';
            
            SELECT COUNT(*) INTO female_count
            FROM Student s
            JOIN Department d ON s.department_id = d.department_id
            WHERE d.department_name = dept_name AND s.gender = 'Female';
        END
    ";
    if ($conn->query($sql) === TRUE) {
        echo "GetGenderCountByDepartment stored procedure created.\n";
    } else {
        throw new Exception("Error creating stored procedure: " . $conn->error);
    }

    echo "Database setup completed successfully.\n";

    // Close the connection
    $conn->close();

} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    exit();
}
?>
