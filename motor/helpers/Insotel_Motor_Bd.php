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
                PRIMARY KEY  (id),
                UNIQUE (idioma)
                ) $charset_collate;";

            dbDelta($sql);
        }


        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));

        if ($count === 0 || $count === "0") {
            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'ES',
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'EN',
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'IT',
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'CA',
                )
            );
        }
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
                label_trayecto varchar(100) NOT NULL,
                label_fecha_viaje varchar(100) NOT NULL,
                label_pasajeros varchar(100) NOT NULL,
                label_codigo_promocion varchar(100) NOT NULL,
                label_adultos varchar(100) NOT NULL,
                label_ninos varchar(100) NOT NULL,
                label_seniors varchar(100) NOT NULL,
                label_bebes varchar(100) NOT NULL,
                label_descuentos varchar(100) NOT NULL,
                label_sin_descuentos varchar(100) NOT NULL,
                label_fn_general varchar(200) NOT NULL,
                label_fn_especial varchar(200) NOT NULL,
                label_anos varchar(100) NOT NULL,
                label_reservar varchar(100) NOT NULL,
                label_edad_adultos varchar(100) NOT NULL,
                label_edad_ninos varchar(100) NOT NULL,
                label_edad_seniors varchar(100) NOT NULL,
                label_edad_bebes varchar(100) NOT NULL, 
                label_mascotas varchar(200) NOT NULL,
                label_anadir_vehiculo varchar(200) NOT NULL,
                ruta_ida varchar(200) NOT NULL,
                ruta_vuelta varchar(200) NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (idioma_id) REFERENCES $idiomasTable(id) ON DELETE CASCADE
                ) $charset_collate;";

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
                    'idioma_id' => 1, // ES
                    'label_solo_ida' => 'Sólo ida',
                    'label_ida_y_vuelta' => 'Ida y vuelta',
                    'label_trayecto' => 'Trayecto',
                    'label_fecha_viaje' => 'Fecha de viaje',
                    'label_pasajeros' => 'Pasajero(s)',
                    'label_codigo_promocion' => 'Código promoción',
                    'label_adultos' => 'Adultos',
                    'label_ninos' => 'Niños',
                    'label_seniors' => 'Seniors',
                    'label_bebes' => 'Bebés',
                    'label_descuentos' => 'Descuentos',
                    'label_sin_descuentos' => 'No tengo descuentos',
                    'label_fn_general' => 'Fam. Num. General',
                    'label_fn_especial' => 'Fam. Num. Especial',
                    'label_anos' => 'años',
                    'label_reservar' => 'Reservar',
                    'label_edad_adultos' => '14-59 años',
                    'label_edad_ninos' => '4-13 años',
                    'label_edad_seniors' => '+59 años',
                    'label_edad_bebes' => '0-3 años',
                    'label_mascotas' => 'Animales de compañia',
                    'label_anadir_vehiculo' => 'Añadir vehículo (operado por Trasmapi)',
                    'ruta_ida' => 'Ibiza',
                    'ruta_vuelta' => 'Formentera'
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma_id' => 2, // EN
                    'label_solo_ida' => 'One way',
                    'label_ida_y_vuelta' => 'Return',
                    'label_trayecto' => 'Route',
                    'label_fecha_viaje' => 'Travel date',
                    'label_pasajeros' => 'Passenger(s)',
                    'label_codigo_promocion' => 'Promo code',
                    'label_adultos' => 'Adults',
                    'label_ninos' => 'Children',
                    'label_seniors' => 'Seniors',
                    'label_bebes' => 'Babies',
                    'label_descuentos' => 'Discounts',
                    'label_sin_descuentos' => "I don't have discounts",
                    'label_fn_general' => 'Fam. Num. General',
                    'label_fn_especial' => 'Fam. Num. Especial',
                    'label_anos' => 'years',
                    'label_reservar' => 'Book now',
                    'label_edad_adultos' => '14-59 years',
                    'label_edad_ninos' => '4-13 years',
                    'label_edad_seniors' => '+59 years',
                    'label_edad_bebes' => '0-3 years',
                    'label_mascotas' => 'Pets',
                    'label_anadir_vehiculo' => 'Add a vehicle (operated by Trasmapi)',
                    'ruta_ida' => 'Ibiza',
                    'ruta_vuelta' => 'Formentera'
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma_id' => 3, // IT
                    'label_solo_ida' => 'Solo andata',
                    'label_ida_y_vuelta' => 'Andata e ritorno',
                    'label_trayecto' => 'Tratta',
                    'label_fecha_viaje' => 'Data',
                    'label_pasajeros' => 'Passeggeri',
                    'label_codigo_promocion' => 'Codice promozionale',
                    'label_adultos' => 'Adulti',
                    'label_ninos' => 'Bambini',
                    'label_seniors' => 'Seniors',
                    'label_bebes' => 'Neonati',
                    'label_descuentos' => 'Sconto',
                    'label_sin_descuentos' => "Nessuno sconto",
                    'label_fn_general' => 'Fam. Num. Generale',
                    'label_fn_especial' => 'Fam. Num. Speciale',
                    'label_anos' => 'anni',
                    'label_reservar' => 'Cerca',
                    'label_edad_adultos' => '14-59 anni',
                    'label_edad_ninos' => '4-13 anni',
                    'label_edad_seniors' => '+59 anni',
                    'label_edad_bebes' => '0-3 anni',
                    'label_mascotas' => 'Animali domestici',
                    'label_anadir_vehiculo' => 'Viaggia con il veicolo (gestito da Trasmapi)',
                    'ruta_ida' => 'Ibiza',
                    'ruta_vuelta' => 'Formentera'
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma_id' => 4, // CA
                    'label_solo_ida' => 'Només anada',
                    'label_ida_y_vuelta' => 'Anada i tornada',
                    'label_trayecto' => 'Trajecte',
                    'label_fecha_viaje' => 'Data de viatge',
                    'label_pasajeros' => 'Passatger(s)',
                    'label_codigo_promocion' => 'Codi de promoció',
                    'label_adultos' => 'Adults',
                    'label_ninos' => 'Nens',
                    'label_seniors' => 'Sèniors',
                    'label_bebes' => 'Nadons',
                    'label_descuentos' => 'Descomptes',
                    'label_sin_descuentos' => 'No tinc descomptes',
                    'label_fn_general' => 'Fam. Num. General',
                    'label_fn_especial' => 'Fam. Num. Especial',
                    'label_anos' => 'anys',
                    'label_reservar' => 'Reserva ara',
                    'label_edad_adultos' => '14-59 anys',
                    'label_edad_ninos' => '4-13 anys',
                    'label_edad_seniors' => '+59 anys',
                    'label_edad_bebes' => '0-3 anys',
                    'label_mascotas' => 'Animals de companyia',
                    'label_anadir_vehiculo' => "Viatjo amb vehicle (opera't per Trasmapi)",
                    'ruta_ida' => 'Ibiza',
                    'ruta_vuelta' => 'Formentera'
                )
            );
        }
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
                    'url_motor' => 'https://booking.formenteralines.com/',
                    'origen' => 'https://formenteralines.dev/',
                    'canal_reserva' => 'https://formenteralines.dev/',
                    'is_promocion' => true

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
