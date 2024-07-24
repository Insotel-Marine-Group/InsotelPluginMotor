<?php
global $wpdb;
$message = '';
$messageClass = '';
$page_url = admin_url('admin.php?page=motor/admin/configuracion_puertos.php');
$table_puertos = $wpdb->prefix . 'insotel_motor_puertos';

// Manejar la solicitud POST para agregar o actualizar un puerto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    $nombre = $_POST['nombre'];
    $id = $_POST['id'];
    $valor = $_POST['valor'];
    $orden = $_POST['orden'];


    // Verificar si el nombre ya existe
    $existing_nombre = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_puertos WHERE nombre = %s AND id != %d",
        $nombre,
        $id
    ));

    // Verificar si el valor ya existe
    $existing_valor = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_puertos WHERE valor = %s AND id != %d",
        $valor,
        $id
    ));

    // Verificar si el orden ya existe
    $existing_orden = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_puertos WHERE orden = %d AND id != %d",
        $orden,
        $id
    ));

    // Verificar que el campo 'orden' sea numérico
    if (!is_numeric($orden)) {
        $message = 'El campo "orden" debe ser un número.';
        $messageClass = 'alert-warning';
    } else if ($existing_nombre != null || $existing_valor != null || $existing_orden != null) {
        if ($existing_nombre != null) {
            $message = 'El nombre del puerto ya existe.';
            $messageClass = 'alert-warning';
        } else if ($existing_valor != null) {
            $message = 'El valor del puerto ya existe.';
            $messageClass = 'alert-warning';
        } else if ($existing_orden != null) {
            $message = 'El orden del puerto ya existe.';
            $messageClass = 'alert-warning';
        }
    } else {
        if ($id > 0) {
            $result = $wpdb->update(
                $table_puertos,
                array('nombre' => $nombre, 'valor' => $valor, 'orden' => $orden),
                array('id' => $id)
            );
            $message = $result !== false ? 'Puerto actualizado correctamente.' : 'Hubo un error al actualizar el puerto.';
            $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
        } else {
            $result = $wpdb->insert(
                $table_puertos,
                array('nombre' => $nombre, 'valor' => $valor, 'orden' => $orden)
            );
            $message = $result !== false ? 'Puerto insertado correctamente.' : 'Hubo un error al insertar el puerto.';
            $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
        }
    }
}

// Manejar la solicitud GET para eliminar un idioma
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_puerto = intval($_GET['id']);

    // Eliminar las rutas asociadas con este puerto
    $table_textos = $wpdb->prefix . 'insotel_motor_rutas';
    $wpdb->delete($table_textos, array('puerto_ruta_ida' => $id_puerto));
    $wpdb->delete($table_textos, array('puerto_ruta_vuelta' => $id_puerto));

    // Eliminar el puerto
    $result = $wpdb->delete($table_puertos, array('id' => $id_puerto));
    $message = $result !== false ? 'Puerto y rutas asociadas eliminadas correctamente.' : 'Hubo un error al eliminar el puerto.';
    $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
    $_GET['id'] = null;
}

// Obtener la lista de puertos
$puertos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}insotel_motor_puertos");
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">

        <?php if (!empty($message)) : ?>
            <div id="update-result" class="alert <?php echo $messageClass; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <h1 class="mb-4">CONFIGURACIÓN PUERTOS DEL PLUGIN</h1>


        <div class="w-100 h-100 d-flex justify-content-between align-items-start">
            <div class="card shadow-sm mb-5" style="min-width: 500px;">
                <div class="card-header">
                    <?php $titulo = isset($_GET['id']) ? "Editar " : "Crear " ?>
                    <h2 class="h5 mb-0"><?php echo $titulo; ?>Puertos</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo esc_url($page_url); ?>" id="formulario_configuracion" name="formulario_configuracion">
                        <div class="row">
                            <input type="hidden" id="id" name="id" value="<?php echo isset($_GET['id']) ? intval($_GET['id']) : 0; ?>">
                            <div class="col">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($_GET['nombre']) ? sanitize_text_field($_GET['nombre']) : ''; ?>" required>
                            </div>
                            <div class="col">
                                <label for="valor" class="form-label">Valor:</label>
                                <input type="text" class="form-control" id="valor" name="valor" value="<?php echo isset($_GET['valor']) ? sanitize_text_field($_GET['valor']) : ''; ?>" required>
                            </div>
                            <div class="col">
                                <label for="orden" class="form-label">Orden:</label>
                                <input type="number" class="form-control" id="orden" name="orden" value="<?php echo isset($_GET['orden']) ? intval($_GET['orden']) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <?php
                            if (isset($_GET['id'])) {
                            ?>
                                <a href="<?php echo esc_url($page_url); ?>" class="btn btn-primary mt-3">Volver a la creación</a>
                            <?php
                            }
                            ?>
                            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm" style="min-width: 500px;">
                <div class="card-header">
                    <h2 class="h5 mb-0">Lista de Puertos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Valor</th>
                                <th>Orden</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($puertos as $puerto) : ?>
                                <tr>
                                    <td><?php echo $puerto->nombre; ?></td>
                                    <td><?php echo $puerto->valor; ?></td>
                                    <td><?php echo $puerto->orden; ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(add_query_arg(array('id' => $puerto->id, 'nombre' => $puerto->nombre, 'valor' => $puerto->valor, 'orden' => $puerto->orden), $page_url)); ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="<?php echo esc_url(add_query_arg(array('action' => 'delete', 'id' => $puerto->id), $page_url)); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este puerto y todas las rutas asociadas?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>