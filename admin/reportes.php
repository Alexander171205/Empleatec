<?php
session_start();
require '../constants/db_config.php';

if (isset($_GET['descargar'])) {
    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=Reporte_Usuarios_Empleatec.xls");
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $stmt = $conn->query("SELECT first_name, last_name, email, role, city FROM tbl_users");
    
    echo "Nombre\tApellido\tEmail\tRol\tCiudad\n";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['first_name']."\t".$row['last_name']."\t".$row['email']."\t".$row['role']."\t".$row['city']."\n";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light p-5 text-center">
    <div class="card shadow-sm mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3>Generar Reportes</h3>
            <p>Descarga la lista completa de usuarios en formato Excel.</p>
            <a href="reportes.php?descargar=1" class="btn btn-success btn-lg">
                <i class="fa fa-download"></i> Descargar Excel
            </a>
            <br><br>
            <a href="dashboard.php">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>