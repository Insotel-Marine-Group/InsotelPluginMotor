<?php
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class Insotel_Motor_Functions
{
    public function getArrayDays($days)
    {
        if ($days != "") {
            if (!empty($days)) {
                $days = explode(', ', $days);
            }
        }

        return $days;
    }

    public function transformDayWeekInIndex($days)
    {
        $arrayIndexDays = [];
        foreach ($days as $day) {

            switch (strtolower($day)) {
                case 'lun':
                case 'lunes':
                case 'mon':
                case 'monday':
                    array_push($arrayIndexDays, 1);
                    break;
                case 'mar':
                case 'martes':
                case 'tue':
                case 'tuesday':
                    array_push($arrayIndexDays, 2);
                    break;
                case 'mie':
                case 'miercoles':
                case 'wed':
                case 'wednesday':
                    array_push($arrayIndexDays, 3);
                    break;
                case 'jue':
                case 'jueves':
                case 'thu':
                case 'thursday':
                    array_push($arrayIndexDays, 4);
                    break;
                case 'vie':
                case 'viernes':
                case 'fri':
                case 'friday':
                    array_push($arrayIndexDays, 5);
                    break;
                case 'sab':
                case 'sabado':
                case 'sat':
                case 'saturday':
                    array_push($arrayIndexDays, 6);
                    break;
                case 'dom':
                case 'domingo':
                case 'sun':
                case 'sunday':
                    array_push($arrayIndexDays, 0);
                    break;

                default:
                    # code...
                    break;
            }
        }

        return $arrayIndexDays;
    }


    public function getOptionsMarca()
    {
        $optionsMarcasCoche = "";

        try {

            $serviceMarcasCoches = new SoapClient("http://api.menorcalines.com/V3/FerryWebService.asmx?WSDL");
            $respServiceCoches = $serviceMarcasCoches->GetVehicleBrands();
            $numVehiculos = count($respServiceCoches->GetVehicleBrandsResult->MarcaVehiculo);

            for ($a = 0; $a < $numVehiculos; $a++) {
                $optionsMarcasCoche .= "<option value=\"" . $respServiceCoches->GetVehicleBrandsResult->MarcaVehiculo[$a]->Code . "\">" . $respServiceCoches->GetVehicleBrandsResult->MarcaVehiculo[$a]->Name . "</option>";
            }
        } catch (Exception $e) {

            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return $optionsMarcasCoche;
    }

    public function check_language_in_url($idiomas)
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

                if (strtolower($segment) == strtolower($lang["idioma"])) {
                    return $lang["idioma"];
                }
            }
        }
    }

    public function rellenarOptionsModelo($marcaSeleccioanda)
    {
        $optionsModelosCoche = "";
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
            return $optionsModelosCoche;
        }
    }

    public function cambiarPuertoInicial($id_puerto_inicial, $puertos)
    {
        try {
            if ($id_puerto_inicial != "" && $id_puerto_inicial != 0) {
                if (is_numeric($id_puerto_inicial) && ctype_digit((string) $id_puerto_inicial)) {
                    // Es un número entero
                    // Buscar el puerto con el id_puerto_inicial en la lista
                    $puertoInicial = null;
                    foreach ($puertos as $index => $puerto) {
                        if ($puerto->id == $id_puerto_inicial) {
                            $puertoInicial = $puerto;
                            unset($puertos[$index]); // Eliminar el puerto inicial del array original
                            break;
                        }
                    }

                    if ($puertoInicial) {
                        // Insertar el puerto inicial al principio del array
                        array_unshift($puertos, $puertoInicial);
                    }
                }
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }


        return $puertos;
    }
}
