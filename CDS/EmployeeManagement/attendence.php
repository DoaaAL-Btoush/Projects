<?php

$server = "DESKTOP-PO8603Q\\MSSQLSERVER06";
$database = "EmployeeManagement";

$connectionOptions = array(
    "Database" => $database,
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($server, $connectionOptions);


$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$searchName = isset($_POST['search_name']) ? $_POST['search_name'] : '';


$sql = "SELECT a.AttendanceID, e.FirstName, e.LastName, a.Date, a.Status 
        FROM Attendance a 
        JOIN Employees e ON a.EmployeeID = e.EmployeeID 
        WHERE 1=1";

$params = [];


if ($searchName) {
    $sql .= " AND (e.FirstName + ' ' + e.LastName) LIKE ?";
    $params[] = '%' . $searchName . '%';
}

if ($startDate) {
    $sql .= " AND a.Date >= ?";
    $params[] = $startDate;
}

if ($endDate) {
    $sql .= " AND a.Date <= ?";
    $params[] = $endDate;
}

$stmt = sqlsrv_query($conn, $sql, $params);
$attendanceRecords = [];


if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $attendanceRecords[] = $row;
    }
}


$totalPresent = 0;
$totalAbsent = 0;

foreach ($attendanceRecords as $record) {
    if ($record['Status'] === 'Present') {
        $totalPresent++;
    } elseif ($record['Status'] === 'Absent') {
        $totalAbsent++;
    }
}

$totalDays = count($attendanceRecords);
$attendancePercentage = $totalDays > 0 ? ($totalPresent / $totalDays) * 100 : 0;

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        .container {
            margin-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .summary {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        .btn-primary {
            background-color:rgb(136, 50, 238);
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance Tracking</h2>

        
        <form method="POST" class="mb-4">
            <div class="form-row align-items-end">
              <div class="col">
                    <label for="startDate">Start Date:</label>
                    <input type="date" name="start_date" id="startDate" class="form-control" value="<?php echo $startDate; ?>">
                </div>
                <div class="col">
                    <label for="endDate">End Date:</label>
                    <input type="date" name="end_date" id="endDate" class="form-control" value="<?php echo $endDate; ?>">
                </div>
                <div class="col">
                    <label for="searchName">Search:</label>
                    <input type="text" name="search_name" id="searchName" class="form-control" placeholder="Search by name" value="<?php echo htmlspecialchars($searchName); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>

        
        <div class="summary">
            <h4>Summary Statistics</h4>
            <p>Total Present Days: <span id="totalPresent"><?php echo $totalPresent; ?></span></p>
            <p>Total Absent Days: <span id="totalAbsent"><?php echo $totalAbsent; ?></span></p>
            <p>Overall Attendance Percentage: <span id="attendancePercentage"><?php echo number_format($attendancePercentage, 2); ?>%</span></p>
           
        </div>

        
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Attendance ID</th>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($attendanceRecords)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <tr>
                            <td><?php echo $record['AttendanceID']; ?></td>
                            <td><?php echo htmlspecialchars($record['FirstName'] . ' ' . $record['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($record['Date']->format('Y-m-d')); ?></td>
                            <td><?php echo htmlspecialchars($record['Status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3">
                <a href="dash.html" class="btn btn-secondary">Back to Dashboard</a>
            </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>