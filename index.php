<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "HUSCMS";

// Function to check if database exists
function databaseExists($conn, $dbname) {
    $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    return $result && $result->num_rows > 0;
}

// Function to check if table exists (requires database to be selected)
function tableExists($conn, $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result && $result->num_rows > 0;
}

try {
    // Connect to MySQL server
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if database exists, create if not
    if (!databaseExists($conn, $dbname)) {
        include 'database_setup.php';
    } else {
        // Select the database before checking tables
        if (!$conn->select_db($dbname)) {
            throw new Exception("Failed to select database: " . $conn->error);
        }
        // Check if Student table exists, run setup if not
        if (!tableExists($conn, 'Student')) {
            include 'database_setup.php';
        }
    }

    // Ensure database is selected for subsequent queries
    if (!$conn->select_db($dbname)) {
        throw new Exception("Failed to select database: " . $conn->error);
    }

} catch (Exception $e) {
    die("Setup error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUSCMS - Student Contact Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Haramaya University Student Contact Management System</h1>

    <!-- Filter by Department -->
    <div class="filter-section">
        <form method="POST" action="">
            <label for="department">Select Department:</label>
            <select name="department" id="department">
                <option value="">All Departments</option>
                <option value="SENG">Software Engineering (SENG)</option>
                <option value="MED">Medicine (MED)</option>
            </select>
            <button type="submit">Get Student Count</button>
        </form>
    </div>

    <!-- Display Stored Procedure Result -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['department'])) {
        $dept = $conn->real_escape_string($_POST['department']);
        if ($dept) {
            // Check if stored procedure exists
            $checkProc = $conn->query("SHOW PROCEDURE STATUS WHERE Db = '$dbname' AND Name = 'GetGenderCountByDepartment'");
            if ($checkProc && $checkProc->num_rows > 0) {
                // Call stored procedure
                $result = $conn->query("CALL GetGenderCountByDepartment('$dept', @male_count, @female_count)");
                if ($result) {
                  
                    while ($conn->more_results()) {
                        $conn->next_result();
                    }
                    $counts = $conn->query("SELECT @male_count AS male_count, @female_count AS female_count");
                    if ($counts) {
                        $row = $counts->fetch_assoc();
                        echo "<p>Male Students in $dept: " . ($row['male_count'] ?? 0) . "</p>";
                        echo "<p>Female Students in $dept: " . ($row['female_count'] ?? 0) . "</p>";
                        $counts->free();
                    } else {
                        echo "<p>Error retrieving counts: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p>Error calling stored procedure: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Error: Stored procedure GetGenderCountByDepartment does not exist. Please run <a href='database_setup.php'>database_setup.php</a> manually.</p>";
            }
            if ($checkProc) {
                $checkProc->free();
            }
        }
    }
    ?>

    <!-- Student Contact Table -->
    <table>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>College</th>
            <th>Department</th>
            <th>Campus</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
        <?php
        // Query all students with explicit column selection
        $sql = "
            SELECT s.student_id, s.first_name, s.last_name, d.college, d.department_name, c.campus_name, s.gender, s.phone
            FROM Student s
            INNER JOIN Department d ON s.department_id = d.department_id
            INNER JOIN Campus c ON s.campus_id = c.campus_id
        ";
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $index = 1;
                while ($row = $result->fetch_assoc()) {
                    $fullName = $row['first_name'] . ' ' . $row['last_name'];
                    echo "<tr>";
                    echo "<td>$index</td>";
                    echo "<td>" . htmlspecialchars($fullName) . "</td>";
                    echo "<td>" . htmlspecialchars($row['college'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['department_name'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['campus_name'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone'] ?? 'N/A') . "</td>";
                    echo "<td><button class='detail-btn' onclick='showDetails({$row['student_id']})'>Detail</button></td>";
                    echo "</tr>";
                    $index++;
                }
            } else {
                echo "<tr><td colspan='8'>No students found. Sample data may not be inserted. Run <a href='database_setup.php'>database_setup.php</a>.</td></tr>";
            }
            $result->free();
        } else {
            echo "<tr><td colspan='8'>Error retrieving data: " . $conn->error . "</td></tr>";
        }
        ?>
    </table>

    <!-- Modal for Detailed Information -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">×</span>
            <h2>Student Details</h2>
            <div id="studentDetails"></div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
<?php
$conn->close();
?>