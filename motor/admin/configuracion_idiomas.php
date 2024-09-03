<?php
global $wpdb;
$message = '';
$messageClass = '';
$page_url = admin_url('admin.php?page=motor/admin/configuracion_idiomas.php');
$table_idiomas = $wpdb->prefix . 'insotel_motor_idiomas';
$table_textos = $wpdb->prefix . 'insotel_motor_textos';

// Manejar la solicitud POST para agregar o actualizar un idioma
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    $idioma = sanitize_text_field($_POST['idioma']);
    $idioma_id = $_POST['idioma_id'];
    $idioma_por_defecto = $_POST['idioma_por_defecto'] === "on" ? true : false;;

    // Verificar si el idioma ya existe
    $existing_idioma = $wpdb->get_var($wpdb->prepare(
        "SELECT idioma FROM $table_idiomas WHERE idioma = %s",
        $idioma
    ));

    

    $actualizacion_idioma_por_defecto = true;

    if ($idioma_por_defecto) {
        $query = "UPDATE $table_idiomas SET idioma_por_defecto = %d";
        $result = $wpdb->query($wpdb->prepare($query, false));
        $message = $result !== false ? 'Actualizadas correctamente en todas las filas.' : 'Hubo un error al actualizar idioma_por_defecto en todas las filas.';
        $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
        if ($result !== false){}else{
            $actualizacion_idioma_por_defecto = false;
        }
    }
    echo "Actualizacion de la filas: $actualizacion_idioma_por_defecto </br>";
    echo "Existing Idioma: $existing_idioma </br>";

    if ($actualizacion_idioma_por_defecto) {
        if ($existing_idioma === null) {
            if ($idioma_id > 0) {

                $result = $wpdb->update(
                    $table_idiomas,
                    array(
                        'idioma' => $idioma,
                        'idioma_por_defecto' => $idioma_por_defecto
                    ),
                    array('id' => $idioma_id)
                );

                $message = $result !== false ? 'Idioma actualizado correctamente.' : 'Hubo un error al actualizar el idioma.';
                $messageClass = $result !== false ? 'alert-info' : 'alert-danger';

            } else {

                $result = $wpdb->insert(
                    $table_idiomas,
                    array(
                        'idioma' => $idioma,
                        'idioma_por_defecto' => $idioma_por_defecto
                    )
                );
                
                $message = $result !== false ? 'Idioma insertado correctamente.' : 'Hubo un error al insertar el idioma.';
                $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
            }
        } else {
            // $message = 'El idioma ya existe.';
            // $messageClass = 'alert-warning';

            $result = $wpdb->update(
                $table_idiomas,
                array(
                    'idioma' => $idioma,
                    'idioma_por_defecto' => $idioma_por_defecto
                ),
                array('id' => $idioma_id)
            );

            $message = $result !== false ? 'Idioma actualizado correctamente.' : 'Hubo un error al actualizar el idioma.';
            $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
        }
    }
}

// Manejar la solicitud GET para eliminar un idioma
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idioma_id = intval($_GET['id']);
    $wpdb->delete($table_textos, array('idioma_id' => $idioma_id));

    // Eliminar el idioma
    $result = $wpdb->delete($table_idiomas, array('id' => $idioma_id));
    $message = $result !== false ? 'Idioma y textos asociados eliminados correctamente.' : 'Hubo un error al eliminar el idioma.';
    $messageClass = $result !== false ? 'alert-info' : 'alert-danger';
    $_GET['id'] = null;
}

// Obtener la lista de idiomas
$idiomas = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}insotel_motor_idiomas");

?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <?php if (!empty($message)) : ?>
            <div id="update-result" class="alert <?php echo $messageClass; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <h1 class="mb-4">CONFIGURACIÓN DE LOS IDIOMAS DEL PLUGIN</h1>
        <div class="w-100 h-100 d-flex align-items-start justify-content-between">
            <div class="card shadow-sm mb-5" style="min-width:500px;">
                <div class="card-header">
                    <?php $titulo = isset($_GET['idioma_id']) ? "Editar " : "Crear " ?>
                    <h2 class="h5 mb-0"><?php echo $titulo; ?>Idioma</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo esc_url($page_url); ?>" id="formulario_configuracion" name="formulario_configuracion">
                        <div class="mb-3">
                            <label for="idioma" class="form-label">Idioma:</label>
                            <input type="hidden" id="idioma_id" name="idioma_id" value="<?php echo isset($_GET['id']) ? intval($_GET['id']) : 0; ?>">
                            <input type="text" class="form-control" id="idioma" name="idioma" value="<?php echo isset($_GET['idioma']) ? sanitize_text_field($_GET['idioma']) : ''; ?>" required>
                            <div class="">
                                <label for="idioma_por_defecto">¿Es el idioma por defecto?</label><br>
                                <?php
                                if ($_GET['idioma_por_defecto']) {
                                ?>
                                    <input type="checkbox" class="form-control" id="idioma_por_defecto" name="idioma_por_defecto" checked>

                                <?php
                                } else {
                                ?>
                                    <input type="checkbox" class="form-control" id="idioma_por_defecto" name="idioma_por_defecto">

                                <?php
                                }
                                ?>
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

            <div class="card shadow-sm" style="min-width:500px;">
                <div class="card-header">
                    <h2 class="h5 mb-0">Lista de Idiomas</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Idioma</th>
                                <th>¿Es el por defecto?</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($idiomas as $idioma) : ?>
                                <tr>
                                    <td><?php echo $idioma->id; ?></td>
                                    <td><?php echo $idioma->idioma; ?></td>
                                    <td><?php echo $idioma->idioma_por_defecto; ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(add_query_arg(array('id' => $idioma->id, 'idioma' => $idioma->idioma, 'idioma_por_defecto' => $idioma->idioma_por_defecto), $page_url)); ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="<?php echo esc_url(add_query_arg(array('action' => 'delete', 'id' => $idioma->id), $page_url)); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este idioma y todos los textos asociados?');">Eliminar</a>
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