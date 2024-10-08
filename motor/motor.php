<?php
/*
 * Plugin Name:       MOTOR INSOTEL MARINE GROUP
 * Plugin URI:        https://softme.es/
 * Description:       MOTOR INSOTEL MARINE GROUP desarrollado por SOFTME.
 * Version:           1.1.7
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            SOFTME
 * Author URI:        https://softme.es/
 * License:           GPL v2 or later
 * License URI:       https://softme.es/
 * Update URI:        https://softme.es/
 * Text Domain:       motor
 * Domain Path:       /languages/
 */

//salir si accede directamente a esta url
if (!defined('ABSPATH')) {
    exit;
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//Creacion opciones menu
define('WPP_MOTOR_URL', plugin_dir_url(__FILE__));
define('WPP_MOTOR_PATH', realpath(plugin_dir_path(__FILE__)));


function insotel_motor_menu()
{
    add_menu_page('Insotel Motor', 'Insotel Motor', 'manage_options', WPP_MOTOR_PATH . '/admin/main.php', null, 'dashicons-calendar-alt');
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración de parámetros globales', 'Configuración de parámetros globales', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_constantes.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración puertos', 'Configuración puertos', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_puertos.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración rutas', 'Configuración rutas', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_rutas.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración idiomas', 'Configuración idiomas', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_idiomas.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración textos', 'Configuración textos', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_textos.php', null);
}
add_action('admin_menu', 'insotel_motor_menu');

//Añadir Shortcode
add_shortcode('insotel_motor', 'insotel_motor_shortcode');

function insotel_motor_shortcode($atts)
{

    $id_servicio = isset($atts['id_servicio']) ? $atts['id_servicio'] : 0;
    $tipo_servicio = isset($atts['tipo_servicio']) ? $atts['tipo_servicio'] : "";
    $mostrar_vehiculo = isset($atts['mostrar_vehiculo']) ? filter_var($atts['mostrar_vehiculo'], FILTER_VALIDATE_BOOLEAN) : true;
    $solo_una_fecha = isset($atts['solo_una_fecha']) ? filter_var($atts['solo_una_fecha'], FILTER_VALIDATE_BOOLEAN) : false;
    $tipo_viaje = isset($atts['tipo_viaje']) ? $atts['tipo_viaje'] : "seleccionable";
    $solo_adultos = isset($atts['solo_adultos']) ? filter_var($atts['solo_adultos'], FILTER_VALIDATE_BOOLEAN) : false;
    $dias_deshabilitados = isset($atts['dias_deshabilitados']) ? $atts['dias_deshabilitados'] : "";
    $dias_mes_deshabilitados = isset($atts['dias_mes_deshabilitados']) ? $atts['dias_mes_deshabilitados'] : "";
    $dias_semana_deshabilitados = isset($atts['dias_semana_deshabilitados']) ? $atts['dias_semana_deshabilitados'] : "";
    $id_puerto_inicial = isset($atts['id_puerto_inicial']) ? $atts['id_puerto_inicial'] : "";


    $atts = shortcode_atts(
        array(
            'id_servicio' =>  $id_servicio,
            'tipo_servicio' =>  $tipo_servicio,
            'mostrar_vehiculo' =>  $mostrar_vehiculo,
            'solo_una_fecha' =>  $solo_una_fecha,
            'tipo_viaje' =>  $tipo_viaje,
            'solo_adultos' =>  $solo_adultos,
            'dias_deshabilitados' =>  $dias_deshabilitados,
            'dias_mes_deshabilitados' =>  $dias_mes_deshabilitados,
            'dias_semana_deshabilitados' =>  $dias_semana_deshabilitados,
            'id_puerto_inicial' =>  $id_puerto_inicial
        ),
        $atts,
        'insotel_motor'
    );

    // Encolar los estilos y scripts necesarios

    if (!wp_script_is('insotel_motor_jquery_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_jquery_js');
    }

    if (!wp_script_is('insotel_motor_moment_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_moment_js');
    }

    if (!wp_style_is('insotel_motor_bootstrap_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_bootstrap_css');
    }

    if (!wp_script_is('insotel_motor_popper_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_popper_js');
    }

    if (!wp_script_is('insotel_motor_bootstrap_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_bootstrap_js');
    }

    if (!wp_style_is('insotel_motor_font_awesome_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_font_awesome_css');
    }

    if (!wp_style_is('insotel_motor_main_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_main_css');
    }

    if (!wp_script_is('insotel_motor_main_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_main_js');
    }

    if (!wp_script_is('insotel_motor_daterangepicker_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_daterangepicker_js');
    }

    if (!wp_style_is('insotel_motor_daterangepicker_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_daterangepicker_css');
    }




    ob_start();
    include_once(WPP_MOTOR_PATH . '/helpers/Insotel_Motor_Functions.php');
    include_once(WPP_MOTOR_PATH . '/public/main.php');
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

//Añadir Shortcode
add_shortcode('insotel_motor_movil', 'insotel_motor_shortcode_movil');

function insotel_motor_shortcode_movil($atts)
{

    $id_servicio = isset($atts['id_servicio']) ? $atts['id_servicio'] : 0;
    $tipo_servicio = isset($atts['tipo_servicio']) ? $atts['tipo_servicio'] : "";
    $mostrar_vehiculo = isset($atts['mostrar_vehiculo']) ? filter_var($atts['mostrar_vehiculo'], FILTER_VALIDATE_BOOLEAN) : true;
    $solo_una_fecha = isset($atts['solo_una_fecha']) ? filter_var($atts['solo_una_fecha'], FILTER_VALIDATE_BOOLEAN) : false;
    $tipo_viaje = isset($atts['tipo_viaje']) ? $atts['tipo_viaje'] : "seleccionable";
    $solo_adultos = isset($atts['solo_adultos']) ? filter_var($atts['solo_adultos'], FILTER_VALIDATE_BOOLEAN) : false;
    $dias_deshabilitados = isset($atts['dias_deshabilitados']) ? $atts['dias_deshabilitados'] : "";
    $dias_mes_deshabilitados = isset($atts['dias_mes_deshabilitados']) ? $atts['dias_mes_deshabilitados'] : "";
    $dias_semana_deshabilitados = isset($atts['dias_semana_deshabilitados']) ? $atts['dias_semana_deshabilitados'] : "";
    $id_puerto_inicial = isset($atts['id_puerto_inicial']) ? $atts['id_puerto_inicial'] : "";

    // Encolar los estilos y scripts necesarios

    if (!wp_script_is('insotel_motor_jquery_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_jquery_js');
    }

    if (!wp_script_is('insotel_motor_moment_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_moment_js');
    }

    if (!wp_style_is('insotel_motor_bootstrap_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_bootstrap_css');
    }

    if (!wp_script_is('insotel_motor_popper_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_popper_js');
    }

    if (!wp_script_is('insotel_motor_bootstrap_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_bootstrap_js');
    }

    if (!wp_style_is('insotel_motor_font_awesome_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_font_awesome_css');
    }

    if (!wp_style_is('insotel_motor_main_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_main_css');
    }

    if (!wp_script_is('insotel_motor_main_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_main_js');
    }

    if (!wp_script_is('insotel_motor_daterangepicker_js', 'enqueued')) {
        wp_enqueue_script('insotel_motor_daterangepicker_js');
    }

    if (!wp_style_is('insotel_motor_daterangepicker_css', 'enqueued')) {
        wp_enqueue_style('insotel_motor_daterangepicker_css');
    }


    $atts = shortcode_atts(
        array(
            'id_servicio' =>  $id_servicio,
            'tipo_servicio' =>  $tipo_servicio,
            'mostrar_vehiculo' =>  $mostrar_vehiculo,
            'solo_una_fecha' =>  $solo_una_fecha,
            'tipo_viaje' =>  $tipo_viaje,
            'solo_adultos' =>  $solo_adultos,
            'dias_deshabilitados' =>  $dias_deshabilitados,
            'dias_mes_deshabilitados' =>  $dias_mes_deshabilitados,
            'dias_semana_deshabilitados' =>  $dias_semana_deshabilitados,
            'id_puerto_inicial' =>  $id_puerto_inicial

        ),
        $atts,
        'insotel_motor'
    );


    ob_start();
    include_once(WPP_MOTOR_PATH . '/helpers/Insotel_Motor_Functions.php');
    include_once(WPP_MOTOR_PATH . '/public_movil/main.php');
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function registrar_librerias_insotel_motor()
{
    // Registrar main.css
    wp_register_style(
        'insotel_motor_main_css',
        plugins_url('public/main.css', __FILE__),
        array(),
        null
    );

    // Registrar main.js
    wp_register_script(
        'insotel_motor_main_js',
        plugins_url('public/main.js', __FILE__),
        array(),
        null
    );

    // Registrar Font Awesome CSS
    wp_register_style(
        'insotel_motor_font_awesome_css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css',
        array(),
        '5.9.0'
    );

    // Registrar jQuery
    wp_register_script(
        'insotel_motor_jquery_js',
        'https://cdn.jsdelivr.net/jquery/latest/jquery.min.js',
        array(),
        null,
        true
    );

    // Registrar Moment.js
    wp_register_script(
        'insotel_motor_moment_js',
        'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
        array(),
        null,
        true
    );

    // Registrar bootstrap.css
    wp_enqueue_style(
        'insotel_motor_bootstrap_css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        array(),
        '5.3.3'
    );

    // Registrar popper.js
    wp_enqueue_script(
        'insotel_motor_popper_js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
        array(),
        '1.14.3',
        true
    );

    // Registrar bootstrap.js
    wp_enqueue_script(
        'insotel_motor_bootstrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        array('insotel_motor_jquery', 'insotel_motor_popper_js'),
        '5.3.3',
        true
    );

    // Registrar DateRangePicker JavaScript
    wp_register_script(
        'insotel_motor_daterangepicker_js',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
        array('insotel_motor_jquery_js', 'insotel_motor_moment_js'),
        null,
        true
    );

    // Registrar DateRangePicker CSS
    wp_register_style(
        'insotel_motor_daterangepicker_css',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
        array(),
        null
    );
}
// Engancha la función a wp_enqueue_scripts
add_action('wp_enqueue_scripts', 'registrar_librerias_insotel_motor');



function wp_learn_create_database_table_motor()
{
    global $wpdb;
    require_once WPP_MOTOR_PATH . '/helpers/Insotel_Motor_Bd.php';
    $Insotel_Motor_bd = new Insotel_Motor_Bd;
    $Insotel_Motor_bd->create_table_insotel_motor_idiomas($wpdb);
    $Insotel_Motor_bd->create_table_insotel_motor_textos($wpdb);
    $Insotel_Motor_bd->create_table_insotel_motor_constantes($wpdb);
    $Insotel_Motor_bd->create_table_insotel_motor_puertos($wpdb);
    $Insotel_Motor_bd->create_table_insotel_motor_rutas($wpdb);
}
// Creamos la base da datos
register_activation_hook(__FILE__, 'wp_learn_create_database_table_motor');
