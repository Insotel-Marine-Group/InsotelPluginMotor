<?php

class DatosMotor {
    public int $id_servicio = 0;
    public string $tipo_servicio = "";
    public bool $mostrar_vehiculo = true;
    public bool $solo_una_fecha = false;
    public string $tipo_viaje = "seleccionable";
    public bool $solo_adultos = false;

    public function __construct(int $id_servicio = 0, string $tipo_servicio = "", bool $mostrar_vehiculo = true, bool $solo_una_fecha = false, string $tipo_viaje = "seleccionable", bool $solo_adultos = false) {
        $this->id_servicio = $id_servicio;
        $this->tipo_servicio = $tipo_servicio;
        $this->mostrar_vehiculo = $mostrar_vehiculo;
        $this->solo_una_fecha = $solo_una_fecha;
        $this->tipo_viaje = $tipo_viaje;
        $this->solo_adultos = $solo_adultos;
    }

    public function updateFromAtts(array $atts) {
        $this->id_servicio = isset($atts['id_servicio']) ? $atts['id_servicio'] : $this->id_servicio;
        $this->tipo_servicio = isset($atts['tipo_servicio']) ? $atts['tipo_servicio'] : $this->tipo_servicio;
        $this->mostrar_vehiculo = isset($atts['mostrar_vehiculo']) ? filter_var($atts['mostrar_vehiculo'], FILTER_VALIDATE_BOOLEAN) : $this->mostrar_vehiculo;
        $this->solo_una_fecha = isset($atts['solo_una_fecha']) ? filter_var($atts['solo_una_fecha'], FILTER_VALIDATE_BOOLEAN) : $this->solo_una_fecha;
        $this->tipo_viaje = isset($atts['tipo_viaje']) ? $atts['tipo_viaje'] : $this->tipo_viaje;
        $this->solo_adultos = isset($atts['solo_adultos']) ? filter_var($atts['solo_adultos'], FILTER_VALIDATE_BOOLEAN) : $this->solo_adultos;
    }
}

?>
