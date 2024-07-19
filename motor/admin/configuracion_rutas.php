<?php
global $wpdb;
$message = '';
$page_url = admin_url('admin.php?page=motor/admin/configuracion_rutas.php');
$table_puertos = $wpdb->prefix . 'insotel_motor_puertos';
$table_rutas = $wpdb->prefix . 'insotel_motor_rutas';


// Manejar la solicitud POST para agregar o actualizar un texto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    $id = intval($_POST['id']);
    $nombre_ruta = sanitize_text_field($_POST['nombre_ruta']);
    $puerto_ruta_ida = intval($_POST['puerto_ruta_ida']);
    $puerto_ruta_vuelta = intval($_POST['puerto_ruta_vuelta']);


    if ($id > 0) {
        $result = $wpdb->update(
            $table_rutas,
            array(
                'nombre_ruta' => $nombre_ruta,
                'puerto_ruta_ida' => intval($puerto_ruta_ida),
                'puerto_ruta_vuelta' => intval($puerto_ruta_vuelta),
            ),
            array('id' => $id)
        );
        $wpdb->print_error();
        $message = $result !== false ? 'Ruta actualizada correctamente.' : 'Hubo un error al actualizar la ruta.';
    } else {
        $result = $wpdb->insert(
            $table_rutas,
            array(
                'nombre_ruta' => $nombre_ruta,
                'puerto_ruta_ida' => intval($puerto_ruta_ida),
                'puerto_ruta_vuelta' => intval($puerto_ruta_vuelta),
            )
        );
        $message = $result !== false ? 'Ruta insertada correctamente.' : "Hubo un error al insertar la ruta.";
    }
}

// Manejar la solicitud GET para eliminar un texto
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Eliminar el texto
    $result = $wpdb->delete($table_rutas, array('id' => $id));
    $message = $result !== false ? 'Rutas eliminado correctamente.' : 'Hubo un error al eliminar la ruta.';
}


// Obtener la lista de textos
$puertos = $wpdb->get_results("SELECT * FROM $table_puertos");
$rutas = $wpdb->get_results("SELECT * FROM $table_rutas");



function getNamePuertoById($id_puerto, $puertos)
{
    $valorDevuelto = "";
    foreach ($puertos as $puerto) {
        if ($puerto->id == $id_puerto) {
            $valorDevuelto = $puerto->nombre_ruta;
        }
    }
    return $valorDevuelto;
}

?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <?php if (!empty($message)) : ?>
            <div id="update-result" class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <h1 class="mb-4">CONFIGURACIÓN DE LAS RUTAS DEL PLUGIN</h1>
        <div class="w-100 h-100 d-flex align-items-start justify-content-between">
            <div class="card shadow-sm mb-5">
                <div class="card-header">
                    <?php $titulo = isset($_GET['id']) ? "Editar " : "Crear " ?>
                    <h2 class="h5 mb-0"><?php echo $titulo; ?>Ruta</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo esc_url($page_url); ?>" id="formulario_configuracion" name="formulario_configuracion">
                        <input type="hidden" id="id" name="id" value="<?php echo isset($_GET['id']) ? intval($_GET['id']) : 0; ?>">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="nombre_ruta">Nombre ruta:</label><br>
                                <input type="text" class="form-control" id="nombre_ruta" name="nombre_ruta" value="<?php echo isset($_GET['nombre_ruta']) ? $_GET['nombre_ruta'] : ''; ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="nombre_ruta">Puerto Origen:</label><br>
                                <select name="puerto_ruta_ida" id="puerto_ruta_ida">
                                    <?php
                                    $rutaidaComprobar = isset($_GET['puerto_ruta_ida']) ? $_GET['puerto_ruta_ida'] : 0;
                                    foreach ($puertos as $puerto) {
                                        $selected = ($puerto->id == $rutaidaComprobar) ? 'selected' : '';
                                        echo "<option value='$puerto->id' $selected>$puerto->nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="nombre_ruta">Puerto Destino:</label><br>
                                <select name="puerto_ruta_vuelta" id="puerto_ruta_vuelta">
                                    <?php
                                    $rutaVueltaComprobar = isset($_GET['puerto_ruta_vuelta']) ? $_GET['puerto_ruta_vuelta'] : 0;
                                    foreach ($puertos as $puerto) {
                                        $selected = ($puerto->id == $rutaVueltaComprobar) ? 'selected' : '';
                                        echo "<option value='$puerto->id' $selected>$puerto->nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
                            <?php $disabled = isset($_GET['id']) ? "" : "disabled" ?>
                            <button id="boton_guardar" name="reset_table" type="submit" class="btn btn-primary mt-3" <?php echo $disabled; ?>>Volver a la creación</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm" style="min-width: 500px;">
                <div class="card-header">
                    <h2 class="h5 mb-0">Lista de Rutas</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre Ruta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rutas as $ruta) : ?>
                                <tr>
                                    <td><?php echo $ruta->nombre_ruta ?></td>
                                    <td>
                                        <a href="
                                    <?php
                                    echo esc_url(add_query_arg(
                                        array(
                                            'id' => $ruta->id,
                                            'nombre_ruta' => $ruta->nombre_ruta,
                                            'puerto_ruta_ida' => $ruta->puerto_ruta_ida,
                                            'puerto_ruta_vuelta' => $ruta->puerto_ruta_vuelta,
                                        ),
                                        $page_url
                                    ));
                                    ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="<?php echo esc_url(add_query_arg(array('action' => 'delete', 'id' => $ruta->id), $page_url)); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta ruta?');">Eliminar</a>
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