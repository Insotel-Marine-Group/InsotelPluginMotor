<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->

<?php
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


$domainActual = $_SERVER['HTTP_HOST'];

function check_language_in_url()
{
    $current_url = $_SERVER['REQUEST_URI'];

    $languages = array('ES', 'EN', 'IT', 'FR', 'DE');

    foreach ($languages as $lang) {
        if (strpos($current_url, $lang) !== false) {
            return $lang;
        }
    }

    return false;
}
$current_language = check_language_in_url();
$idioma = "ES";

if ($current_language != false) {
    $idioma = $current_language;
}


global $wpdb;
$queryTextos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_textos WHERE idioma = '$idioma'");
$textosTraducidos = $wpdb->get_results($queryTextos, ARRAY_A)[0];

$queryConstantes = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_constantes");
$constantes = $wpdb->get_results($queryConstantes)[0];

$vehicini = '20220319';
$vehicfin = '20220930';
if (!isset($diaactivo)) {
    $diaactivo = date("d-m-Y");
}

// PARTIR PRIMER DIA ACTIVO
$diaini = substr($diaactivo, 0, 2);
$mesini = substr($diaactivo, 3, 2);
$anoini = substr($diaactivo, 6, 4);
$primerdia_es = $diaini . "/" . $mesini . "/" . $anoini;
$primerdia_en = $diaini . "/" . $mesini . "/" . $anoini; // no se modifica

?>

<script>
    $(document).ready(function(e) {

        const vehicini = '20220319';
        const vehicfin = '20220930';
        const idioma = "<?php echo $idioma ?>";
        const urlMotor = "<?php echo $constantes->url_motor ?>";
        const promocion = "<?php echo $constantes->promocion ?>";
        const tipoFormulario = "<?php echo $constantes->tipo_formulario ?>";

        const primerDiaEs = <?php echo $primerdia_es; ?>;
        const primerDiEn = <?php echo $primerdia_en; ?>;

        const dateActually = <?php echo date("d/m/Y"); ?>;


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

        let dateIdioma;

        if (idioma == "EN") {
            dateIdioma = {
                minDate: dateActually,
                locale: {
                    direction: "ltr",
                    format: "DD/MM/YYYY",
                    separator: " - ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    fromLabel: "From",
                    toLabel: "To",
                    customRangeLabel: "Custom",
                    daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                    monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                    firstDay: 0
                }
            };
        } else {
            dateIdioma = {
                minDate: dateActually,
                locale: {
                    direction: "ltr",
                    format: "DD/MM/YYYY",
                    separator: " - ",
                    applyLabel: "Aplicar",
                    cancelLabel: "Cancelar",
                    fromLabel: "De",
                    toLabel: "A",
                    customRangeLabel: "Custom",
                    daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                    monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                    firstDay: 1
                }
            };
        }

        baribi = "";
        ibibar = "";
        valibi = "";
        ibival = "";
        ibzfor = "";
        foribz = "";

        function idavuelta() {
            idavue = $('#booking-form #idavue').val();
            if (idavue == "ida") {
                return true;
            } else {
                return false;
            }
        }

        function desactivardias(fechas) {

            setTimeout(() => {
                var calendario = $('#booking-form #fecha-viaje').daterangepicker({
                    "singleDatePicker": idavuelta(),
                    isInvalidDate: function(date) {

                        if (fechas) {
                            for (i = 0; i < fechas.length; i++) {
                                if (date.format("DD-MM-YYYY") == fechas[i]) {
                                    return true;
                                }
                            }
                        } else {
                            if (date.format("DD-MM-YYYY") == "01-01-1970") {
                                return true;
                            }
                        }

                    },
                    dateGeneral,
                    dateFechas,
                    dateIdioma

                }, function(start, end, label) {

                });

                var hoy = moment(moment().format('DD-MM-YYYY'));

                for (const fecha of fechas) {
                    if (fecha != "") {
                        var fechaNoValida = moment(fecha, "DD-MM-YYYY");
                        if (fechaNoValida < hoy) {
                            continue;
                        }

                        if (fechaNoValida.startOf('day').isSame(hoy.startOf('day'))) {
                            hoy = moment(fecha, "DD-MM-YYYY").add(1, 'days');
                            continue;
                        }

                        if (fechaNoValida > hoy) {
                            break;
                        }
                    }
                }
            }, 2000);

        }

        fechas = ibzfor.split(",");
        desactivardias(fechas);

        /******** IDA Y VUELTA ***********/

        $('#booking-form #idavue').change(function() {
            $('#booking-form #fecha-viaje').daterangepicker({
                "singleDatePicker": idavuelta(),
                dateGeneral,
                dateFechas,
                dateIdioma
            });
        });

        /******** IDA ***********/
        $('#booking-form #origen').change(function() {
            ori = $('#booking-form #origen').val();
            if (ori == "ibi") { // Mallorca
                let newOptions = {
                    "Formentera": "for"
                };
                $("#booking-form").attr("action", `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`);
            } else if (ori == "for") { // Menorca
                let newOptions = {
                    "Ibiza": "ibi"
                };

                $("#booking-form").attr("action", `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`);
            } else {
                let newOptions = {
                    "Ibiza": "ibi",
                    "Formentera": "for"
                };

                $("#booking-form").attr("action", `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`);
            }

            let $el = $("#booking-form #destino");
            $el.empty(); // remove old options
            $.each(newOptions, function(key, value) {
                $el.append($("<option></option>")
                    .attr("value", value).text(key));
            });

            if ($("#booking-form #origen").val() == "bar") {
                fechas = baribi.split(",");
            } else
            if ($("#booking-form #origen").val() == "val") {
                fechas = valibi.split(",");
            } else
            if ($("#booking-form #origen").val() == "ibi") {
                fechas = ibzfor.split(",");
            } else
            if ($("#booking-form #origen").val() == "for") {
                fechas = foribz.split(",");
            } else {
                fechas = "";
            }
            desactivardias(fechas);
        });

        $('#booking-form #destino').change(function() {
            if ($("#booking-form #destino").val() == "bar") {
                fechas = ibibar.split(",");
            } else
            if ($("#booking-form #destino").val() == "val") {
                fechas = ibival.split(",");
            } else
            if ($("#booking-form #destino").val() == "ibi") {
                fechas = foribz.split(",");
            } else
            if ($("#booking-form #destino").val() == "for") {
                fechas = ibzfor.split(",");
            } else {
                fechas = "";
            }
            desactivardias(fechas);
        });

        $('#booking-form #promo').focus(function() {
            return false;
        });

        /******** VEHICULOS ***********/

        $("#booking-form #switchViajo").change(function(e) {

            $("#booking-form #divmarca").removeClass("disabled");
            $("#booking-form #marca").attr("disabled", false);

            if (!$("#booking-form #switchViajo").is(':checked')) {
                $(this).val("0");
            } else {
                $(this).val("1");
            }

            actualizarFecha();

            ori = $('#booking-form #origen').val();
            des = $('#booking-form #destino').val();
            fechaini = $('#booking-form #fechaini').val();
            fechafin = $('#booking-form #fechafin').val();

            if (idioma == "en") {
                fechaini = fechaini.substr(6, 4) + fechaini.substr(0, 2) + fechaini.substr(3, 2);
                fechafin = fechafin.substr(6, 4) + fechafin.substr(0, 2) + fechafin.substr(3, 2);
            } else {
                fechaini = fechaini.substr(6, 4) + fechaini.substr(3, 2) + fechaini.substr(0, 2);
                fechafin = fechafin.substr(6, 4) + fechafin.substr(3, 2) + fechafin.substr(0, 2);
            }

            idavue = $('#booking-form #idavue').val();

            if (idavue == "ida") {
                if (fechaini >= vehicini && fechaini <= vehicfin) permisovehi = 1;
                else permisovehi = 0;
            } else {
                if ($("#booking-form #origen").val() == "ibi" || $("#booking-form #origen").val() == "for") {
                    if (fechaini >= vehicini && fechafin <= vehicfin) permisovehi = 1;
                    else permisovehi = 0;
                } else {
                    permisovehi = 1;
                }
            }

            // if ($("#booking-form #switchViajo").is(':checked')) {
            //     $("#booking-form #capaViajo").slideDown("slow");
            // } else {
            //     $("#booking-form #capaViajo").slideUp("slow");
            // }

            document.querySelector("#capaViajo").classList.toggle("active");

        });

        $('#booking-form #vehiculo').change(function() {
            //bloquearvehiculo();
            vehiculo = $('#booking-form #vehiculo').val();
            if (vehiculo == "turismo" || vehiculo == "remolque" || vehiculo == "electrico" || vehiculo == "hibrido") {
                $("#booking-form #divmarca").removeClass("disabled");
                $("#booking-form #marca").attr("disabled", false);
            } else if (vehiculo == "otros") {
                $("#aviso-vehiculosnoinc").fadeIn(600);
            } else if (vehiculo == "furgoneta") {
                $("#aviso-furgonetas").fadeIn(600);
                $("#booking-form #divmarca").removeClass("disabled");
                $("#booking-form #marca").attr("disabled", false);
            } else {
                $("#booking-form #divmarca").addClass("disabled");
                $("#booking-form #marca").attr("disabled", true);
                $("#booking-form #divmodelo").addClass("disabled");
                $("#booking-form #modelo").attr("disabled", true);
            }

            if (vehiculo == "" || vehiculo == "bicicleta" || vehiculo == "motocicleta" || vehiculo == "ciclomotor") {
                $('#booking-form #marca').val("");
                $('#booking-form #modelo').val("");
            }
        });

        /* comprobar Marca */
        $('#booking-form #marca').change(function() {
            $("#booking-form #divmodelo").removeClass("disabled");
            $("#booking-form #modelo").attr("disabled", false);

            $("#booking-form #modelo").empty();

            marca = $('#booking-form #marca').val();
            $.ajax({
                type: "POST",
                url: "/wp-content/plugins/Plugin%20WP%20IMG/lib/modelos.php?marca=" + marca,
                success: function(response) {
                    $('#booking-form #modelo').html(response).fadeIn();
                }
            });


        });

        function bloquearvehiculo() {}

        function habilitarbuscar() {
            $("#booking-form #botonbuscar").attr("disabled", false);
        }

        function deshabilitarbuscar() {
            $("#booking-form #botonbuscar").attr("disabled", true);
        }


        $('#booking-form #botonbuscar').click(function(ev) {

            fechas = $('#booking-form #fecha-viaje').val();
            fechasarray = fechas.split("-");
            fechaini = $.trim(fechasarray[0]);
            fechafin = $.trim(fechasarray[1]);

            diaini = fechaini.substring(0, 2);
            mesini = fechaini.substring(3, 5);
            anoini = fechaini.substring(6, 10);
            diafin = fechafin.substring(0, 2);
            mesfin = fechafin.substring(3, 5);
            anofin = fechafin.substring(6, 10);

            $("#booking-form #fechaini").val(diaini + "-" + mesini + "-" + anoini);
            $("#booking-form #fechafin").val(diafin + "-" + mesfin + "-" + anofin);

            $("#booking-form").attr("action", `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`);

            if (!$("#booking-form #switchFamilia").is(':checked')) {
                $("#booking-form #familia").val("");
            }
            if ($("#booking-form #switchMascotas").is(':checked')) {
                $("#booking-form #mascotas").val("1");
            }
            if ($("#booking-form #switchViajo").is(':checked')) {
                $("#booking-form").attr("action", `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`);
            } else {
                $("#booking-form #vehiculo").val("");
            }

            $("#booking-form").submit();
        });




        function actualizarFecha() {
            fechas = $('#booking-form #fecha-viaje').val();
            fechasarray = fechas.split("-");
            fechaini = $.trim(fechasarray[0]);
            fechafin = $.trim(fechasarray[1]);

            diaini = fechaini.substring(0, 2);
            mesini = fechaini.substring(3, 5);
            anoini = fechaini.substring(6, 10);
            diafin = fechafin.substring(0, 2);
            mesfin = fechafin.substring(3, 5);
            anofin = fechafin.substring(6, 10);

            $("#booking-form #fechaini").val(diaini + "-" + mesini + "-" + anoini);
            $("#booking-form #fechafin").val(diafin + "-" + mesfin + "-" + anofin);
        }

        actualizarFecha();



        $('#booking-form #divpersonas').click(function(e) {
            $("#booking-form #divocupacion").slideDown();
            e.stopPropagation();
        });

        $("#booking-form #divocupacion").click(function(e) {
            e.stopPropagation();
        });
        $("#booking-form #aceptarocu").click(function() {
            $("#booking-form #divocupacion").slideUp();
        });

        adultos = 1;
        ninos = 0;
        seniors = 0;
        bebes = 0;
        mascotas = 0;

        function computo() {
            if (adultos == 0 && ninos == 0 && seniors == 0 && bebes == 0) {
                numpasajeros = "Sin pasajeros";
            } else {
                totalpasajeros = adultos + ninos + seniors + bebes;
                numpasajeros = totalpasajeros + " <?= $textosTraducidos["label_pasajeros"] ?>";
            }

            $("#booking-form #numpasajeros").val(numpasajeros);
        }


        /* BOTONES */

        /* adultos */
        $('#booking-form #ad_ma').click(function() {
            adultos = $("#booking-form #adultos").val();
            adultos++;
            if (adultos > 10) adultos = 10;
            $("#booking-form #adultos").val(adultos);
            computo();
        });

        $('#booking-form #ad_me').click(function() {
            adultos = $("#booking-form #adultos").val();
            adultos--;
            if (adultos < 0) adultos = 0;
            $("#booking-form #adultos").val(adultos);
            computo();
        });

        /* niños */
        $('#booking-form #ni_ma').click(function() {
            ninos = $("#booking-form #ninos").val();
            ninos++;
            if (ninos > 10) ninos = 10;
            $("#booking-form #ninos").val(ninos);
            computo();
        });

        $('#booking-form #ni_me').click(function() {
            ninos = $("#booking-form #ninos").val();
            ninos--;
            if (ninos < 0) ninos = 0;
            $("#booking-form #ninos").val(ninos);
            computo();
        });

        /* seniors */
        $('#booking-form #se_ma').click(function() {
            seniors = $("#booking-form #seniors").val();
            seniors++;
            if (seniors > 10) seniors = 10;
            $("#booking-form #seniors").val(seniors);
            computo();
        });

        $('#booking-form #se_me').click(function() {
            seniors = $("#booking-form #seniors").val();
            seniors--;
            if (seniors < 0) seniors = 0;
            $("#booking-form #seniors").val(seniors);
            computo();
        });

        /* bebés */
        $('#booking-form #be_ma').click(function() {
            bebes = $("#booking-form #bebes").val();
            bebes++;
            if (bebes > 10) bebes = 10;
            $("#booking-form #bebes").val(bebes);
            computo();
        });

        $('#booking-form be_me').click(function() {
            bebes = $("#booking-form #bebes").val();
            bebes--;
            if (bebes < 0) bebes = 0;
            $("#booking-form #bebes").val(bebes);
            computo();
        });

        /* mascotas */
        $('#booking-form #ma_ma').click(function() {
            mascotas = $("#booking-form #mascotas").val();
            mascotas++;
            if (mascotas > 2) mascotas = 2;
            $("#booking-form #mascotas").val(mascotas);
            computo();
        });

        $('#booking-form #ma_me').click(function() {
            mascotas = $("#booking-form #mascotas").val();
            mascotas--;
            if (mascotas < 0) mascotas = 0;
            $("#booking-form #mascotas").val(mascotas);
            computo();
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
    <input type="hidden" name="canalreserva" id="canalreserva" value="<?php echo $domainActual ?>">
    <input type="hidden" name="origin" id="origin" value="<?php echo $domainActual ?>">
    <input type="hidden" name="fechaini" id="fechaini">
    <input type="hidden" name="fechafin" id="fechafin">

    <div id="divreservas" class="p-10px borderRadius10px">

        <div id="reservas">

            <div id="dividavue" class="cajaCampo ml-20px">
                <select id="idavue" class="form-select form-select-solid campoMotor">
                    <option value="ida"><?= $textosTraducidos["label_solo_ida"] ?></option>
                    <option value="idavue" selected><?= $textosTraducidos["label_ida_y_vuelta"] ?></option>
                </select>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <div id="divorigen" class="cajaCampo">
                    <i class="far fa-compass icon translateX30px"></i>
                    <select name="origen" id="origen" class="campoMotor">
                        <option value="ibi">Ibiza</option>
                        <option value="for">Formentera</option>
                    </select>
                </div>

                <div id="divdestino" class="cajaCampo">
                    <i class="far fa-compass icon translateX30px"></i>
                    <select name="destino" id="destino" class="campoMotor">
                        <option value="for">Formentera</option>
                    </select>
                </div>

                <div id="divfechas" class="cajaCampo">
                    <i class="far fa-calendar-alt icon translateX30px"></i>
                    <input type="text" id="fecha-viaje" class="campoMotor" readonly>
                </div>



                <div id="divpersonas" class="cajaCampo">
                    <i class="fas fa-male icon translateX30px"></i>
                    <input id="numpasajeros" name="numpasajeros" type="text" class="campoMotor" value="1 <?= $textosTraducidos["label_pasajeros"] ?>" readonly>
                </div>

                <div id="divcodigo" class="cajaCampo">
                    <i class="far fa-sticky-note icon translateX30px"></i>
                    <input type="text" id="promo" name="codigo" class="campoMotor" autocomplete="off" placeholder="Promo" value="<?= $promocion ?>">
                </div>

                <div id="divbuscar" class="cajaCampo" style="padding: 4px;">
                    <button type="button" id="botonbuscar" class="btnFormentera"><?= $textosTraducidos["label_reservar"] ?></button>
                </div>

                <div class="salto"></div>



            </div>

            <div id="divviajo">
                <div class="form-check form-switch form-switch-md ml-20px">
                    <input class="form-check-input" type="checkbox" id="switchViajo" name="checkvehiculo" value="1">
                    <label class="form-check-label"><?= $textosTraducidos["label_anadir_vehiculo"] ?></label>
                </div>
            </div>

            <div id="capaViajo" class="d-flex algin-items-center justify-content-between">

                <div id="d_vehiculo" class="w-30">
                    <div class="ml-20px">
                        Tipo vehículo
                    </div>
                    <div id="divvehiculo" class="cajaCampoCoche">
                        <i class="fas fa-car-alt icon translateX30px"></i>
                        <select name="vehiculo" id="vehiculo" class="form-select campoMotor">
                            <option value="turismo">Turismo</option>
                            <option value="remolque">Turismo + Remolque</option>
                            <option value="furgoneta">Furgoneta</option>
                            <option value="motocicleta">Motocicleta (>125cc)</option>
                            <option value="ciclomotor">Ciclomotor</option>
                            <option value="bicicleta">Bicicleta</option>
                        </select>
                    </div>
                </div>

                <div id="d_marca" class="w-30">
                    <div class="ml-20px">
                        Marca
                    </div>
                    <div id="divmarca" class="disabled cajaCampoCoche">
                        <i class="fas fa-car-side icon translateX30px"></i>
                        <select name="marca" id="marca" class="form-select campoMotor" disabled>
                            <option value="0"></option>
                            <?php echo $optionsMarcasCoche; ?>
                        </select>
                    </div>
                </div>

                <div id="d_modelo" class="w-30">
                    <div class="ml-20px">
                        Modelo
                    </div>
                    <div id="divmodelo" class="disabled cajaCampoCoche">
                        <i class="fas fa-car-side icon translateX30px"></i>
                        <select name="modelo" id="modelo" class="form-select campoMotor" disabled>
                            <option value="0"></option>
                        </select>
                    </div>
                </div>

            </div>
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

            <div id="divfamilia" class="cajaCampoCoche">
                <i class="fas fa-users icon translateX30px"></i>
                <select name="famnum" id="familia" class="form-select campoMotor">
                    <option value="general">Reg. General</option>
                    <option value="especial">Reg. Especial</option>
                </select>
            </div>

            <div>
                <button type="button" class="mt-50px btnFormentera" id="aceptarocu">Aceptar</button>
            </div>

        </div>

    </div>
</form>


<style>
    :root {
        --azul: #1580A0;
        --rosa: #E33F65;
        --verde: #2C992A;
        --verde-oscuro: #3A5D31;
        --negro: #000000;
        --altoSticky: 140px;
    }

    .bgColor1 {
        background-color: var(--color1);
    }

    .bgColor2 {
        background-color: var(--color2);
    }

    .bgColor3 {
        background-color: var(--color3);
    }

    .bgColor4 {
        background-color: var(--color4);
    }

    .bgColor5 {
        background-color: var(--color5);
    }

    .bgWhite {
        background-color: white;
    }

    .color1 {
        color: var(--color1);
    }

    .color2 {
        color: var(--color2);
    }

    .color3 {
        color: var(--color3);
    }

    .color4 {
        color: var(--color4);
    }

    .color5 {
        color: var(--color5);
    }

    form#booking-form * {
        box-sizing: border-box !important;
    }


    #booking-form {
        box-sizing: border-box !important;
        max-width: 1200px !important;
    }

    .h-100 {
        height: 100% !important;
    }

    .w-100 {
        width: 100% !important;
    }

    .w-30 {
        width: 30%;
    }

    .d-flex {
        display: flex !important;
    }

    .flex-column {
        flex-direction: column;
    }

    .align-items-center {
        align-items: center;
    }

    .align-items-start {
        align-items: start;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .justify-content-center {
        justify-content: center !important;
    }


    .mt-10px {
        margin-top: 10px !important;
    }

    .mt-20px {
        margin-top: 20px !important;
    }


    .mr-5px {
        margin-right: 5px !important;
    }

    .text-center {
        text-align: center !important;
    }

    .bg-transparent {
        background-color: transparent !important;
    }

    #botonbuscar {
        font-size: 16px;
        font-weight: 500;
    }

    .pl-50px {
        padding-left: 50px;
    }

    .translateX30px {
        transform: translateX(+30px);
    }

    .translateX50px {
        transform: translateX(+50px);
    }

    .cajaCampo {
        background-color: transparent;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 20% !important;
    }

    .cajaCampoCoche {
        background-color: transparent;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .campoMotor {
        background-color: #efefef !important;
        border-radius: 6px !important;
        border: none !important;
        height: 40px !important;
        font-size: 13px !important;
        padding-left: 50px !important;
        width: 100% !important;
    }

    .btnFormentera {
        width: 100% !important;
        height: 100%;
        background-color: var(--azul) !important;
        border-radius: 6px !important;
        color: white !important;
        font-size: 16px !important;
        padding: 3px 5px !important;
        font-weight: 500 !important;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        -ms-transition: all 0.5s ease;
        transition: all 0.5s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btnFormentera:hover {
        background-color: #444;
        text-decoration: none;
    }

    #divreservas {
        background-color: #FFFFFF;
    }

    .ml-20px {
        margin-left: 20px;
    }

    .p-10px {
        padding: 10px;
    }

    .borderRadius10px {
        border-radius: 10px !important;
    }

    .icon {
        color: #1580A0;
    }

    #reservas #divviajo {
        width: 100%;
        float: left;
        text-align: left;
    }

    #reservas #divviajo select {
        width: 100%;
        border: 0;
        padding: 2px;
        height: 20px;
    }

    #capaViajo{
        height: 0px !important;
    }

    #capaViajo.active{
        height:fit-content;
    }

    #divocupacion {
        display: none;
        position: absolute;
        z-index: 600;
        top: 120px;
        left: 50%;
        background-color: #FFF;
        width: 300px;
        padding: 20px;
        height: auto;
        border-radius: 15px;
        -webkit-box-shadow: 0px 5px 20px 0px rgba(0, 0, 0, 0.55);
        box-shadow: 0px 5px 20px 0px rgba(0, 0, 0, 0.55);
        overflow: hidden;
    }

    #divocupacion .pasajero {
        margin-bottom: 20px;
        overflow: hidden;
        border-bottom: 1px solid #f1f2f6;
    }

    #divocupacion .pasajero .texto,
    #divocupacion .pasajero .menos,
    #divocupacion .pasajero .mas,
    #divocupacion .pasajero .dato {
        float: left;
    }

    #divocupacion .pasajero .texto {
        width: 45%;
        font-size: 14px;
        color: #444;
        line-height: 17px;
        text-align: left !important;
    }

    #divocupacion .pasajero .texto span {
        font-size: 15px;
        color: #777;
        font-weight: 500;
    }

    #divocupacion .pasajero .menos,
    #divocupacion .pasajero .mas {
        width: 20%;
        font-size: 20px;
        color: var(--azul);
        padding: 0px;
        margin: 0;
        line-height: 1px;
        cursor: pointer;
    }

    #divocupacion .pasajero .menos {
        text-align: right;
        margin: 0 !important;
    }

    #divocupacion .pasajero .mas {
        text-align: left;
        margin: 0 !important;
    }

    #divocupacion .pasajero .dato {
        width: 15%;
    }

    #divocupacion .pasajero .dato input {
        font-size: 18px;
        text-align: center;
        border: none;
        background-color: white;
    }

    #divocupacion .form-check-label {
        font-size: 14px;
        font-weight: 500;
        color: #777;
        text-align: left !important;
        float: left;
        line-height: 2;
        margin-left: 0.5em;
    }

    @media screen and (max-width: 768px) {
        #divocupacion {
            top: 120px;
            left: 10%;
        }
    }
</style>