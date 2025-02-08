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

$sql = "SELECT p.ProjectName, p.Description, e.FirstName, e.LastName, pt.Role, pt.AssignedDate 
        FROM ProjectTeam pt 
        JOIN Projects p ON pt.ProjectID = p.ProjectID 
        JOIN Employees e ON pt.EmployeeID = e.EmployeeID 
        WHERE 1=1";

$params = [];

if ($searchProject) {
    $sql .= " AND p.ProjectName LIKE ?";
    $params[] = '%' . $searchProject . '%';
}

$stmt = sqlsrv_query($conn, $sql, $params);
$projectTeams = [];

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $projectTeams[] = $row;
    }
}

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Teams</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .search-form {
            margin-bottom: 20px;
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
        .btn {
            background-color:  rgb(136, 50, 238); 
            box-shadow: 0 6px 12px mediumspringgreen; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Project Teams</h2>

        <form method="POST" class="search-form">
            <div class="form-row align-items-end">
                <div class="col">
                    <input type="text" name="search_project" class="form-control" placeholder="Search by project name" value="<?php echo htmlspecialchars($searchProject); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Employee Name</th>
                    <th>Role</th>
                    <th>Assigned Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projectTeams)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projectTeams as $team): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($team['ProjectName']); ?></td>
                            <td><?php echo htmlspecialchars($team['Description']); ?></td>
                            <td><?php echo htmlspecialchars($team['FirstName'] . ' ' . $team['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($team['Role']); ?></td>
                            <td><?php echo htmlspecialchars($team['AssignedDate']->format('Y-m-d')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center mt-3">
                <a href="dash.html" class="btn btn-secondary">Back to Dashboard</a>
            </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>