<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: proyectos.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM proyectos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$proyecto = $stmt->get_result()->fetch_assoc();
if (!$proyecto) {
    header('Location: proyectos.php');
    exit;
}

$error = '';
$estados = ['operacion','pruebas','construccion','aprobado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim($_POST['nombre']);
    $capacidad_mw = (float)$_POST['capacidad_mw'];
    $region       = trim($_POST['region']);
    $departamento = trim($_POST['departamento']);
    $municipio    = trim($_POST['municipio']);
    $estado       = $_POST['estado'];
    $empresa      = trim($_POST['empresa']);
    $anio_inicio  = $_POST['anio_inicio'] ? (int)$_POST['anio_inicio'] : null;
    $lat          = $_POST['lat'] ? (float)$_POST['lat'] : null;
    $lng          = $_POST['lng'] ? (float)$_POST['lng'] : null;

    if (!$nombre || !$capacidad_mw || !$region || !$departamento || !$municipio || !$estado || !$empresa) {
        $error = 'Todos los campos obligatorios deben estar llenos.';
    } else {
        $stmt = $conn->prepare("UPDATE proyectos SET nombre=?, capacidad_mw=?, region=?, departamento=?, municipio=?, estado=?, empresa=?, anio_inicio=?, lat=?, lng=? WHERE id=?");
        $stmt->bind_param('sdssssssddi', $nombre, $capacidad_mw, $region, $departamento, $municipio, $estado, $empresa, $anio_inicio, $lat, $lng, $id);
        if ($stmt->execute()) {
            header('Location: proyectos.php?msg=actualizado');
            exit;
        } else {
            $error = 'Error al actualizar: ' . $conn->error;
        }
    }
}

adminHeader('Editar proyecto');
?>

<div class="card">
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Nombre del proyecto *</label>
                <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($proyecto['nombre']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Capacidad (MW) *</label>
                <input type="number" step="any" name="capacidad_mw" class="form-control" required value="<?= $proyecto['capacidad_mw'] ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Región *</label>
                <select name="region" class="form-control" required>
                    <option value="Caribe" <?= $proyecto['region']=='Caribe'?'selected':'' ?>>Caribe</option>
                    <option value="Andina" <?= $proyecto['region']=='Andina'?'selected':'' ?>>Andina</option>
                    <option value="Orinoquía" <?= $proyecto['region']=='Orinoquía'?'selected':'' ?>>Orinoquía</option>
                    <option value="Pacífica" <?= $proyecto['region']=='Pacífica'?'selected':'' ?>>Pacífica</option>
                    <option value="Amazonía" <?= $proyecto['region']=='Amazonía'?'selected':'' ?>>Amazonía</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Departamento *</label>
                <input type="text" name="departamento" class="form-control" required value="<?= htmlspecialchars($proyecto['departamento']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Municipio *</label>
                <input type="text" name="municipio" class="form-control" required value="<?= htmlspecialchars($proyecto['municipio']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Estado *</label>
                <select name="estado" class="form-control" required>
                    <?php foreach ($estados as $e): ?>
                        <option value="<?= $e ?>" <?= $proyecto['estado']==$e?'selected':'' ?>><?= ucfirst($e) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Empresa desarrolladora *</label>
                <input type="text" name="empresa" class="form-control" required value="<?= htmlspecialchars($proyecto['empresa']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Año de inicio (opcional)</label>
                <input type="number" name="anio_inicio" class="form-control" value="<?= $proyecto['anio_inicio'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Coordenadas (lat, lng) – opcional</label>
                <div style="display:flex;gap:1rem">
                    <input type="text" name="lat" class="form-control" placeholder="Latitud" value="<?= $proyecto['lat'] ?? '' ?>">
                    <input type="text" name="lng" class="form-control" placeholder="Longitud" value="<?= $proyecto['lng'] ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-amber">Actualizar proyecto</button>
                <a href="proyectos.php" class="btn btn-outline-green">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php adminFooter(); ?>