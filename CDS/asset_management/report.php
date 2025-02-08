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

$sql = "SELECT * FROM Assets";
$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    die("Query failed: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Asset Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Asset Report</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Asset ID</th>
                <th>Asset Name</th>
                <th>Type</th>
                <th>Assigned Employee</th>
                <th>Purchase Date</th>
                <th>Warranty Expiry</th>
                <th>Status</th>
                <th>Condition</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['asset_name']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['assigned_to']; ?></td>
                    <td><?php echo $row['purchase_date']->format('Y-m-d'); ?></td>
                    <td><?php echo $row['warranty_expiry']->format('Y-m-d'); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['condition']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
sqlsrv_close($conn);
?>