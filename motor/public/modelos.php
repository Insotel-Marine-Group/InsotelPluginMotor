<?php
//include_once ("../../sesion.php");

//require_once ("nusoap/nusoap.php");


try {
    $client = new SoapClient("https://api.menorcalines.com/v3/FerryWebService.asmx?WSDL");
	$param = array( 'BrandID' => $_GET["marca"] );

	
//$respuesta = $client->call("GetVehiclesByBrand", $param);
$respuesta = $client->GetVehiclesByBrand($param);

/*
print_r($respuesta);
echo "<br><br>";
*/


// comprobar si llega un elemento o un Array
if ($respuesta->GetVehiclesByBrandResult->ModeloVehiculo->Code){
	
	//echo "**Hay un elemento**";
	$salida .= "<option value=\"".$respuesta->GetVehiclesByBrandResult->ModeloVehiculo->Code."\">".$respuesta->GetVehiclesByBrandResult->ModeloVehiculo->Name."</option>";

	} else {

	//echo "**Hay un ARRAY**";

	$cuantos = count($respuesta->GetVehiclesByBrandResult->ModeloVehiculo);
	$salida = "";

	for ($a=0; $a<$cuantos; $a++){
		$salida .= "<option value=\"".$respuesta->GetVehiclesByBrandResult->ModeloVehiculo[$a]->Code."\">".$respuesta->GetVehiclesByBrandResult->ModeloVehiculo[$a]->Name."</option>";
	}
}



echo $salida;

	
	
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}

?>


