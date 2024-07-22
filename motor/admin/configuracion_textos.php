<?php
global $wpdb;
$message = '';

// Obtener el nombre de la página actual para construir URLs correctas
$page_url = admin_url('admin.php?page=motor/admin/configuracion_textos.php');

// Manejar la solicitud POST para agregar o actualizar un texto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    $texto_id = intval($_POST['texto_id']);
    $idioma_id = intval($_POST['idioma_id']);
    $label_solo_ida = sanitize_text_field($_POST['label_solo_ida']);
    $label_ida_y_vuelta = sanitize_text_field($_POST['label_ida_y_vuelta']);
    $label_trayecto = sanitize_text_field($_POST['label_trayecto']);
    $label_fecha_viaje = sanitize_text_field($_POST['label_fecha_viaje']);
    $label_pasajeros = sanitize_text_field($_POST['label_pasajeros']);
    $label_codigo_promocion = sanitize_text_field($_POST['label_codigo_promocion']);
    $label_adultos = sanitize_text_field($_POST['label_adultos']);
    $label_ninos = sanitize_text_field($_POST['label_ninos']);
    $label_seniors = sanitize_text_field($_POST['label_seniors']);
    $label_bebes = sanitize_text_field($_POST['label_bebes']);
    $label_descuentos = sanitize_text_field($_POST['label_descuentos']);
    $label_sin_descuentos = sanitize_text_field($_POST['label_sin_descuentos']);
    $label_fn_general = sanitize_text_field($_POST['label_fn_general']);
    $label_fn_especial = sanitize_text_field($_POST['label_fn_especial']);
    $label_anos = sanitize_text_field($_POST['label_anos']);
    $label_reservar = sanitize_text_field($_POST['label_reservar']);
    $label_edad_adultos = sanitize_text_field($_POST['label_edad_adultos']);
    $label_edad_ninos = sanitize_text_field($_POST['label_edad_ninos']);
    $label_edad_seniors = sanitize_text_field($_POST['label_edad_seniors']);
    $label_edad_bebes = sanitize_text_field($_POST['label_edad_bebes']);
    $label_mascotas = sanitize_text_field($_POST['label_mascotas']);
    $label_anadir_vehiculo = sanitize_text_field($_POST['label_anadir_vehiculo']);



    $table_textos = $wpdb->prefix . 'insotel_motor_textos';
    $table_idiomas = $wpdb->prefix . 'insotel_motor_idiomas';

    // Verificar si el texto ya existe para el idioma
    $existing_idioma = $wpdb->get_var($wpdb->prepare(
        "SELECT * FROM $table_idiomas WHERE id = %s",
        $idioma_id,
    ));



    if ($existing_idioma != null) {
        if ($texto_id > 0) {
            $result = $wpdb->update(
                $table_textos,
                array(
                    'idioma_id' => $idioma_id,
                    'label_solo_ida' => $label_solo_ida,
                    'label_ida_y_vuelta' => $label_ida_y_vuelta,
                    'label_trayecto' => $label_trayecto,
                    'label_fecha_viaje' => $label_fecha_viaje,
                    'label_pasajeros' => $label_pasajeros,
                    'label_codigo_promocion' => $label_codigo_promocion,
                    'label_adultos' => $label_adultos,
                    'label_ninos' => $label_ninos,
                    'label_seniors' => $label_seniors,
                    'label_bebes' => $label_bebes,
                    'label_descuentos' => $label_descuentos,
                    'label_sin_descuentos' => $label_sin_descuentos,
                    'label_fn_general' => $label_fn_general,
                    'label_fn_especial' => $label_fn_especial,
                    'label_anos' => $label_anos,
                    'label_reservar' => $label_reservar,
                    'label_edad_adultos' => $label_edad_adultos,
                    'label_edad_ninos' => $label_edad_ninos,
                    'label_edad_seniors' => $label_edad_seniors,
                    'label_edad_bebes' => $label_edad_bebes,
                    'label_mascotas' => $label_mascotas,
                    'label_anadir_vehiculo' => $label_anadir_vehiculo,
                ),
                array('id' => $texto_id)
            );
            $message = $result !== false ? 'Texto actualizado correctamente.' : 'Hubo un error al actualizar el texto.';
        } else {
            $result = $wpdb->insert(
                $table_textos,
                array(
                    'idioma_id' => $idioma_id,
                    'label_solo_ida' => $label_solo_ida,
                    'label_ida_y_vuelta' => $label_ida_y_vuelta,
                    'label_trayecto' => $label_trayecto,
                    'label_fecha_viaje' => $label_fecha_viaje,
                    'label_pasajeros' => $label_pasajeros,
                    'label_codigo_promocion' => $label_codigo_promocion,
                    'label_adultos' => $label_adultos,
                    'label_ninos' => $label_ninos,
                    'label_seniors' => $label_seniors,
                    'label_bebes' => $label_bebes,
                    'label_descuentos' => $label_descuentos,
                    'label_sin_descuentos' => $label_sin_descuentos,
                    'label_fn_general' => $label_fn_general,
                    'label_fn_especial' => $label_fn_especial,
                    'label_anos' => $label_anos,
                    'label_reservar' => $label_reservar,
                    'label_edad_adultos' => $label_edad_adultos,
                    'label_edad_ninos' => $label_edad_ninos,
                    'label_edad_seniors' => $label_edad_seniors,
                    'label_edad_bebes' => $label_edad_bebes,
                    'label_mascotas' => $label_mascotas,
                    'label_anadir_vehiculo' => $label_anadir_vehiculo,
                )
            );
            $message = $result !== false ? 'Texto insertado correctamente.' : 'Hubo un error al insertar el texto.';
        }
    } else {
        $message = "No existe este idioma $idioma_id";
    }
}

// Manejar la solicitud GET para eliminar un texto
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $texto_id = intval($_GET['id']);

    // Eliminar el texto
    $table_textos = $wpdb->prefix . 'insotel_motor_textos';
    $result = $wpdb->delete($table_textos, array('id' => $texto_id));
    $message = $result !== false ? 'Texto eliminado correctamente.' : 'Hubo un error al eliminar el texto.';
}

if (isset($_GET['action']) && $_GET['action'] === 'create' && isset($_GET['idioma_id'])) {
}

// Obtener la lista de textos
$textos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}insotel_motor_textos");
$idiomas = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}insotel_motor_idiomas");

function getIdioma($idIdiomas, $idiomas)
{
    $valorDevuelto = "";
    foreach ($idiomas as $idioma) {
        if ($idioma->id == $idIdiomas) {
            $valorDevuelto = $idioma->idioma;
        }
    }
    return $valorDevuelto;
}

function getTextoByIdioma($idIdioma, $textos)
{
    $textoSeleccionado = [];
    foreach ($textos as $texto) {
        if ($idIdioma == $texto->idioma_id)
            $textoSeleccionado = $texto;
    }
    return $textoSeleccionado;
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

        <h1 class="mb-4">CONFIGURACIÓN DE LOS TEXTOS DEL PLUGIN</h1>
        <div class="w-100 h-100 d-flex align-items-start justify-content-between">
            <div class="card shadow-sm mb-5">
                <div class="card-header">
                    <h2 class="h5 mb-0">Agregar/Editar Texto</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo esc_url($page_url); ?>" id="formulario_configuracion" name="formulario_configuracion">
                        <div class="mb-3">
                            <label for="idioma_id" class="form-label">Idioma:</label>
                            <input type="hidden" id="texto_id" name="texto_id" value="<?php echo isset($_GET['id']) ? intval($_GET['id']) : 0; ?>">
                            <?php
                            $idioma_id_mostrar = isset($_GET['idioma_id']) ? intval($_GET['idioma_id']) : '';
                            ?>
                            <input type="hidden" id="idioma_id" name="idioma_id" value="<?php echo $idioma_id_mostrar; ?>">
                            <input readonly type="text" class="form-control" id="idioma_id_mostar" name="idioma_id_mostrar" value="<?php echo getIdioma($idioma_id_mostrar, $idiomas);  ?>">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="label_solo_ida">Label solo ida:</label><br>
                                <input type="text" class="form-control" id="label_solo_ida" name="label_solo_ida" value="<?php echo isset($_GET['label_solo_ida']) ? $_GET['label_solo_ida'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_ida_y_vuelta">Label ida y vuelta:</label><br>
                                <input type="text" class="form-control" id="label_ida_y_vuelta" name="label_ida_y_vuelta" value="<?php echo isset($_GET['label_ida_y_vuelta']) ? $_GET['label_ida_y_vuelta'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_trayecto">Label trayecto:</label><br>
                                <input type="text" class="form-control" id="label_trayecto" name="label_trayecto" value="<?php echo isset($_GET['label_trayecto']) ? $_GET['label_trayecto'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_fecha_viaje">Label fech viaje:</label><br>
                                <input type="text" class="form-control" id="label_fecha_viaje" name="label_fecha_viaje" value="<?php echo isset($_GET['label_fecha_viaje']) ? $_GET['label_fecha_viaje'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_pasajeros">Label pasajeros:</label><br>
                                <input type="text" class="form-control" id="label_pasajeros" name="label_pasajeros" value="<?php echo isset($_GET['label_pasajeros']) ? $_GET['label_pasajeros'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_codigo_promocion">Label codigo promocion:</label><br>
                                <input type="text" class="form-control" id="label_codigo_promocion" name="label_codigo_promocion" value="<?php echo isset($_GET['label_codigo_promocion']) ? $_GET['label_codigo_promocion'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_adultos">Label adultos:</label><br>
                                <input type="text" class="form-control" id="label_adultos" name="label_adultos" value="<?php echo isset($_GET['label_adultos']) ? $_GET['label_adultos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_ninos">Label ninos:</label><br>
                                <input type="text" class="form-control" id="label_ninos" name="label_ninos" value="<?php echo isset($_GET['label_ninos']) ? $_GET['label_ninos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_seniors">Label seniors:</label><br>
                                <input type="text" class="form-control" id="label_seniors" name="label_seniors" value="<?php echo isset($_GET['label_seniors']) ? $_GET['label_seniors'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_bebes">Label bebes:</label><br>
                                <input type="text" class="form-control" id="label_bebes" name="label_bebes" value="<?php echo isset($_GET['label_bebes']) ? $_GET['label_bebes'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_descuentos">Label descuentos:</label><br>
                                <input type="text" class="form-control" id="label_descuentos" name="label_descuentos" value="<?php echo isset($_GET['label_descuentos']) ? $_GET['label_descuentos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_sin_descuentos">Label sin descuentos:</label><br>
                                <input type="text" class="form-control" id="label_sin_descuentos" name="label_sin_descuentos" value="<?php echo isset($_GET['label_sin_descuentos']) ? $_GET['label_sin_descuentos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_fn_general">Label fn general:</label><br>
                                <input type="text" class="form-control" id="label_fn_general" name="label_fn_general" value="<?php echo isset($_GET['label_fn_general']) ? $_GET['label_fn_general'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_fn_especial">Label fn especial:</label><br>
                                <input type="text" class="form-control" id="label_fn_especial" name="label_fn_especial" value="<?php echo isset($_GET['label_fn_especial']) ? $_GET['label_fn_especial'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_anos">Label anos:</label><br>
                                <input type="text" class="form-control" id="label_anos" name="label_anos" value="<?php echo isset($_GET['label_anos']) ? $_GET['label_anos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_reservar">Label reservar:</label><br>
                                <input type="text" class="form-control" id="label_reservar" name="label_reservar" value="<?php echo isset($_GET['label_reservar']) ? $_GET['label_reservar'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_edad_adultos">Label edad adultos:</label><br>
                                <input type="text" class="form-control" id="label_edad_adultos" name="label_edad_adultos" value="<?php echo isset($_GET['label_edad_adultos']) ? $_GET['label_edad_adultos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_edad_ninos">Label edad ninos:</label><br>
                                <input type="text" class="form-control" id="label_edad_ninos" name="label_edad_ninos" value="<?php echo isset($_GET['label_edad_ninos']) ? $_GET['label_edad_ninos'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_edad_seniors">Label edad seniors:</label><br>
                                <input type="text" class="form-control" id="label_edad_seniors" name="label_edad_seniors" value="<?php echo isset($_GET['label_edad_seniors']) ? $_GET['label_edad_seniors'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_edad_bebes">Label edad bebes:</label><br>
                                <input type="text" class="form-control" id="label_edad_bebes" name="label_edad_bebes" value="<?php echo isset($_GET['label_edad_bebes']) ? $_GET['label_edad_bebes'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_mascotas">Label mascotas:</label><br>
                                <input type="text" class="form-control" id="label_mascotas" name="label_mascotas" value="<?php echo isset($_GET['label_mascotas']) ? $_GET['label_mascotas'] : ''; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="label_anadir_vehiculo">Label añadir vehiculo:</label><br>
                                <input type="text" class="form-control" id="label_anadir_vehiculo" name="label_anadir_vehiculo" value="<?php echo isset($_GET['label_anadir_vehiculo']) ? $_GET['label_anadir_vehiculo'] : ''; ?>" required>
                            </div>
                        </div>

                        <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm" style="min-width: 500px;">
                <div class="card-header">
                    <h2 class="h5 mb-0">Lista de Textos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Idioma</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($idiomas as $idioma) : ?>
                                <?php $texto = getTextoByIdioma($idioma->id, $textos) ?>

                                <tr>
                                    <td><?php echo $idioma->idioma ?></td>
                                    <?php
                                    if (isset($texto->id)) {
                                    ?>
                                        <td>
                                            <a href="
                                    <?php
                                        echo esc_url(add_query_arg(
                                            array(
                                                'id' => $texto->id,
                                                'idioma_id' => $texto->idioma_id,
                                                'label_solo_ida' => $texto->label_solo_ida,
                                                'label_ida_y_vuelta' => $texto->label_ida_y_vuelta,
                                                'label_trayecto' => $texto->label_trayecto,
                                                'label_fecha_viaje' => $texto->label_fecha_viaje,
                                                'label_pasajeros' => $texto->label_pasajeros,
                                                'label_codigo_promocion' => $texto->label_codigo_promocion,
                                                'label_adultos' => $texto->label_adultos,
                                                'label_ninos' => $texto->label_ninos,
                                                'label_seniors' => $texto->label_seniors,
                                                'label_bebes' => $texto->label_bebes,
                                                'label_descuentos' => $texto->label_descuentos,
                                                'label_sin_descuentos' => $texto->label_sin_descuentos,
                                                'label_fn_general' => $texto->label_fn_general,
                                                'label_fn_especial' => $texto->label_fn_especial,
                                                'label_anos' => $texto->label_anos,
                                                'label_reservar' => $texto->label_reservar,
                                                'label_edad_adultos' => $texto->label_edad_adultos,
                                                'label_edad_ninos' => $texto->label_edad_ninos,
                                                'label_edad_seniors' => $texto->label_edad_seniors,
                                                'label_edad_bebes' => $texto->label_edad_bebes,
                                                'label_mascotas' => $texto->label_mascotas,
                                                'label_anadir_vehiculo' => $texto->label_anadir_vehiculo,
                                            ),
                                            $page_url
                                        ));
                                    ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <a href="<?php echo esc_url(add_query_arg(array('action' => 'delete', 'id' => $texto->id), $page_url)); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este texto?');">Eliminar</a>
                                        </td>
                                    <?php
                                    } else {
                                    ?>
                                        <td>
                                            <a href="<?php echo esc_url(add_query_arg(array('action' => 'create', 'idioma_id' => $idioma->id), $page_url)); ?>" class="btn btn-success btn-sm">Crear</a>
                                        </td>
                                    <?php
                                    }

                                    ?>

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