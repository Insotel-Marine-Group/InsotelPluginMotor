<?php
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class Insotel_Motor_Functions
{
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
                if ($segment == $lang["idioma"]) {
                    return $lang["idioma"];
                }
            }
        }

        return "ES";
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
}
