<?php
/*
 * Plugin Name:       MOTOR INSOTEL MARINE GROUP
 * Plugin URI:        https://softme.es/
 * Description:       MOTOR INSOTEL MARINE GROUP desarrollado por SOFTME.
 * Version:           1.0.0
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

//Creacion opciones menu
define('WPP_MOTOR_URL', plugin_dir_url(__FILE__));
define('WPP_MOTOR_PATH', realpath(plugin_dir_path(__FILE__)));


function insotel_motor_menu()
{
    add_menu_page('Insotel Motor', 'Insotel Motor', 'manage_options', WPP_MOTOR_PATH . '/admin/main.php', null, 'dashicons-calendar-alt');
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración textos', 'Configuración textos', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_textos.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración idiomas', 'Configuración idiomas', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_idiomas.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración constantes', 'Configuración constantes', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_constantes.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración puertos', 'Configuración puertos', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_puertos.php', null);
    add_submenu_page(WPP_MOTOR_PATH . '/admin/main.php', 'Configuración rutas', 'Configuración rutas', 'manage_options', WPP_MOTOR_PATH . '/admin/configuracion_rutas.php', null);
}
add_action('admin_menu', 'insotel_motor_menu');

//Añadir Shortcode
add_shortcode('insotel_motor', 'insotel_motor_shortcode');

function insotel_motor_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'modo' => "normal",
        ),
        $atts,
        'insotel_motor'
    );

    // Encolar los estilos y scripts necesarios
    wp_enqueue_script('insotel_motor_jquery_js');
    wp_enqueue_script('insotel_motor_moment_js');

    wp_enqueue_style('insotel_motor_bootstrap_css');
    wp_enqueue_script('insotel_motor_popper_js');
    wp_enqueue_script('insotel_motor_bootstrap_js');



    wp_enqueue_style('insotel_motor_font_awesome_css');
    wp_enqueue_style('insotel_motor_main_css');
    wp_enqueue_script('insotel_motor_main_js');

    wp_enqueue_script('insotel_motor_daterangepicker_js');
    wp_enqueue_style('insotel_motor_daterangepicker_css');

    ob_start();
    include(WPP_MOTOR_PATH . '/public/main.php');
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}



//Añadir Shortcode
add_shortcode('insotel_motor_servicios', 'insotel_motor_servicios_shortcode');

function insotel_motor_servicios_shortcode($atts)
{
    $id_servicio = intval($atts['id_servicio']);
    $tipo_servicio = $atts['tipo_servicio'];
    $tipo_calendario = $atts['tipo_calendario'];
    $opciones_ida_vuelta = $atts['opciones_ida_vuelta'];
    $tipo_pasajero = $atts['tipo_pasajero'];

    $atts = shortcode_atts(
        array(
            'modo' => "modo_servicios",
            'id_servicio' =>  $id_servicio,
            'tipo_servicio' =>  $tipo_servicio,
            'tipo_calendario' =>  $tipo_calendario,
            'opciones_ida_vuelta' =>  $opciones_ida_vuelta,
            'tipo_pasajero' =>  $tipo_pasajero
        ),
        $atts,
        'insotel_motor_servicios'
    );

    // Encolar los estilos y scripts necesarios
    wp_enqueue_script('insotel_motor_jquery_js');
    wp_enqueue_script('insotel_motor_moment_js');

    wp_enqueue_style('insotel_motor_bootstrap_css');
    wp_enqueue_script('insotel_motor_popper_js');
    wp_enqueue_script('insotel_motor_bootstrap_js');

    wp_enqueue_style('insotel_motor_font_awesome_css');
    wp_enqueue_style('insotel_motor_main_css');
    wp_enqueue_script('insotel_motor_main_js');

    wp_enqueue_style('insotel_motor_daterangepicker_css');
    wp_enqueue_script('insotel_motor_daterangepicker_js');

    ob_start();
    include(WPP_MOTOR_PATH . '/public/main.php');
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
