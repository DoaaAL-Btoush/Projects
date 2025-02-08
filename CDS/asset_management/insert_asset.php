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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $asset_name = $_POST['asset_name'];
    $type = $_POST['type'];
    $assigned_to = $_POST['assigned_to'];
    $purchase_date = $_POST['purchase_date'];
    $warranty_expiry = $_POST['warranty_expiry'];
    $status = $_POST['status'];

    $sql = "INSERT INTO Assets (Asset_Name, Type, Assigned_To, Purchase_Date, Warranty_Expiry, Status) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $params = array($asset_name, $type, $assigned_to, $purchase_date, $warranty_expiry, $status);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "New asset added successfully!";
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }
}


sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Asset</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_form.css">


    <style>
        .form-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; 
            background-color: #f8f9fa; 
        }
        .card {
            width: 40%;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="card">
        <h2 class="text-center">Add New Asset</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Asset Name:</label>
                <input type="text" name="asset_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Type:</label>
                <select name="type" class="form-control" required>
                    <option value="Hardware">Hardware</option>
                    <option value="Software">Software</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Assigned Employee:</label>
                <select name="assigned_to" class="form-control" required>
                    <option value="1">Ahmad Abuali</option>
                    <option value="2">Laith Abuali</option>
                    <option value="3">Sami Alghareeb</option>
                    <option value="4">Abu Somalia</option>
                    <option value="5">Rawan Alnadi</option>
                    <option value="6">Rakan Insan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Purchase Date:</label>
                <input type="date" name="purchase_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Warranty Expiry:</label>
                <input type="date" name="warranty_expiry" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-control" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Add</button>
        </form>
    </div>
</div>

</body>
</html>