<?php
global $wpdb;

$queryIdiomas = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_idiomas");
$arrayIdiomas = $wpdb->get_results($queryIdiomas, ARRAY_A);

$queryTextos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_textos");
$textos = $wpdb->get_results($queryTextos);


// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    // Obtener el nuevo valor enviado desde el formulario
    $idioma = sanitize_text_field($_POST['idioma']);
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


    // Actualizar la tabla
    $table_insotel_motor = $wpdb->prefix . 'insotel_motor_textos';

    // Verificar si el idioma ya existe
    $existing_idioma_texto = $wpdb->get_var($wpdb->prepare(
        "SELECT idioma FROM $table_insotel_motor WHERE idioma = %s",
        $idioma
    ));

    if ($existing_idioma_texto === null) {
        $result = $wpdb->insert(
            $table_insotel_motor,
            array(
                'idioma' => $idioma,
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
        if ($result !== false) {
            $textos = $wpdb->get_results($queryTextos);
            $message = 'Se ha insertado en la tabla correctamente.';
        } else {
            $message = 'Hubo un error al insertar la tabla.';
        }
    } else {
        $result = $wpdb->update(
            $table_insotel_motor,
            array(
                'idioma' => $idioma,
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
            array('idioma' => $idioma)
        );
        if ($result !== false) {
            $textos = $wpdb->get_results($queryTextos);
            $message = 'La tabla se ha actualizado correctamente.';
        } else {
            $message = 'Hubo un error al actualizar la tabla.';
        }
    }
}
?>


<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>


<script>
    let textosBd = <?php echo json_encode($textos); ?>;

    document.addEventListener("DOMContentLoaded", () => {
        printValues(textosBd[0]);

        document.querySelector("#idioma").addEventListener("change", () => {
            changeValues(textosBd);
        })
    });

    function changeValues(textos) {
        let interruptor = false;
        textos.forEach(texto => {
            if (texto.idioma == document.querySelector("#idioma").value) {
                interruptor = true;
                printValues(texto);
            }
        });

        if (!interruptor) {
            let textoVacio = {
                label_solo_ida: "",
                label_ida_y_vuelta: "",
                label_trayecto: "",
                label_fecha_viaje: "",
                label_pasajeros: "",
                label_codigo_promocion: "",
                label_adultos: "",
                label_ninos: "",
                label_seniors: "",
                label_bebes: "",
                label_descuentos: "",
                label_sin_descuentos: "",
                label_fn_general: "",
                label_fn_especial: "",
                label_anos: "",
                label_reservar: "",
                label_edad_adultos: "",
                label_edad_ninos: "",
                label_edad_seniors: "",
                label_edad_bebes: "",
                label_mascotas: "",
                label_anadir_vehiculo: "",
            }

            printValues(textoVacio);
        }
    }

    function printValues(values) {
        document.querySelector("#label_solo_ida").value = values.label_solo_ida;
        document.querySelector("#label_ida_y_vuelta").value = values.label_ida_y_vuelta;
        document.querySelector("#label_trayecto").value = values.label_trayecto;
        document.querySelector("#label_fecha_viaje").value = values.label_fecha_viaje;
        document.querySelector("#label_pasajeros").value = values.label_pasajeros;
        document.querySelector("#label_codigo_promocion").value = values.label_codigo_promocion;
        document.querySelector("#label_adultos").value = values.label_adultos;
        document.querySelector("#label_ninos").value = values.label_ninos;
        document.querySelector("#label_seniors").value = values.label_seniors;
        document.querySelector("#label_bebes").value = values.label_bebes;
        document.querySelector("#label_descuentos").value = values.label_descuentos;
        document.querySelector("#label_sin_descuentos").value = values.label_sin_descuentos;
        document.querySelector("#label_fn_general").value = values.label_fn_general;
        document.querySelector("#label_fn_especial").value = values.label_fn_especial;
        document.querySelector("#label_anos").value = values.label_anos;
        document.querySelector("#label_reservar").value = values.label_reservar;
        document.querySelector("#label_edad_adultos").value = values.label_edad_adultos;
        document.querySelector("#label_edad_ninos").value = values.label_edad_ninos;
        document.querySelector("#label_edad_seniors").value = values.label_edad_seniors;
        document.querySelector("#label_edad_bebes").value = values.label_edad_bebes;
        document.querySelector("#label_mascotas").value = values.label_mascotas;
        document.querySelector("#label_anadir_vehiculo").value = values.label_anadir_vehiculo;
    }
</script>


<body>
    <?php if (!empty($message)) : ?>
        <div id="update-result" onchange="changeValues()"><?php echo $message; ?></div>
    <?php endif; ?>

    <h1>CONFIGURACIÓN DE TEXTOS PARA EL PLUGIN</h1>
    <div class="container text-bg-light p-5 shadow">
        <form method="post" action="" class="pb-4" id="formulario_configuracion" name="formulario_configuracion">
            <div class="row pt-1">
                <div class="col-sm-12">
                    <label for="idioma">Idioma:</label><br>
                    <select id="idioma" name="idioma">
                        <?php
                        foreach ($arrayIdiomas as $key => $value) {
                            $idioma = $value["idioma"];
                            echo "<option value='$idioma'>$idioma</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="label_solo_ida">Label solo ida:</label><br>
                    <input type="text" class="form-control" id="label_solo_ida" name="label_solo_ida">
                </div>
                <div class="col-sm-6">
                    <label for="label_ida_y_vuelta">Label ida y vuelta:</label><br>
                    <input type="text" class="form-control" id="label_ida_y_vuelta" name="label_ida_y_vuelta">
                </div>
                <div class="col-sm-6">
                    <label for="label_trayecto">Label trayecto:</label><br>
                    <input type="text" class="form-control" id="label_trayecto" name="label_trayecto">
                </div>
                <div class="col-sm-6">
                    <label for="label_fecha_viaje">Label fech viaje:</label><br>
                    <input type="text" class="form-control" id="label_fecha_viaje" name="label_fecha_viaje">
                </div>
                <div class="col-sm-6">
                    <label for="label_pasajeros">Label pasajeros:</label><br>
                    <input type="text" class="form-control" id="label_pasajeros" name="label_pasajeros">
                </div>
                <div class="col-sm-6">
                    <label for="label_codigo_promocion">Label codigo promocion:</label><br>
                    <input type="text" class="form-control" id="label_codigo_promocion" name="label_codigo_promocion">
                </div>
                <div class="col-sm-6">
                    <label for="label_adultos">Label adultos:</label><br>
                    <input type="text" class="form-control" id="label_adultos" name="label_adultos">
                </div>
                <div class="col-sm-6">
                    <label for="label_ninos">Label ninos:</label><br>
                    <input type="text" class="form-control" id="label_ninos" name="label_ninos">
                </div>
                <div class="col-sm-6">
                    <label for="label_seniors">Label seniors:</label><br>
                    <input type="text" class="form-control" id="label_seniors" name="label_seniors">
                </div>
                <div class="col-sm-6">
                    <label for="label_bebes">Label bebes:</label><br>
                    <input type="text" class="form-control" id="label_bebes" name="label_bebes">
                </div>
                <div class="col-sm-6">
                    <label for="label_descuentos">Label descuentos:</label><br>
                    <input type="text" class="form-control" id="label_descuentos" name="label_descuentos">
                </div>
                <div class="col-sm-6">
                    <label for="label_sin_descuentos">Label sin descuentos:</label><br>
                    <input type="text" class="form-control" id="label_sin_descuentos" name="label_sin_descuentos">
                </div>
                <div class="col-sm-6">
                    <label for="label_fn_general">Label fn general:</label><br>
                    <input type="text" class="form-control" id="label_fn_general" name="label_fn_general">
                </div>
                <div class="col-sm-6">
                    <label for="label_fn_especial">Label fn especial:</label><br>
                    <input type="text" class="form-control" id="label_fn_especial" name="label_fn_especial">
                </div>
                <div class="col-sm-6">
                    <label for="label_anos">Label anos:</label><br>
                    <input type="text" class="form-control" id="label_anos" name="label_anos">
                </div>
                <div class="col-sm-6">
                    <label for="label_reservar">Label reservar:</label><br>
                    <input type="text" class="form-control" id="label_reservar" name="label_reservar">
                </div>
                <div class="col-sm-6">
                    <label for="label_edad_adultos">Label edad adultos:</label><br>
                    <input type="text" class="form-control" id="label_edad_adultos" name="label_edad_adultos">
                </div>
                <div class="col-sm-6">
                    <label for="label_edad_ninos">Label edad ninos:</label><br>
                    <input type="text" class="form-control" id="label_edad_ninos" name="label_edad_ninos">
                </div>
                <div class="col-sm-6">
                    <label for="label_edad_seniors">Label edad seniors:</label><br>
                    <input type="text" class="form-control" id="label_edad_seniors" name="label_edad_seniors">
                </div>
                <div class="col-sm-6">
                    <label for="label_edad_bebes">Label edad bebes:</label><br>
                    <input type="text" class="form-control" id="label_edad_bebes" name="label_edad_bebes">
                </div>
                <div class="col-sm-6">
                    <label for="label_mascotas">Label mascotas:</label><br>
                    <input type="text" class="form-control" id="label_mascotas" name="label_mascotas">
                </div>
                <div class="col-sm-6">
                    <label for="label_anadir_vehiculo">Label añadir vehiculo:</label><br>
                    <input type="text" class="form-control" id="label_anadir_vehiculo" name="label_anadir_vehiculo">
                </div>
            </div>
            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success float-end mt-2">Guardar Cambios</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>