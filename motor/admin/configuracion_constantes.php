<?php
global $wpdb;
$message = '';
$messageClass = '';
$queryConstantes = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_constantes");
$constantes = $wpdb->get_results($queryConstantes)[0];


// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {

    // Obtener el nuevo valor enviado desde el formulario
    $url_motor = sanitize_text_field($_POST['url_motor']);
    $promocion = sanitize_text_field($_POST['promocion']);
    $origen = sanitize_text_field($_POST['origen']);
    $canal_reserva = sanitize_text_field($_POST['canal_reserva']);


    // Actualizar la tabla
    $table_insotel_motor_constantes = $wpdb->prefix . 'insotel_motor_constantes';

    // Verificar si el idioma ya existe
    $existing_constantes = $wpdb->get_var($wpdb->prepare(
        "SELECT url_motor FROM $table_insotel_motor_constantes"
    ));


    if ($existing_constantes === null) {
        $result = $wpdb->insert(
            $table_insotel_motor_constantes,
            array(
                'url_motor' => $url_motor,
                'promocion' => $promocion,
                'canal_reserva' => $canal_reserva,
                'origen' => $origen
            )
        );

        if ($result !== false) {
            $constantes = $wpdb->get_results($queryConstantes)[0];
            $message = 'La tabla se ha insertado correctamente.';
            $messageClass = 'alert-info';
        } else {
            $message = 'Hubo un error al actualizar la tabla.';
            $messageClass = 'alert-danger';
        }
    } else {
        $result = $wpdb->update(
            $table_insotel_motor_constantes,
            array(
                'url_motor' => $url_motor,
                'promocion' => $promocion,
                'canal_reserva' => $canal_reserva,
                'origen' => $origen,
            ),
            array('id' => "1")
        );

        if ($result !== false) {
            $constantes = $wpdb->get_results($queryConstantes)[0];
            $message = 'La tabla se ha actualizado correctamente.';
            $messageClass = 'alert-info';
        } else {
            $message = 'Hubo un error al actualizar la tabla.';
            $messageClass = 'alert-danger';
        }
    }
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <?php if (!empty($message)) : ?>
            <div id="update-result" class="alert <?php echo $messageClass; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <h1>CONFIGURACIÓN CONSTANTES DEL PLUGIN</h1>
        <div class="container text-bg-light p-5 shadow mt-5">
            <form method="post" action="" class="pb-4" id="formulario_configuracion" name="formulario_configuracion">
                <div class="row pt-1">
                    <div class="col-sm-6">
                        <label for="url_motor">Url motor:</label><br>
                        <input type="text" class="form-control" id="url_motor" name="url_motor" value="<?php echo $constantes->url_motor ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="promocion">¿Promocion activa?</label><br>
                        <?php
                        if ($constantes->is_promocion) {
                        ?>
                            <input type="checkbox" class="form-control" id="is_promocion" name="is_promocion" checked>

                        <?php
                        } else {
                        ?>
                            <input type="checkbox" class="form-control" id="is_promocion" name="is_promocion">

                        <?php
                        }
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <label for="promocion">Promocion:</label><br>
                        <input type="text" class="form-control" id="promocion" name="promocion" value="<?php echo $constantes->promocion ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="origen">Origen:</label><br>
                        <input type="text" class="form-control" id="origen" name="origen" value="<?php echo $constantes->origen ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="canal_reserva">Canal reserva:</label><br>
                        <input type="text" class="form-control" id="canal_reserva" name="canal_reserva" value="<?php echo $constantes->canal_reserva ?>">
                    </div>
                </div>
                <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success float-end mt-2">Guardar Cambios</button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>