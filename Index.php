<?php
require("Libreria/Motor.php");
Plantilla::aplicar();

$condiciones = [];
$parametros = [];
$tipos = "";

// Definir el número de registros por página
$registros_por_pagina = 10;

// Obtener el número de página actual desde la URL (por defecto, página 1)
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Verificar si se ingresó un nombre
if (!empty($_GET['nombre'])) {
    $condiciones[] = "nombre LIKE ?";
    $parametros[] = "%" . $_GET['nombre'] . "%";
    $tipos .= "s";
}

// Verificar si se ingresó una fecha o rango de fechas
if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
    $condiciones[] = "DATE(fecha) BETWEEN ? AND ?";
    $parametros[] = $_GET['fecha_inicio'];
    $parametros[] = $_GET['fecha_fin'];
    $tipos .= "ss";
} elseif (!empty($_GET['fecha_inicio'])) {
    $condiciones[] = "DATE(fecha) >= ?";
    $parametros[] = $_GET['fecha_inicio'];
    $tipos .= "s";
} elseif (!empty($_GET['fecha_fin'])) {
    $condiciones[] = "DATE(fecha) <= ?";
    $parametros[] = $_GET['fecha_fin'];
    $tipos .= "s";
}

// Construcción de la consulta SQL dinámica
$consulta = "SELECT * FROM visitas";
$consulta_count = "SELECT COUNT(*) as total FROM visitas";

// Si hay condiciones, aplicarlas a ambas consultas
if (!empty($condiciones)) {
    $consulta .= " WHERE " . implode(" AND ", $condiciones);
    $consulta_count .= " WHERE " . implode(" AND ", $condiciones);
}

// Ordenar por fecha de forma descendente
$consulta .= " ORDER BY fecha DESC";

// Obtener el número total de registros para la paginación
$stmt_count = mysqli_prepare($connection, $consulta_count);
if (!empty($parametros)) {
    mysqli_stmt_bind_param($stmt_count, $tipos, ...$parametros);
}
mysqli_stmt_execute($stmt_count);
$resultado_count = mysqli_stmt_get_result($stmt_count);
$total_registros = mysqli_fetch_assoc($resultado_count)['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Agregar paginación a la consulta principal
$consulta .= " LIMIT ? OFFSET ?";
$parametros[] = $registros_por_pagina;
$parametros[] = $inicio;
$tipos .= "ii";

// Preparar y ejecutar la consulta
$stmt = mysqli_prepare($connection, $consulta);
if (!empty($parametros)) {
    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
}
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$visitantes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>

<h1 class="title has-text-centered">Visitantes</h1>

<!-- Formulario de búsqueda -->
<form method="GET" class="box">
    <div class="field is-grouped is-grouped-multiline">
        <div class="control has-icons-left">
            <input class="input" type="text" name="nombre" placeholder="Buscar por nombre" 
                value="<?php echo isset($_GET['nombre']) ? $_GET['nombre'] : ''; ?>">
            <span class="icon is-small is-left">
                <i class="fas fa-user"></i>
            </span>
        </div>

        <div class="control">
            <input class="input" type="date" name="fecha_inicio" 
                value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">
        </div>

        <div class="control">
            <input class="input" type="date" name="fecha_fin" 
                value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">
        </div>

        <div class="control">
            <button class="button is-primary">
                <span class="icon"><i class="fas fa-search"></i></span>
                <span>Buscar</span>
            </button>
        </div>
    </div>
</form>

<!-- Tabla de visitantes -->
<?php if (!empty($visitantes)): ?>
    <div class="table-container">
        <table class="table is-striped is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visitantes as $visitante): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($visitante['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($visitante['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($visitante['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($visitante['correo']); ?></td>
                        <td><?php echo date('d/m/Y h:i A', strtotime($visitante['fecha'])); ?></td>
                        <td>
                            <div class="buttons">
                                <a class="button is-info is-small" href="Editar.php?id=<?php echo $visitante['id']; ?>">
                                    <span class="icon"><i class="fas fa-edit"></i></span>
                                    <span>Editar</span>
                                </a>
                                <a class="button is-danger is-small" href="Eliminar.php?id=<?php echo $visitante['id']; ?>" 
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta visita?');">
                                    <span class="icon"><i class="fas fa-trash"></i></span>
                                    <span>Eliminar</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <nav class="pagination is-centered mt-4">
        <?php if ($pagina_actual > 1): ?>
            <a class="pagination-previous" href="?pagina=<?php echo $pagina_actual - 1; ?>">Anterior</a>
        <?php endif; ?>

        <ul class="pagination-list">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li>
                    <a class="pagination-link <?php echo ($i == $pagina_actual) ? 'is-current' : ''; ?>" 
                       href="?pagina=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>

        <?php if ($pagina_actual < $total_paginas): ?>
            <a class="pagination-next" href="?pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a>
        <?php endif; ?>
    </nav>

<?php else: ?>
    <div class="notification is-warning has-text-centered">
        <strong>No se encontraron visitantes.</strong>
    </div>
<?php endif; ?>
