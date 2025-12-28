<?php
session_start();
// Si no es admin, lo mandamos al login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:../login.php");
    exit();
}

require '../constants/db_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Contar total de usuarios
    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM tbl_users");
    $stmt1->execute();
    $total_usuarios = $stmt1->fetchColumn();

    // Contar total de empleos
    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM tbl_jobs");
    $stmt2->execute();
    $total_empleos = $stmt2->fetchColumn();

    // Contar total de empresas (rol employer)
    $stmt3 = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE role = 'employer'");
    $stmt3->execute();
    $total_empresas = $stmt3->fetchColumn();

} catch(PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrativo - Empleatec</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex; min-height: 100vh; background: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 260px; background: #232e3c; color: white; padding: 20px; transition: all 0.3s; }
        .sidebar h3 { font-size: 1.2rem; text-align: center; margin-bottom: 30px; color: #3b7ddd; }
        .nav-link { color: #adb5bd; padding: 12px 15px; border-radius: 5px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .nav-link i { margin-right: 10px; width: 20px; text-align: center; }
        .content { flex: 1; padding: 40px; }
        .card-box { background: white; padding: 25px; border-radius: 10px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); transition: transform 0.2s; }
        .card-box:hover { transform: translateY(-5px); }
        .icon-shape { width: 48px; height: 48px; background: #e0eafc; color: #3b7ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <h3><i class="fa fa-briefcase"></i> EMPLEATEC ADMIN</h3>
    <hr style="background: rgba(255,255,255,0.1)">
    <ul class="nav flex-column">
        <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="nav-item"><a href="usuarios.php" class="nav-link"><i class="fa fa-users"></i> Gestionar Usuarios</a></li>
        <li class="nav-item"><a href="reportes.php" class="nav-link"><i class="fa fa-file-invoice"></i> Reportes Excel</a></li>
        <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="fa fa-sign-out-alt"></i> Cerrar Sesión</a></li>
    </ul>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Resumen del Sistema</h2>
        <span class="badge bg-primary px-3 py-2">Rol: <?php echo strtoupper($_SESSION['role']); ?></span>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card-box">
                <div class="icon-shape"><i class="fa fa-user-friends"></i></div>
                <h6 class="text-muted font-weight-normal">Total Usuarios</h6>
                <h2 class="mb-0"><?php echo $total_usuarios; ?></h2>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card-box">
                <div class="icon-shape" style="background: #e1fcef; color: #28a745;"><i class="fa fa-building"></i></div>
                <h6 class="text-muted font-weight-normal">Empresas Activas</h6>
                <h2 class="mb-0"><?php echo $total_empresas; ?></h2>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card-box">
                <div class="icon-shape" style="background: #fff5e6; color: #fd7e14;"><i class="fa fa-clipboard-list"></i></div>
                <h6 class="text-muted font-weight-normal">Empleos Publicados</h6>
                <h2 class="mb-0"><?php echo $total_empleos; ?></h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card-box">
                <h4>Acciones Rápidas</h4>
                <hr>
                <a href="usuarios.php" class="btn btn-primary me-2"><i class="fa fa-user-plus"></i> Gestionar Roles</a>
                <a href="reportes.php" class="btn btn-success"><i class="fa fa-download"></i> Generar Reporte General</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>