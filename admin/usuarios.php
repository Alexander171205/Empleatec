<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("location:../login.php"); exit(); }
require '../constants/db_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Traemos todos los usuarios ordenados por rol
    $stmt = $conn->prepare("SELECT member_no, first_name, last_name, email, role, city FROM tbl_users ORDER BY role ASC");
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
} catch(PDOException $e) { echo "Error: " . $e->getMessage(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Administrador</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="d-flex">
        <div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
            <h4>Panel Admin</h4>
            <hr>
            <a href="dashboard.php" class="nav-link text-white"><i class="fa fa-home"></i> Inicio</a>
            <a href="usuarios.php" class="nav-link text-warning"><i class="fa fa-users"></i> Usuarios</a>
            <a href="reportes.php" class="nav-link text-white"><i class="fa fa-file-excel"></i> Reportes</a>
            <a href="../logout.php" class="nav-link text-danger mt-5"><i class="fa fa-sign-out"></i> Salir</a>
        </div>

        <div class="p-4 w-100">
            <h3>Gestión de Usuarios y Empresas</h3>
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Rol Actual</th>
                                <th>Ciudad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($usuarios as $u): ?>
                            <tr>
                                <td><?php echo $u['first_name'] . " " . $u['last_name']; ?></td>
                                <td><?php echo $u['email']; ?></td>
                                <td>
                                    <?php 
                                    $clase = ($u['role'] == 'admin') ? 'bg-danger' : (($u['role'] == 'employer') ? 'bg-success' : 'bg-primary');
                                    ?>
                                    <span class="badge <?php echo $clase; ?>"><?php echo strtoupper($u['role']); ?></span>
                                </td>
                                <td><?php echo $u['city']; ?></td>
                                <td>
                                    <a href="editar_usuario.php?id=<?php echo $u['member_no']; ?>" class="btn btn-sm btn-info text-white">
                                        <i class="fa fa-edit"></i> Cambiar Rol
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>