<?php
$id_servicio = "";
$tipo_servicio = "";
$tipo_calendario = "";
$opciones_ida_vuelta = "seleccionable";
$tipo_pasajero = "todos";

if (isset($atts)) {
    $modo = $atts['modo'];
    $id_servicio = isset($atts['id_servicio']) ? $atts['id_servicio'] : 0;
    $tipo_servicio = isset($atts['tipo_servicio']) ? $atts['tipo_servicio'] : "";
    $tipo_calendario = isset($atts['tipo_calendario']) ? $atts['tipo_calendario'] : "";
    $opciones_ida_vuelta = isset($atts['opciones_ida_vuelta']) ? $atts['opciones_ida_vuelta'] : "seleccionable";
    $tipo_pasajero = isset($atts['tipo_pasajero']) ? $atts['tipo_pasajero'] : "todos";
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
            //var_dump($th);
            //echo "</br>";
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

$idIdioma = 1;

foreach ($idiomas as $idiomaComprove) {
    if ($idiomaComprove->idioma == $idioma) {
        $idIdioma = $idiomaComprove->id;
    }
}


$queryTextos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_textos WHERE idioma_id = '$idIdioma'");
$textosTraducidos = $wpdb->get_results($queryTextos, ARRAY_A)[0];

$queryConstantes = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_constantes");
$constantes = $wpdb->get_results($queryConstantes)[0];

$queryRutas = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_rutas");
$rutas = $wpdb->get_results($queryRutas);

$queryPuertos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_motor_puertos");
$puertos = $wpdb->get_results($queryPuertos);


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
    let isCargado = false;
    document.addEventListener("DOMContentLoaded", () => {
        if (!isCargado) {
            isCargado = true;
            console.log("No cargado");
        } else {
            console.log("Cargado");
        }

        const idioma = "<?php echo $idioma ?>";
        const urlMotor = "<?php echo $constantes->url_motor ?>";
        const promocion = "<?php echo $constantes->promocion ?>";
        const primerDiaEs = "<?php echo $primerdia_es; ?>";
        const primerDiEn = "<?php echo $primerdia_en; ?>";
        const dateActually = "<?php echo date("d/m/Y"); ?>";
        const label_pasajeros = "<?php echo $textosTraducidos["label_pasajeros"] ?>";
        const tipo_calendario = "<?php echo $tipo_calendario; ?>";

        const rutas = <?php echo json_encode($rutas) ?>;
        const puertos = <?php echo json_encode($puertos) ?>;

        const opciones_ida_vuelta = "<?php echo $opciones_ida_vuelta ?>";
        const tipo_pasajero = "<?php echo $tipo_pasajero ?>";

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


        setRutasValueInSelect("origen", "destino", puertos, rutas);

        let dateIdioma = paramsByDatepicker(idioma, dateActually);
        loadDatePicker(dateGeneral, dateFechas, dateIdioma, tipo_calendario);
        loadEventListeners(urlMotor, idioma, opciones_ida_vuelta);
        updateDate();

        let adultos = 1;
        let ninos = 0;
        let seniors = 0;
        let bebes = 0;
        let mascotas = 0;



        /*Cuando cambiar entre ida o ida y vuelta*/
        $("#booking-form #idavue").change(function() {
            loadDatePicker(dateGeneral, dateFechas, dateIdioma, tipo_calendario);
        });

        /*Cuando clicka en el boton de aceptar en el dropdown de pasajeros para que se cierre*/
        $("#booking-form #aceptarocu").click(function() {
            $("#booking-form #divocupacion").slideUp();
        });

        $("#booking-form #divocupacion").click(function(e) {
            e.stopPropagation();
        });


        /*Cuando clicka en el div de los pasajeros para que se despliegue*/
        $("#booking-form #divpersonas").click(function(e) {
            $("#booking-form #divocupacion").slideDown();
            e.stopPropagation();
        });

        /*Cuando hace focus en el codigo promocional*/
        $("#booking-form #promo").focus(function() {
            return false;
        });

        /*Cuando cambia el origen*/
        $("#booking-form #origen").change(function() {
            const valorOrigen = document.querySelector("#origen").value;
            const puertoId = puertos.find(p => p.valor == valorOrigen).id;
            const optionsDestino = convertirRutasAObjeto(obtenerRutasCoincidentes(puertoId, rutas), puertos);
            const selectDestino = document.querySelector("#destino");
            selectDestino.innerHTML = "";


            Object.entries(optionsDestino).forEach(([key, value]) => {
                const option = document.createElement("option");
                option.value = value;
                option.textContent = key;
                selectDestino.appendChild(option);
            });

            loadDatePicker(dateGeneral, dateFechas, dateIdioma, tipo_calendario);
        });

        if (document.querySelector("#booking-form #vehiculo") != undefined) {
            document
                .querySelector("#booking-form #vehiculo")
                .addEventListener("change", function() {
                    const divMarca = document.querySelector("#booking-form #divmarca");
                    const marcaInput = document.querySelector("#booking-form #marca");
                    const divModelo = document.querySelector("#booking-form #divmodelo");
                    const modeloInput = document.querySelector("#booking-form #modelo");


                    const vehiculo = document
                        .querySelector("#booking-form #vehiculo").value;

                    if (
                        vehiculo === "turismo" ||
                        vehiculo === "remolque" ||
                        vehiculo === "electrico" ||
                        vehiculo === "hibrido"
                    ) {
                        divMarca.classList.remove("disabled");
                        marcaInput.disabled = false;
                    } else if (vehiculo === "otros") {
                        divMarca.classList.add("disabled");
                        marcaInput.disabled = true;
                        divModelo.classList.add("disabled");
                        modeloInput.disabled = true;
                    } else if (vehiculo === "furgoneta") {
                        divMarca.classList.remove("disabled");
                        marcaInput.disabled = false;
                    } else {
                        divMarca.classList.add("disabled");
                        marcaInput.disabled = true;
                        divModelo.classList.add("disabled");
                        modeloInput.disabled = true;
                    }

                    if (
                        vehiculo === "" ||
                        vehiculo === "bicicleta" ||
                        vehiculo === "motocicleta" ||
                        vehiculo === "ciclomotor"
                    ) {
                        marcaInput.value = "";
                        modeloInput.value = "";
                    }
                });
        }


        /*Cuando cambia el destino*/
        $("#booking-form #destino").change(function() {
            loadDatePicker(dateGeneral, dateFechas, dateIdioma, tipo_calendario);
        });

        /*LISTENERS PASAEJROS */
        if (document.querySelector('#booking-form #ad_ma') != undefined) {
            $('#booking-form #ad_ma').click(function() {
                adultos = $("#booking-form #adultos").val();
                adultos++;
                if (adultos > 10) adultos = 10;
                $("#booking-form #adultos").val(adultos);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #ad_me') != undefined) {
            $('#booking-form #ad_me').click(function() {
                adultos = $("#booking-form #adultos").val();
                adultos--;
                if (adultos < 0) adultos = 0;
                $("#booking-form #adultos").val(adultos);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #ni_ma') != undefined) {
            document.querySelector('#booking-form #ni_ma').addEventListener("click", () => {
                ninos = $("#booking-form #ninos").val();
                console.log(ninos);
                ninos++;
                console.log(ninos);
                if (ninos > 10) ninos = 10;
                $("#booking-form #ninos").val(ninos);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            })
        }

        if (document.querySelector('#booking-form #ni_me') != undefined) {
            $('#booking-form #ni_me').click(function() {
                ninos = $("#booking-form #ninos").val();
                console.log(ninos);
                ninos--;
                console.log(ninos);
                if (ninos < 0) ninos = 0;
                $("#booking-form #ninos").val(ninos);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #se_ma') != undefined) {
            $('#booking-form #se_ma').click(function() {
                seniors = $("#booking-form #seniors").val();
                seniors++;
                if (seniors > 10) seniors = 10;
                $("#booking-form #seniors").val(seniors);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #se_me') != undefined) {
            $('#booking-form #se_me').click(function() {
                seniors = $("#booking-form #seniors").val();
                seniors--;
                if (seniors < 0) seniors = 0;
                $("#booking-form #seniors").val(seniors);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #be_ma') != undefined) {
            $('#booking-form #be_ma').click(function() {
                bebes = $("#booking-form #bebes").val();
                bebes++;
                if (bebes > 10) bebes = 10;
                $("#booking-form #bebes").val(bebes);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form be_me') != undefined) {
            $('#booking-form be_me').click(function() {
                bebes = $("#booking-form #bebes").val();
                bebes--;
                if (bebes < 0) bebes = 0;
                $("#booking-form #bebes").val(bebes);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #ma_ma') != undefined) {
            $('#booking-form #ma_ma').click(function() {
                mascotas = $("#booking-form #mascotas").val();
                mascotas++;
                if (mascotas > 2) mascotas = 2;
                $("#booking-form #mascotas").val(mascotas);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector('#booking-form #ma_me') != undefined) {
            $('#booking-form #ma_me').click(function() {
                mascotas = $("#booking-form #mascotas").val();
                mascotas--;
                if (mascotas < 0) mascotas = 0;
                $("#booking-form #mascotas").val(mascotas);
                calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros);
            });
        }

        if (document.querySelector("#booking-form #switchFamilia") != undefined) {
            $("#booking-form #switchFamilia").change(function(e) {
                if ($("#booking-form #switchFamilia").is(':checked')) {
                    $("#booking-form #divfamilia").slideDown("slow");
                } else {
                    $("#booking-form #divfamilia").slideUp("slow");
                }
            });
        }

        if (document.querySelector("#booking-form #marca") != undefined) {
            document
                .querySelector("#booking-form #marca")
                .addEventListener("change", function() {
                    const marcaSelect = document.querySelector("#booking-form #marca");
                    const divModelo = document.querySelector("#booking-form #divmodelo");
                    const modeloSelect = document.querySelector("#booking-form #modelo");

                    // Habilita el contenedor y el select de modelo
                    divModelo.classList.remove("disabled");
                    modeloSelect.disabled = false;

                    // Limpia el contenido del select de modelo
                    modeloSelect.innerHTML = "";

                    // Obtén el valor seleccionado en el select de marca
                    const marca = marcaSelect.value;
                    // Construye la URL para la solicitud nuevo-test/wordpress-6.2.1-es_ES
                    const url = `/wp-content/plugins/motor/public/modelos.php?marca=${marca}`;

                    // Realiza una solicitud POST usando fetch
                    fetch(url, {
                            method: "POST",
                        })
                        .then((response) => response.text()) // Convierte la respuesta a texto
                        .then((data) => {
                            // Actualiza el select de modelo con la respuesta y muestra el select
                            modeloSelect.innerHTML = data;
                            modeloSelect.style.display = "block";
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                        });
                });
        }


        // Selecciona el botón de buscar
        document
            .querySelector("#booking-form #botonbuscar")
            .addEventListener("click", function(ev) {
                console.log("Has clickado el boton de buscar reserva");

                const fechas = document.querySelector("#booking-form #fecha-viaje").value;
                if (fechas.includes("-")) {
                    const fechasArray = fechas.split("-");
                    const fechaini = fechasArray[0].trim();
                    const fechafin = fechasArray[1].trim();

                    // Extrae el día, mes y año de las fechas
                    const diaini = fechaini.substring(0, 2);
                    const mesini = fechaini.substring(3, 5);
                    const anoini = fechaini.substring(6, 10);
                    const diafin = fechafin.substring(0, 2);
                    const mesfin = fechafin.substring(3, 5);
                    const anofin = fechafin.substring(6, 10);

                    // Asigna las fechas formateadas a los campos de entrada
                    document.querySelector(
                        "#booking-form #fechaini"
                    ).value = `${diaini}-${mesini}-${anoini}`;

                    if (opciones_ida_vuelta != "ida") {
                        document.querySelector(
                            "#booking-form #fechafin"
                        ).value = `${diafin}-${mesfin}-${anofin}`;
                    }
                } else {
                    const diaini = fechas.substring(0, 2);
                    const mesini = fechas.substring(3, 5);
                    const anoini = fechas.substring(6, 10);

                    document.querySelector(
                        "#booking-form #fechaini"
                    ).value = `${diaini}-${mesini}-${anoini}`;

                    if (opciones_ida_vuelta != "ida") {
                        document.querySelector(
                            "#booking-form #fechafin"
                        ).value = `${diaini}-${mesini}-${anoini}`;
                    }
                }

                // Establece la acción del formulario
                document.querySelector(
                    "#booking-form"
                ).action = `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`;

                // Verifica el estado de los switches y actualiza los valores del formulario
                if (document.querySelector("#booking-form #switchFamilia") != undefined) {
                    if (!document.querySelector("#booking-form #switchFamilia").checked) {
                        document.querySelector("#booking-form #familia").value = "";
                    }
                }

                if (document.querySelector("#booking-form #switchViajo") != undefined) {
                    if (document.querySelector("#booking-form #switchViajo").checked) {
                        document.querySelector(
                            "#booking-form"
                        ).action = `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`;
                    } else {
                        document.querySelector("#booking-form #vehiculo").value = "";
                    }
                }

                // Envía el formulario
                if (getTotalPasajeros() > 0) {
                    document.querySelector("#booking-form").submit();
                }
            });
    });
</script>


<form id="booking-form" name="booking-form" target="_blank" action="#" method="POST" form-type="POST">
    <input type="hidden" name="canalreserva" id="canalreserva" value="<?php echo $constantes->canal_reserva; ?>">
    <input type="hidden" name="origin" id="origin" value="<?php echo $constantes->origen; ?>">

    <input type="hidden" name="id_servicio" id="id_servicio" value="<?php echo $id_servicio; ?>">
    <input type="hidden" name="tipo_servicio" id="tipo_servicio" value="<?php echo $tipo_servicio; ?>">

    <div id="divreservas">

        <div id="reservas">
            <div>
                <?php
                if ($opciones_ida_vuelta == "seleccionable") {
                ?>
                    <div id="dividavue" class="caja"><select id="idavue" class="form-select form-select-solid">
                            <option value="ida"><?= $textosTraducidos["label_solo_ida"] ?></option>
                            <option value="idavue" selected><?= $textosTraducidos["label_ida_y_vuelta"] ?></option>
                        </select></div>

                    <div class="salto"></div>

                <?php
                }

                ?>

                <div id="divorigen" class="caja">
                    <i class="far fa-compass icon"></i>
                    <select name="origen" id="origen" class="form-select">
                    </select>
                </div>
                <div id="divdestino" class="caja">
                    <i class="far fa-compass icon"></i>
                    <select name="destino" id="destino" class="form-select">
                    </select>
                </div>


                <div id="divfechas" class="caja"><i class="far fa-calendar-alt icon"></i><input type="text" id="fecha-viaje" class="form-control campo" readonly></div>
                <input type="hidden" name="fechaini" id="fechaini">

                <?php
                if ($opciones_ida_vuelta != "ida") {
                ?>
                    <input type="hidden" name="fechafin" id="fechafin">

                <?php
                }
                ?>

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

            <?php
            if ($tipo_pasajero != "adultos") {
            ?>
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
            <?php
            }
            ?>


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

            <?php

            if ($tipo_pasajero != "adulto") {
            ?>
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
            <?php
            }

            ?>



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