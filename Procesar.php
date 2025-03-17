<?php
require("Libreria\Motor.php");
Plantilla::aplicar();

if (!isset($connection)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Validar y limpiar los datos del formulario
$nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : '';
$telefono = isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '';
$correo = isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '';

if (empty($nombre) || empty($apellido) || empty($telefono) || empty($correo)) {
    die("Error: Todos los campos son obligatorios.");
}

if (!isset($_POST['id'])) {
    // Inserción de nueva visita
    $consulta = "INSERT INTO visitas (nombre, apellido, telefono, correo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $consulta);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $apellido, $telefono, $correo);
} else {
    // Actualización de visita existente
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];

    if (empty($id) || empty($fecha)) {
        die("Error: Datos insuficientes para actualizar la visita.");
    }

    $consulta = "UPDATE visitas SET nombre = ?, apellido = ?, telefono = ?, correo = ?, fecha = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $consulta);
    mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $apellido, $telefono, $correo, $fecha, $id);
}

// Ejecutar consulta
if (mysqli_stmt_execute($stmt)) {
    header("Location: Visitas.php");
    exit();
} else {
    echo "Error al guardar la visita: " . mysqli_error($connection);
}
?>