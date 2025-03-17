<?php
require("Libreria\Motor.php");
Plantilla::aplicar();

if (!isset($connection)) {
    die("Error: No se pudo conectar a la base de datos.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Error: ID no válido.");
}

$consulta = "SELECT * FROM visitas WHERE id = ?";
$stmt = mysqli_prepare($connection, $consulta);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$visitante = mysqli_fetch_assoc($resultado);

if (!$visitante) {
    die("Error: No se encontró la visita.");
}

$nombre = htmlspecialchars($visitante['nombre']);
$apellido = htmlspecialchars($visitante['apellido']);
$telefono = htmlspecialchars($visitante['telefono']);
$correo = htmlspecialchars($visitante['correo']);
$fecha = htmlspecialchars($visitante['fecha']);
?>

<section class="section">
    <div class="container">
        <div class="box">
            <h1 class="title has-text-centered">Editar Visita</h1>

            <form action="Procesar.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="field">
                    <label class="label">Nombre</label>
                    <div class="control">
                        <input type="text" name="nombre" class="input is-fullwidth" value="<?php echo $nombre; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Apellido</label>
                    <div class="control">
                        <input type="text" name="apellido" class="input is-fullwidth" value="<?php echo $apellido; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Teléfono</label>
                    <div class="control">
                        <input type="text" name="telefono" class="input is-fullwidth" value="<?php echo $telefono; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input type="email" name="correo" class="input is-fullwidth" value="<?php echo $correo; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Fecha</label>
                    <div class="control">
                    <input type="datetime-local" name="fecha" class="input is-fullwidth" value="<?php echo date('Y-m-d\TH:i', strtotime($fecha)); ?>" required>
                    </div>
                </div>

                <div class="field has-text-centered">
                    <button class="button is-link is-medium">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</section>