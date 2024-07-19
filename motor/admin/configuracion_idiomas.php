<?php
global $wpdb;
$message = '';

// Obtener el nombre de la página actual para construir URLs correctas
$page_url = admin_url('admin.php?page=motor/admin/configuracion_idiomas.php');

// Manejar la solicitud POST para agregar o actualizar un idioma
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    $idioma = sanitize_text_field($_POST['idioma']);
    $idioma_id = isset($_POST['idioma_id']) ? intval($_POST['idioma_id']) : 0;

    // Verificar si el idioma ya existe
    $table_idiomas = $wpdb->prefix . 'insotel_motor_idiomas';
    $existing_idioma = $wpdb->get_var($wpdb->prepare(
        "SELECT idioma FROM $table_idiomas WHERE idioma = %s",
        $idioma
    ));


    if ($existing_idioma === null) {
        if ($idioma_id > 0) {
            $result = $wpdb->update(
                $table_idiomas,
                array('idioma' => $idioma),
                array('id' => $idioma_id)
            );
            $message = $result !== false ? 'Idioma actualizado correctamente.' : 'Hubo un error al actualizar el idioma.';
        } else {
            $result = $wpdb->insert(
                $table_idiomas,
                array('idioma' => $idioma)
            );
            $message = $result !== false ? 'Idioma insertado correctamente.' : 'Hubo un error al insertar el idioma.';
        }
    } else {
        $message = 'El idioma ya existe.';
    }
}

// Manejar la solicitud GET para eliminar un idioma
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idioma_id = intval($_GET['id']);

    // Eliminar los textos asociados con este idioma
    $table_textos = $wpdb->prefix . 'insotel_motor_textos';
    $wpdb->delete($table_textos, array('idioma_id' => $idioma_id));

    // Eliminar el idioma
    $table_idiomas = $wpdb->prefix . 'insotel_motor_idiomas';
    $result = $wpdb->delete($table_idiomas, array('id' => $idioma_id));
    $message = $result !== false ? 'Idioma y textos asociados eliminados correctamente.' : 'Hubo un error al eliminar el idioma.';
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
            <div id="update-result" class="alert alert-info"><?php echo $message; ?></div>
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
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
                            <?php $disabled = isset($_GET['id']) ? "" : "disabled" ?>
                            <button id="boton_guardar" name="reset_table" type="submit" class="btn btn-primary mt-3" <?php echo $disabled; ?>>Volver a la creación</button>
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
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($idiomas as $idioma) : ?>
                                <tr>
                                    <td><?php echo $idioma->id; ?></td>
                                    <td><?php echo $idioma->idioma; ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(add_query_arg(array('id' => $idioma->id, 'idioma' => $idioma->idioma), $page_url)); ?>" class="btn btn-warning btn-sm">Editar</a>
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