<?php
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class Insotel_Motor_Bd
{
    public function create_table_insotel_motor_idiomas($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_idiomas';

        if ($wpdb->get_var("SHOW TABLES LIKE '$nameTable'") != $nameTable) {
            $sql = "CREATE TABLE $nameTable (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                idioma varchar(5) NOT NULL,
                idioma_por_defecto boolean NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE (idioma)
                ) $charset_collate;";

            dbDelta($sql);
        }


        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));

        // if ($count === 0 || $count === "0") {
        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma' => 'ES',
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma' => 'EN',
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma' => 'IT',
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma' => 'CA',
        //         )
        //     );
        // }
    }

    public function create_table_insotel_motor_textos($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_textos';
        $idiomasTable = $wpdb->prefix . 'insotel_motor_idiomas';

        if ($wpdb->get_var("SHOW TABLES LIKE '$nameTable'") != $nameTable) {
            $sql = "CREATE TABLE $nameTable (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                idioma_id mediumint(9) NOT NULL,
                label_solo_ida varchar(100) NOT NULL,
                label_ida_y_vuelta varchar(100) NOT NULL,
                label_pasajeros varchar(100) NOT NULL,
                label_adultos varchar(100) NOT NULL,
                label_ninos varchar(100) NOT NULL,
                label_seniors varchar(100) NOT NULL,
                label_bebes varchar(100) NOT NULL,
                label_familia varchar(200) NOT NULL,
                label_fn_general varchar(200) NOT NULL,
                label_fn_especial varchar(200) NOT NULL,
                label_reservar varchar(100) NOT NULL,
                label_anos varchar(100) NOT NULL,
                -- label_edad_adultos varchar(100) NOT NULL,
                -- label_edad_ninos varchar(100) NOT NULL,
                -- label_edad_seniors varchar(100) NOT NULL,
                -- label_edad_bebes varchar(100) NOT NULL, 
                label_mascotas varchar(200) NOT NULL,
                label_anadir_vehiculo varchar(200) NOT NULL,
                label_tipo_vehiculo varchar(200) NOT NULL,
                label_marca varchar(200) NOT NULL,
                label_modelo varchar(200) NOT NULL,
                label_aceptar varchar(200) NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (idioma_id) REFERENCES $idiomasTable(id) ON DELETE CASCADE
                ) $charset_collate;";

            dbDelta($sql);
        }


        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));

        // if ($count == 0) {
        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma_id' => 1, // ES
        //             'label_solo_ida' => 'Sólo ida',
        //             'label_ida_y_vuelta' => 'Ida y vuelta',
        //             'label_pasajeros' => 'Pasajero(s)',
        //             'label_adultos' => 'Adultos',
        //             'label_ninos' => 'Niños',
        //             'label_seniors' => 'Seniors',
        //             'label_bebes' => 'Bebés',
        //             'label_familia' => "Familia Numerosa",
        //             'label_fn_general' => 'Reg. General',
        //             'label_fn_especial' => 'Reg. Especial',
        //             'label_reservar' => 'Reservar',
        //             'label_edad_adultos' => '14-59 años',
        //             'label_edad_ninos' => '4-13 años',
        //             'label_edad_seniors' => '+59 años',
        //             'label_edad_bebes' => '0-3 años',
        //             'label_mascotas' => 'Animales de compañia',
        //             'label_anadir_vehiculo' => 'Añadir vehículo (operado por Trasmapi)',
        //             'label_tipo_vehiculo' => 'Tipo vehículo',
        //             'label_marca' => 'Marca',
        //             'label_modelo' => 'Modelo',
        //             'label_aceptar' => 'Aceptar',
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma_id' => 2, // EN
        //             'label_solo_ida' => 'One way',
        //             'label_ida_y_vuelta' => 'Return',
        //             'label_pasajeros' => 'Passenger(s)',
        //             'label_adultos' => 'Adults',
        //             'label_ninos' => 'Children',
        //             'label_seniors' => 'Seniors',
        //             'label_bebes' => 'Babies',
        //             'label_familia' => "Large Family",
        //             'label_fn_general' => 'Reg. General',
        //             'label_fn_especial' => 'Reg. Especial',
        //             'label_reservar' => 'Book now',
        //             'label_edad_adultos' => '14-59 years',
        //             'label_edad_ninos' => '4-13 years',
        //             'label_edad_seniors' => '+59 years',
        //             'label_edad_bebes' => '0-3 years',
        //             'label_mascotas' => 'Pets',
        //             'label_anadir_vehiculo' => 'Add a vehicle (operated by Trasmapi)',
        //             'label_tipo_vehiculo' => 'Vehicle type',
        //             'label_marca' => 'Brand',
        //             'label_modelo' => 'Model',
        //             'label_aceptar' => 'Accept',
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma_id' => 3, // IT
        //             'label_solo_ida' => 'Solo andata',
        //             'label_ida_y_vuelta' => 'Andata e ritorno',
        //             'label_pasajeros' => 'Passeggeri',
        //             'label_adultos' => 'Adulti',
        //             'label_ninos' => 'Bambini',
        //             'label_seniors' => 'Seniors',
        //             'label_bebes' => 'Neonati',
        //             'label_familia' => "Famiglia numerosa",
        //             'label_fn_general' => 'Reg. Generale',
        //             'label_fn_especial' => 'Reg. Speciale',
        //             'label_reservar' => 'Cerca',
        //             'label_edad_adultos' => '14-59 anni',
        //             'label_edad_ninos' => '4-13 anni',
        //             'label_edad_seniors' => '+59 anni',
        //             'label_edad_bebes' => '0-3 anni',
        //             'label_mascotas' => 'Animali domestici',
        //             'label_anadir_vehiculo' => 'Viaggia con il veicolo (gestito da Trasmapi)',
        //             'label_tipo_vehiculo' => 'Tipo di veicolo',
        //             'label_marca' => 'Marca',
        //             'label_modelo' => 'Modelo',
        //             'label_aceptar' => 'Accettare',
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'idioma_id' => 4, // CA
        //             'label_solo_ida' => 'Només anada',
        //             'label_ida_y_vuelta' => 'Anada i tornada',
        //             'label_pasajeros' => 'Passatger(s)',
        //             'label_adultos' => 'Adults',
        //             'label_ninos' => 'Nens',
        //             'label_seniors' => 'Sèniors',
        //             'label_bebes' => 'Nadons',
        //             'label_familia' => "Família Nombrosa",
        //             'label_fn_general' => 'Reg. General',
        //             'label_fn_especial' => 'Reg. Especial',
        //             'label_reservar' => 'Reserva ara',
        //             'label_edad_adultos' => '14-59 anys',
        //             'label_edad_ninos' => '4-13 anys',
        //             'label_edad_seniors' => '+59 anys',
        //             'label_edad_bebes' => '0-3 anys',
        //             'label_mascotas' => 'Animals de companyia',
        //             'label_anadir_vehiculo' => "Viatjo amb vehicle (opera't per Trasmapi)",
        //             'label_tipo_vehiculo' => 'Tipus vehicle',
        //             'label_marca' => 'Marca',
        //             'label_modelo' => 'Model',
        //             'label_aceptar' => 'Acceptar',
        //         )
        //     );
        // }
    }

    public function create_table_insotel_motor_constantes($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_constantes';

        if ($wpdb->get_var("SHOW TABLES LIKE '$nameTable'") != $nameTable) {
            $sql = "CREATE TABLE $nameTable (id mediumint(9) NOT NULL AUTO_INCREMENT,
            promocion varchar(50) NOT NULL,
            is_promocion boolean NOT NULL,
            origen varchar(50) NOT NULL,
            canal_reserva varchar(50) NOT NULL,
            url_motor varchar(300) NOT NULL,
            edad_adulto varchar(20) NOT NULL,
            edad_nino varchar(20) NOT NULL,
            edad_senior varchar(20) NOT NULL,
            edad_bebes varchar(20) NOT NULL,
            PRIMARY KEY  (id)) $charset_collate;";
            dbDelta($sql);
        }



        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));


        if ($count == 0) {
            $wpdb->insert(
                $nameTable,
                array(
                    'promocion' => '',
                    'url_motor' => '',
                    'origen' => '',
                    'canal_reserva' => '',
                    'is_promocion' => true,
                    'edad_adulto' => '14-59',
                    'edad_nino' => '4-13',
                    'edad_senior' => '59',
                    'edad_bebes' => '0-3',
                )
            );
        }
    }

    public function create_table_insotel_motor_puertos($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_puertos';

        if ($wpdb->get_var("SHOW TABLES LIKE '$nameTable'") != $nameTable) {
            $sql = "CREATE TABLE $nameTable (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                nombre varchar(50) NOT NULL,
                valor varchar(20) NOT NULL,
                orden int(11) NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE (nombre),
                UNIQUE (valor),
                UNIQUE (orden)
                ) $charset_collate;";

            dbDelta($sql);
        }


        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));

        // if ($count == 0) {
        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre' => 'Mallorca',
        //             'valor' => 'mall',
        //             'orden' => '1'
        //         )
        //     );
        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre' => 'Menorca',
        //             'valor' => 'men',
        //             'orden' => '2'
        //         )
        //     );
        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre' => 'Barcelona',
        //             'valor' => 'bar',
        //             'orden' => '3'
        //         )
        //     );
        // }
    }

    public function create_table_insotel_motor_rutas($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_rutas';
        $puertosTable = $wpdb->prefix . 'insotel_motor_puertos';

        if ($wpdb->get_var("SHOW TABLES LIKE '$nameTable'") != $nameTable) {
            $sql = "CREATE TABLE $nameTable (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                nombre_ruta varchar(50) NOT NULL,
                puerto_ruta_ida mediumint(9) NOT NULL,
                puerto_ruta_vuelta mediumint(9) NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE (nombre_ruta),
                FOREIGN KEY (puerto_ruta_ida) REFERENCES $puertosTable(id) ON DELETE CASCADE,
                FOREIGN KEY (puerto_ruta_vuelta) REFERENCES $puertosTable(id) ON DELETE CASCADE
                ) $charset_collate;";

            dbDelta($sql);
        }

        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));

        // if ($count == 0) {
        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre_ruta' => 'Mallorca - Menorca',
        //             'puerto_ruta_ida' => 1,
        //             'puerto_ruta_vuelta' => 2
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre_ruta' => 'Menorca - Mallorca',
        //             'puerto_ruta_ida' => 2,
        //             'puerto_ruta_vuelta' => 1
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre_ruta' => 'Barcelona - Menorca',
        //             'puerto_ruta_ida' => 3,
        //             'puerto_ruta_vuelta' => 2
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre_ruta' => 'Menorca - Barcelona',
        //             'puerto_ruta_ida' => 2,
        //             'puerto_ruta_vuelta' => 3
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre_ruta' => 'Barcelona - Mallorca',
        //             'puerto_ruta_ida' => 3,
        //             'puerto_ruta_vuelta' => 1
        //         )
        //     );

        //     $wpdb->insert(
        //         $nameTable,
        //         array(
        //             'nombre_ruta' => 'Mallorca - Barcelona',
        //             'puerto_ruta_ida' => 1,
        //             'puerto_ruta_vuelta' => 3
        //         )
        //     );
        // }
    }
}
