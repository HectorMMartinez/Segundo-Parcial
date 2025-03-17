<?php
require("Libreria\Motor.php"); 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $consulta = "DELETE FROM visitas WHERE id = ?";
    $stmt = mysqli_prepare($connection, $consulta);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: visitas.php");
        exit();
    } else {
        echo "Error al eliminar la visita.";
    }
} else {
    echo "ID inválido.";
}
?>
