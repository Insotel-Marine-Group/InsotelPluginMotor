<?php
// Si el archivo es llamado directamente, salir
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// Obtener acceso global a la base de datos
global $wpdb;

// Especificar las tablas que deseas eliminar
$tableIdiomas = $wpdb->prefix . 'insotel_motor_idiomas';
$tableTextos = $wpdb->prefix . 'insotel_motor_textos';
$tableConstantes = $wpdb->prefix . 'insotel_motor_constantes';
$tablePuertos = $wpdb->prefix . 'insotel_motor_puertos';
$tableRutas = $wpdb->prefix . 'insotel_motor_rutas';

// Eliminar las tablas
$wpdb->query("DROP TABLE IF EXISTS $tableConstantes");
$wpdb->query("DROP TABLE IF EXISTS $tableTextos");
$wpdb->query("DROP TABLE IF EXISTS $tableIdiomas");
$wpdb->query("DROP TABLE IF EXISTS $tableRutas");
$wpdb->query("DROP TABLE IF EXISTS $tablePuertos");

// Otras tareas de limpieza (opcional)
// Eliminar opciones específicas de tu plugin
// delete_option('nombre_de_la_opcion');