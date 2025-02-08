<?php

$server = "DESKTOP-PO8603Q\\MSSQLSERVER06";
$database = "EmployeeManagement";

$connectionOptions = array(
    "Database" => $database,
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($server, $connectionOptions);


$sql = "SELECT d.DepartmentID, d.DepartmentName, e.FirstName, e.LastName
        FROM Departments d
        LEFT JOIN Employees e ON d.DepartmentID = e.DepartmentID
        ORDER BY d.DepartmentID";
$stmt = sqlsrv_query($conn, $sql);

$departments = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $departments[] = $row;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Departments Info</title>
    <style>
           body {
            background-color: #f0f4f8; 
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        .container {
            margin-top: 20px;
        }
        h2 {
            color: #343a40; 
            margin-bottom: 20px;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff; 
        }
        .table th {
            background-color:mediumspringgreen; 
            color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef; 
        }
        .btn {
            background-color:  rgb(136, 50, 238); 
            box-shadow: 0 6px 12px mediumspringgreen; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Departments Information</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Employees</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentDepartmentID = null;
                $currentDepartmentName = null;
                $employeesList = [];

                foreach ($departments as $department) {
                  
                    if ($currentDepartmentID !== $department['DepartmentID']) {
                        
                        if ($currentDepartmentID !== null) {
                            echo "<tr>
                                    <td>$currentDepartmentID</td>
                                    <td>$currentDepartmentName</td>
                                    <td>" . implode(", ", $employeesList) . "</td>
                                  </tr>";
                        }
                     
                        $currentDepartmentID = $department['DepartmentID'];
                        $currentDepartmentName = $department['DepartmentName'];
                        $employeesList = [];
                    }
                  
                    if ($department['FirstName'] && $department['LastName']) {
                        $employeesList[] = $department['FirstName'] . ' ' . $department['LastName'];
                    }
                }

                
                if ($currentDepartmentID !== null) {
                    echo "<tr>
                            <td>$currentDepartmentID</td>
                            <td>$currentDepartmentName</td>
                            <td>" . implode(", ", $employeesList) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center mt-3">
                <a href="dash.html" class="btn btn-secondary">Back to Dashboard</a>
            </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>