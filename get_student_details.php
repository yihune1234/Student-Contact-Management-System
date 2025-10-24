<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "HUSCMS";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
if ($student_id > 0) {
    $sql = "
        SELECT s.student_id, s.first_name, s.last_name, d.college, d.department_name, c.campus_name, s.gender, s.phone,
               COALESCE(a.region, '') AS region, COALESCE(a.zone, '') AS zone, COALESCE(a.woreda, '') AS woreda,
               COALESCE(a.hometown, '') AS hometown, COALESCE(a.pob, '') AS pob, COALESCE(a.dob, '') AS dob
        FROM Student s
        JOIN Department d ON s.department_id = d.department_id
        JOIN Campus c ON s.campus_id = c.campus_id
        LEFT JOIN StudentAddress a ON s.student_id = a.student_id
        WHERE s.student_id = $student_id
    ";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode($student);
        $result->free();
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid student ID']);
}

$conn->close();
?>