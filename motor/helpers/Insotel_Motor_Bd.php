<?php
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class Insotel_Motor_Bd
{
    public function create_table_insotel_motor_idiomas($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_idiomas';

        $sql = "CREATE TABLE $nameTable ( id mediumint(9) NOT NULL AUTO_INCREMENT, idioma varchar(5) NOT NULL, PRIMARY KEY  (id)) $charset_collate;";

        dbDelta($sql);


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

        $sql = "CREATE TABLE $nameTable (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            idioma varchar(50) NOT NULL,
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
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);


        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));


        if ($count === 0 || $count === "0") {
            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'ES',
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
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'EN',
                    'label_solo_ida' => 'One way',
                    'label_ida_y_vuelta' => 'Return',
                    'label_trayecto' => 'Route',
                    'label_fecha_viaje' => 'Travel date',
                    'label_pasajeros' => 'Passenger(s)',
                    'label_codigo_promocion' => 'Promo  code',
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
                )
            );


            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'IT',
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
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'CA',
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
                    'label_anadir_vehiculo' => "Viatjo amb vehicle (opera't per  Trasmapi)",
                )
            );
        }
    }

    public function create_table_insotel_motor_constantes($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_motor_constantes';

        $sql = "CREATE TABLE $nameTable (id mediumint(9) NOT NULL AUTO_INCREMENT,
        tipo_formulario varchar(50) NOT NULL,
        promocion varchar(50) NOT NULL,
        is_promocion boolean NOT NULL,
        origen varchar(50) NOT NULL,
        canal_reserva varchar(50) NOT NULL,
        url_motor varchar(300) NOT NULL,
        PRIMARY KEY  (id)) $charset_collate;";

        dbDelta($sql);


        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));


        if ($count === 0 || $count === "0") {
            $wpdb->insert(
                $nameTable,
                array(
                    'tipo_formulario' => 'POST',
                    'promocion' => '',
                    'url_motor' => 'https://booking.formenteralines.com/',
                    'origen' => 'https://formenteralines.dev/',
                    'canal_reserva' => 'https://formenteralines.dev/',
                    'is_promocion' => true

                )
            );
        }
    }
}
