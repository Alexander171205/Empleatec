<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("location:../login.php"); exit(); }
require '../constants/db_config.php';

$id = $_GET['id'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si se envía el formulario
    if (isset($_POST['actualizar'])) {
        $nuevo_rol = $_POST['role'];
        $stmt_upd = $conn->prepare("UPDATE tbl_users SET role = :role WHERE member_no = :id");
        $stmt_upd->bindParam(':role', $nuevo_rol);
        $stmt_upd->bindParam(':id', $id);
        $stmt_upd->execute();
        header("location:usuarios.php?m=Actualizado");
    }

    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) { echo "Error: " . $e->getMessage(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Rol</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm" style="max-width: 500px;">
        <h4>Cambiar Rol de Usuario</h4>
        <p>Usuario: <strong><?php echo $user['first_name']; ?></strong></p>
        <form method="POST">
            <div class="form-group mb-3">
                <label>Seleccionar nuevo Rol:</label>
                <select name="role" class="form-control">
                    <option value="employee" <?php if($user['role']=='employee') echo 'selected'; ?>>Candidato (Employee)</option>
                    <option value="employer" <?php if($user['role']=='employer') echo 'selected'; ?>>Empresa (Employer)</option>
                    <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Administrador</option>
                </select>
            </div>
            <button name="actualizar" type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
