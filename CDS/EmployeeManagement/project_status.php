<?php
$server = "DESKTOP-PO8603Q\\MSSQLSERVER06";
$database = "EmployeeManagement";

$connectionOptions = array(
    "Database" => $database,
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($server, $connectionOptions);

$searchProject = isset($_POST['search_project']) ? $_POST['search_project'] : '';
$searchStatus = isset($_POST['search_status']) ? $_POST['search_status'] : '';

$sql = "SELECT ProjectName, StartDate, EndDate, Status 
        FROM Projects 
        WHERE 1=1";

$params = [];

if ($searchProject) {
    $sql .= " AND ProjectName LIKE ?";
    $params[] = '%' . $searchProject . '%';
}

if ($searchStatus) {
    $sql .= " AND Status = ?";
    $params[] = $searchStatus;
}

$stmt = sqlsrv_query($conn, $sql, $params);
$projects = [];

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $projects[] = $row;
    }
}

$totalPending = 0;
$totalCompleted = 0;

foreach ($projects as $project) {
    if ($project['Status'] === 'Pending') {
        $totalPending++;
    } elseif ($project['Status'] === 'Completed') {
        $totalCompleted++;
    }
}

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f4f8; 
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        h2 {
            color: #343a40; 
            margin-bottom: 20px;
        }
        .summary {
            background-color: #ffffff; 
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff; 
        }
        .table th {
            background-color:mediumspringgreen; /
            color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef; 
        }
        .btn-primary {
            background-color:rgb(136, 50, 238);
            border: none;
        }
        .btn{
            background-color:#343a40;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Project Status</h2>

        <form method="POST" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <input type="text" name="search_project" class="form-control" placeholder="Search by project name" value="<?php echo htmlspecialchars($searchProject); ?>">
                </div>
                <div class="col">
                    <select name="search_status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="Pending" <?php echo ($searchStatus === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo ($searchStatus === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo ($searchStatus === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <div class="summary">
            <h4>Summary Statistics</h4>
            <p>Total Pending Projects: <span id="totalPending"><?php echo $totalPending; ?></span></p>
            <p>Total Completed Projects: <span id="totalCompleted"><?php echo $totalCompleted; ?></span></p>
        </div>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($project['ProjectName']); ?></td>
                            <td><?php echo htmlspecialchars($project['StartDate']->format('Y-m-d')); ?></td>
                            <td><?php echo htmlspecialchars($project['EndDate']->format('Y-m-d')); ?></td>
                            <td><?php echo htmlspecialchars($project['Status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-3">
                <a href="dash.html" class="btn btn-secondary">Back to Dashboard</a>
            </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>