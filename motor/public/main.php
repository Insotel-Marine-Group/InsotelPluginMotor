<?php
$id_servicio = "";
$tipo_servicio = "";

if (isset($atts)) {
    $modo = $atts['modo'];
    $id_servicio = $atts['id_servicio'];
    $tipo_servicio = $atts['tipo_servicio'];
}



try {
    $serviceMarcasCoches = new SoapClient("http://api.menorcalines.com/V3/FerryWebService.asmx?WSDL");
    $respServiceCoches = $serviceMarcasCoches->GetVehicleBrands();
    $numVehiculos = count($respServiceCoches->GetVehicleBrandsResult->MarcaVehiculo);
    $optionsMarcasCoche = "";
    for ($a = 0; $a < $numVehiculos; $a++) {
        $optionsMarcasCoche .= "<option value=\"" . $respServiceCoches->GetVehicleBrandsResult->MarcaVehiculo[$a]->Code . "\">" . $respServiceCoches->GetVehicleBrandsResult->MarcaVehiculo[$a]->Name . "</option>";
    }
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}

if (!function_exists('check_language_in_url')) {
    function check_language_in_url($idiomas)
    {
        // Convertir la URL actual a mayúsculas
        $current_url = strtoupper($_SERVER['REQUEST_URI']);

        // Dividir la URL en segmentos por las barras "/"
        $url_segments = explode('/', $current_url);

        // Recorrer cada idioma proporcionado
        foreach ($idiomas as $lang) {

            // Recorrer cada segmento de la URL
            foreach ($url_segments as $segment) {
                // Comparar si el segmento contiene el idioma
                if (strpos($segment, $lang["idioma"]) !== false) {
                    return $lang["idioma"];
                }
            }
        }

        return false;
    }
}

$marcaSeleccioanda;
$optionsModelosCoche;

if (!function_exists('rellenarOptionsModelo')) {
    function rellenarOptionsModelo($marcaSeleccioanda, $optionsModelosCoche)
    {
        try {
            if ($marcaSeleccioanda == "") {
                $marcaSeleccioanda = "1";
            }

            if ($marcaSeleccioanda != "") {
                $serviceModeloCoche = new SoapClient("https://api.menorcalines.com/v3/FerryWebService.asmx?WSDL");
                $param = array('BrandID' => $marcaSeleccioanda);
                $respServiceModeloCoche = $serviceModeloCoche->GetVehiclesByBrand($param);

                if ($respServiceModeloCoche->GetVehiclesByBrandResult->ModeloVehiculo->Code) {
                    $optionsModelosCoche .= "<option value=\"" . $respServiceModeloCoche->GetVehiclesByBrandResult->ModeloVehiculo->Code . "\">" . $respServiceModeloCoche->GetVehiclesByBrandResult->ModeloVehiculo->Name . "</option>";
                } else {
                    $cuantos = count($respServiceModeloCoche->GetVehiclesByBrandResult->ModeloVehiculo);
                    $optionsModelosCoche = "";

                    for ($a = 0; $a < $cuantos; $a++) {
                        $optionsModelosCoche .= "<option value=\"" . $respServiceModeloCoche->GetVehiclesByBrandResult->ModeloVehiculo[$a]->Code . "\">" . $respServiceModeloCoche->GetVehiclesByBrandResult->ModeloVehiculo[$a]->Name . "</option>";
                    }
                }

                return $optionsModelosCoche;
            }
        } catch (\Throwable $th) {
            var_dump($th);
            echo "</br>";
        }
    }
}


$optionsModelosCoche = rellenarOptionsModelo("", $optionsModelosCoche);

global $wpdb;
$queryIdiomas = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_idiomas");
$idiomas = $wpdb->get_results($queryIdiomas, ARRAY_A);

$current_language = check_language_in_url($idiomas);
$idioma = "ES";

if ($current_language != false) {
    $idioma = $current_language;
}


$queryTextos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_textos WHERE idioma = '$idioma'");
$textosTraducidos = $wpdb->get_results($queryTextos, ARRAY_A)[0];

$queryConstantes = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_constantes");
$constantes = $wpdb->get_results($queryConstantes)[0];

if (!isset($diaactivo)) {
    $diaactivo = date("d-m-Y");
}

if ($modo === "modo_experiencias") {
    $constantes->canal_reserva = "experiencias_formentera_lines";
}

// PARTIR PRIMER DIA ACTIVO
$diaini = substr($diaactivo, 0, 2);
$mesini = substr($diaactivo, 3, 2);
$anoini = substr($diaactivo, 6, 4);
$primerdia_es = $diaini . "/" . $mesini . "/" . $anoini;
$primerdia_en = $diaini . "/" . $mesini . "/" . $anoini; // no se modifica

?>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const idioma = "<?php echo $idioma ?>";
        const urlMotor = "<?php echo $constantes->url_motor ?>";
        const promocion = "<?php echo $constantes->promocion ?>";
        const tipoFormulario = "<?php echo $constantes->tipo_formulario ?>";
        const primerDiaEs = "<?php echo $primerdia_es; ?>";
        const primerDiEn = "<?php echo $primerdia_en; ?>";
        const dateActually = "<?php echo date("d/m/Y"); ?>";
        const label_pasajeros = "<?php echo $textosTraducidos["label_pasajeros"] ?>";
        const dateGeneral = {
            linkedCalendars: true,
            autoUpdateInput: true,
            autoApply: true,
            drops: "auto",
        };
        const dateFechas = {
            startDate: primerDiEn,
            endDate: primerDiEn
        };

        let dateIdioma = paramsByDatepicker(idioma, dateActually);
        loadDatePicker(dateGeneral, dateFechas, dateIdioma);
        loadEventListeners(urlMotor, idioma, dateGeneral, dateFechas, dateIdioma);
        updateDate();

        let adultos = 1;
        let ninos = 0;
        let seniors = 0;
        let bebes = 0;
        let mascotas = 0;


        /* adultos */
        $('#booking-form #ad_ma').click(function() {
            adultos = $("#booking-form #adultos").val();
            adultos++;
            if (adultos > 10) adultos = 10;
            $("#booking-form #adultos").val(adultos);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        $('#booking-form #ad_me').click(function() {
            adultos = $("#booking-form #adultos").val();
            adultos--;
            if (adultos < 0) adultos = 0;
            $("#booking-form #adultos").val(adultos);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        /* niños */
        $('#booking-form #ni_ma').click(function() {
            ninos = $("#booking-form #ninos").val();
            ninos++;
            if (ninos > 10) ninos = 10;
            $("#booking-form #ninos").val(ninos);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        $('#booking-form #ni_me').click(function() {
            ninos = $("#booking-form #ninos").val();
            ninos--;
            if (ninos < 0) ninos = 0;
            $("#booking-form #ninos").val(ninos);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        /* seniors */
        $('#booking-form #se_ma').click(function() {
            seniors = $("#booking-form #seniors").val();
            seniors++;
            if (seniors > 10) seniors = 10;
            $("#booking-form #seniors").val(seniors);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        $('#booking-form #se_me').click(function() {
            seniors = $("#booking-form #seniors").val();
            seniors--;
            if (seniors < 0) seniors = 0;
            $("#booking-form #seniors").val(seniors);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        /* bebés */
        $('#booking-form #be_ma').click(function() {
            bebes = $("#booking-form #bebes").val();
            bebes++;
            if (bebes > 10) bebes = 10;
            $("#booking-form #bebes").val(bebes);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        $('#booking-form be_me').click(function() {
            bebes = $("#booking-form #bebes").val();
            bebes--;
            if (bebes < 0) bebes = 0;
            $("#booking-form #bebes").val(bebes);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        /* mascotas */
        $('#booking-form #ma_ma').click(function() {
            mascotas = $("#booking-form #mascotas").val();
            mascotas++;
            if (mascotas > 2) mascotas = 2;
            $("#booking-form #mascotas").val(mascotas);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        $('#booking-form #ma_me').click(function() {
            mascotas = $("#booking-form #mascotas").val();
            mascotas--;
            if (mascotas < 0) mascotas = 0;
            $("#booking-form #mascotas").val(mascotas);
            calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
        });

        $("#booking-form #switchFamilia").change(function(e) {
            if ($("#booking-form #switchFamilia").is(':checked')) {
                $("#booking-form #divfamilia").slideDown("slow");
            } else {
                $("#booking-form #divfamilia").slideUp("slow");
            }
        });
    });
</script>




<form id="booking-form" name="booking-form" target="_blank" action="#" method="POST" form-type="<?php echo $tipoFormulario; ?>">
    <input type="hidden" name="canalreserva" id="canalreserva" value="<?php echo $constantes->canal_reserva; ?>">
    <input type="hidden" name="origin" id="origin" value="<?php echo $constantes->origen; ?>">

    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio; ?>">
    <input type="hidden" name="tipo_servicio" id="tipo_servicio" value="<?php echo $tipo_servicio; ?>">

    <div id="divreservas">

        <div id="reservas">
            <div>
                <div id="dividavue" class="caja"><select id="idavue" class="form-select form-select-solid">
                        <option value="ida"><?= $textosTraducidos["label_solo_ida"] ?></option>
                        <option value="idavue" selected><?= $textosTraducidos["label_ida_y_vuelta"] ?></option>
                    </select></div>

                <div class="salto"></div>

                <div id="divorigen" class="caja">
                    <i class="far fa-compass icon"></i>
                    <select name="origen" id="origen" class="form-select">
                        <option value="ibi">Ibiza</option>
                        <option value="for">Formentera</option>
                    </select>
                </div>
                <div id="divdestino" class="caja">
                    <i class="far fa-compass icon"></i>
                    <select name="destino" id="destino" class="form-select">
                        <option value="for">Formentera</option>
                    </select>
                </div>


                <div id="divfechas" class="caja"><i class="far fa-calendar-alt icon"></i><input type="text" id="fecha-viaje" class="form-control campo" readonly></div>
                <input type="hidden" name="fechaini" id="fechaini">
                <input type="hidden" name="fechafin" id="fechafin">

                <div id="divpersonas" class="caja"><i class="fas fa-male icon"></i><input style="width: 100%;" id="numpasajeros" name="numpasajeros" type="text" class="campo" value="1 <?= $textosTraducidos["label_pasajeros"] ?>" readonly></div>


                <?php
                if ($constantes->is_promocion) {
                ?>
                    <div id="divcodigo" class="caja"><i class="far fa-sticky-note icon"></i><input style="width: 100%;" type="text" id="promo" name="codigo" class="campo" autocomplete="off" placeholder="Promo" value="<?= $constantes->promocion;  ?>"></div>
                <?php
                }
                ?>

                <div id="divbuscar" class="caja"><button type="button" id="botonbuscar"><?= $textosTraducidos["label_reservar"] ?></button></div>

            </div>

            <div class="salto"></div>
            <?php
            if ($modo == "normal") {
            ?>
                <div id="divviajo">

                    <div class="form-check form-switch form-switch-md">
                        <input class="form-check-input" type="checkbox" id="switchViajo" name="checkvehiculo" value="1">
                        <label class="form-check-label"><?= $textosTraducidos["label_anadir_vehiculo"] ?></label>
                    </div>
                </div>


                <div id="capaViajo">

                    <div id="d_vehiculo" class="caja">
                        Tipo vehículo<br />
                        <div id="divvehiculo">
                            <i class="fas fa-car-alt icon"></i>
                            <select name="vehiculo" id="vehiculo" class="form-select">
                                <option value="turismo">Turismo</option>
                                <option value="remolque">Turismo + Remolque</option>
                                <option value="furgoneta">Furgoneta</option>
                                <option value="motocicleta">Motocicleta (>125cc)</option>
                                <option value="ciclomotor">Ciclomotor</option>
                                <option value="bicicleta">Bicicleta</option>
                            </select>
                        </div>
                    </div>

                    <div id="d_marca" class="caja">
                        Marca<br />
                        <div id="divmarca" class="disabled">
                            <i class="fas fa-car-side icon"></i>
                            <select name="marca" id="marca" class="form-select" disabled>
                                <option value="0"></option>
                                <?php echo $optionsMarcasCoche; ?>
                            </select>
                        </div>
                    </div>

                    <div id="d_modelo" class="caja">
                        Modelo<br />
                        <div id="divmodelo" class="disabled">
                            <i class="fas fa-car-side icon"></i>
                            <select name="modelo" id="modelo" class="form-select" disabled>
                                <option value="0"></option>
                            </select>
                        </div>
                    </div>

                </div>

            <?php
            }
            ?>

        </div>

        <div id="divocupacion">

            <div class="pasajero">
                <div class="texto"><?= $textosTraducidos["label_adultos"] ?><br><span><?= $textosTraducidos["label_edad_adultos"] ?></span></div>
                <div class="menos" id="ad_me">
                    <svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M9 15h14a1 1 0 0 1 0 2H9a1 1 0 0 1 0-2z"></path>
                        </g>
                    </svg>
                </div>
                <div class="dato"><input type="type" id="adultos" name="adultos" value="1" class="form-control form-control-sm" readonly></div>
                <div class="mas" id="ad_ma">
                    <svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M16 8a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6.001L17 23a1 1 0 0 1-2 0l-.001-6H9a1 1 0 0 1 0-2h6V9a1 1 0 0 1 1-1z"></path>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="salto"></div>

            <div class="pasajero">
                <div class="texto"><?= $textosTraducidos["label_ninos"] ?><br><span><?= $textosTraducidos["label_edad_ninos"] ?></span></div>
                <div class="menos" id="ni_me"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M9 15h14a1 1 0 0 1 0 2H9a1 1 0 0 1 0-2z"></path>
                        </g>
                    </svg></div>
                <div class="dato"><input type="type" id="ninos" name="ninos" value="0" class="form-control form-control-sm" readonly></div>
                <div class="mas" id="ni_ma"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M16 8a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6.001L17 23a1 1 0 0 1-2 0l-.001-6H9a1 1 0 0 1 0-2h6V9a1 1 0 0 1 1-1z"></path>
                        </g>
                    </svg></div>
            </div>

            <div class="salto"></div>

            <div class="pasajero">
                <div class="texto"><?= $textosTraducidos["label_seniors"] ?><br><span><?= $textosTraducidos["label_edad_seniors"] ?></span></div>
                <div class="menos" id="se_me"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M9 15h14a1 1 0 0 1 0 2H9a1 1 0 0 1 0-2z"></path>
                        </g>
                    </svg></div>
                <div class="dato"><input type="type" id="seniors" name="seniors" value="0" class="form-control form-control-sm" readonly></div>
                <div class="mas" id="se_ma"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M16 8a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6.001L17 23a1 1 0 0 1-2 0l-.001-6H9a1 1 0 0 1 0-2h6V9a1 1 0 0 1 1-1z"></path>
                        </g>
                    </svg></div>
            </div>

            <div class="salto"></div>

            <div class="pasajero">
                <div class="texto"><?= $textosTraducidos["label_bebes"] ?><br><span><?= $textosTraducidos["label_edad_bebes"] ?></span></div>
                <div class="menos" id="be_me"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M9 15h14a1 1 0 0 1 0 2H9a1 1 0 0 1 0-2z"></path>
                        </g>
                    </svg></div>
                <div class="dato"><input type="type" id="bebes" name="bebes" value="0" class="form-control form-control-sm" readonly></div>
                <div class="mas" id="be_ma"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M16 8a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6.001L17 23a1 1 0 0 1-2 0l-.001-6H9a1 1 0 0 1 0-2h6V9a1 1 0 0 1 1-1z"></path>
                        </g>
                    </svg></div>
            </div>


            <div class="salto"></div>


            <div class="pasajero">
                <div class="texto"><?= $textosTraducidos["label_mascotas"] ?></div>
                <div class="menos" id="ma_me"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M9 15h14a1 1 0 0 1 0 2H9a1 1 0 0 1 0-2z"></path>
                        </g>
                    </svg></div>
                <div class="dato"><input type="type" id="mascotas" name="mascotas" value="0" class="form-control form-control-sm" readonly></div>
                <div class="mas" id="ma_ma"><svg viewBox="-8 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g fill="currentColor">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16zm0-2C8.268 30 2 23.732 2 16S8.268 2 16 2s14 6.268 14 14-6.268 14-14 14z"></path>
                            <path d="M16 8a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6.001L17 23a1 1 0 0 1-2 0l-.001-6H9a1 1 0 0 1 0-2h6V9a1 1 0 0 1 1-1z"></path>
                        </g>
                    </svg></div>
            </div>


            <div class="salto"></div>


            <div class="form-check form-switch form-switch-md">
                <input class="form-check-input" type="checkbox" id="switchFamilia" name="checkfamilia">
                <label class="form-check-label" for="switchFamilia">Familia Numerosa</label>
            </div>

            <div class="salto"></div>

            <div id="divfamilia" class="caja">
                <i class="fas fa-users icon"></i>
                <select name="famnum" id="familia" class="form-select">
                    <option value="general">Reg. General</option>
                    <option value="especial">Reg. Especial</option>
                </select>
            </div>

            <button type="button" id="aceptarocu">Aceptar</button>

        </div>

    </div>
</form>