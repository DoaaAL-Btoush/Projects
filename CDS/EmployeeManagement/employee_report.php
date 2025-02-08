<?php

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


try {
    $sql = "SELECT e.EmployeeID, e.FirstName, e.LastName, d.DepartmentName, e.JobTitle
            FROM Employees e
            JOIN Departments d ON e.DepartmentID = d.DepartmentID
            ORDER BY d.DepartmentName";

    $result = sqlsrv_query($conn, $sql);

    if ($result === false) {
        die("Query failed: " . print_r(sqlsrv_errors(), true));
    }

    $employees = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $employees[] = $row;
    }
} catch (Exception $e) {
    echo "Error generating report: " . $e->getMessage();
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    $filename = "employee_report_" . date('Ymd') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen("php://output", "w");
    fputcsv($output, ['Employee ID', 'First Name', 'Last Name', 'Department', 'Job Title']);

    foreach ($employees as $row) {
        fputcsv($output, [
            $row['EmployeeID'],
            $row['FirstName'],
            $row['LastName'],
            $row['DepartmentName'],
            $row['JobTitle']
        ]);
    }

    fclose($output);
    exit;
}


if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    require('fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Employee Report', 1, 1, 'C');

  
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 10, 'Employee ID', 1);
    $pdf->Cell(40, 10, 'First Name', 1);
    $pdf->Cell(40, 10, 'Last Name', 1);
    $pdf->Cell(40, 10, 'Department', 1);
    $pdf->Cell(40, 10, 'Job Title', 1);
    $pdf->Ln();


    $pdf->SetFont('Arial', '', 10);
    foreach ($employees as $row) {
        $pdf->Cell(30, 10, $row['EmployeeID'], 1);
        $pdf->Cell(40, 10, $row['FirstName'], 1);
        $pdf->Cell(40, 10, $row['LastName'], 1);
        $pdf->Cell(40, 10, $row['DepartmentName'], 1);
        $pdf->Cell(40, 10, $row['JobTitle'], 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'employee_report.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Employee Report</h2>
        
        <div class="mb-3">
            <a href="?export=csv" class="btn btn-success">Export as CSV</a>
            <a href="?export=pdf" class="btn btn-danger">Export as PDF</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Job Title</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['EmployeeID']) ?></td>
                        <td><?= htmlspecialchars($row['FirstName']) . ' ' . htmlspecialchars($row['LastName']) ?></td>
                        <td><?= htmlspecialchars($row['DepartmentName']) ?></td>
                        <td><?= htmlspecialchars($row['JobTitle']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
