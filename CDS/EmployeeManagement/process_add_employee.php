<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "DESKTOP-PO8603Q\\MSSQLSERVER06";
$database = "EmployeeManagement";

$connectionOptions = array(
    "Database" => $database,
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
);

echo "Connecting to database..."; 

$conn = sqlsrv_connect($server, $connectionOptions);

if ($conn === false) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

echo "Connection successful!"; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addEmployee'])) {
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $departmentID = intval($_POST['department_id']);
    $jobTitle = htmlspecialchars(trim($_POST['job_title']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    $sql = "INSERT INTO Employees (FirstName, LastName, DepartmentID, JobTitle, Email, Phone) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $params = array($firstName, $lastName, $departmentID, $jobTitle, $email, $phone);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error executing query: " . print_r(sqlsrv_errors(), true));
    } else {
        echo "<script>alert('Employee added successfully!'); window.location.href='add_employee.php';</script>";
    }
}

sqlsrv_close($conn);
?>