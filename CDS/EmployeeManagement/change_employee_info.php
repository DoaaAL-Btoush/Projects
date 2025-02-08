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


$conn = sqlsrv_connect($server, $connectionOptions);


if ($conn === false) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

$sql = "SELECT EmployeeID, FirstName, LastName, DepartmentID, JobTitle, Email, Phone FROM Employees";
$stmt = sqlsrv_query($conn, $sql);
$employees = [];

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $employees[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    $employeeID = intval($_POST['employee_id']);
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $departmentID = intval($_POST['department_id']);
    $jobTitle = htmlspecialchars(trim($_POST['job_title']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    $updateSql = "UPDATE Employees SET FirstName = ?, LastName = ?, DepartmentID = ?, JobTitle = ?, Email = ?, Phone = ? WHERE EmployeeID = ?";
    $params = array($firstName, $lastName, $departmentID, $jobTitle, $email, $phone, $employeeID);
    
    $stmt = sqlsrv_query($conn, $updateSql, $params);
    if ($stmt === false) {
        die("Error updating employee: " . print_r(sqlsrv_errors(), true));
    }

    header("Location: change_employee_info.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $deleteSql = "DELETE FROM Employees WHERE EmployeeID = ?";
    $deleteStmt = sqlsrv_query($conn, $deleteSql, array($deleteId));
    if ($deleteStmt === false) {
        die("Error deleting employee: " . print_r(sqlsrv_errors(), true));
    }
    header("Location: change_employee_info.php");
    exit();
}

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Employee Info</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_change.css">
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-start vh-100">
        <div class="content-box p-5">
            <h2 class="mb-4">Employee List</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Department ID</th>
                            <th>Job Title</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr id="row-<?php echo $employee['EmployeeID']; ?>">
                                <td><?php echo htmlspecialchars($employee['EmployeeID']); ?></td>
                                <td class="editable" data-field="FirstName"><?php echo htmlspecialchars($employee['FirstName']); ?></td>
                                <td class="editable" data-field="LastName"><?php echo htmlspecialchars($employee['LastName']); ?></td>
                                <td class="editable" data-field="DepartmentID"><?php echo htmlspecialchars($employee['DepartmentID']); ?></td>
                                <td class="editable" data-field="JobTitle"><?php echo htmlspecialchars($employee['JobTitle']); ?></td>
                                <td class="editable" data-field="Email"><?php echo htmlspecialchars($employee['Email']); ?></td>
                                <td class="editable" data-field="Phone"><?php echo htmlspecialchars($employee['Phone']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $employee['EmployeeID']; ?>">Edit</button>
                                    <a href="?delete_id=<?php echo $employee['EmployeeID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="add_employee.php" class="btn btn-secondary">Back to Add Employee</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const rowId = this.getAttribute('data-id');
        const row = document.getElementById('row-' + rowId);
        const cells = row.querySelectorAll('.editable');

        if (this.innerText === 'Edit') {
            
            cells.forEach(cell => {
                const field = cell.getAttribute('data-field');
                const value = cell.innerText.trim();
                cell.innerHTML = `<input type="text" name="${field}" value="${value}" class="form-control" required>`;
            });

            this.innerText = 'Save';
        } else {
            
            const updatedData = {
                employee_id: rowId,
                first_name: cells[0].querySelector('input').value, 
                last_name: cells[1].querySelector('input').value, 
                department_id: cells[2].querySelector('input').value,
                job_title: cells[3].querySelector('input').value, 
                email: cells[4].querySelector('input').value, 
                phone: cells[5].querySelector('input').value 
            };

           
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            for (let key in updatedData) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = updatedData[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    });
});

    </script>
</body>
</html>