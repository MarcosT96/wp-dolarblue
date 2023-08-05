<?php
/*
Plugin Name: DolarBlue WP
Plugin URI: https://github.com/MarcosT96/dolarblue-wp
Description: Este plugin convierte los precios de WooCommerce a ARS utilizando el valor del dólar blue obtenido de una API externa.
Version: 1.1.4.1
Author: Marcos Tomassi - Grupo MET
Author URI: https://www.grupo-met.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dolarblue
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Incluir el archivo del backend
require_once plugin_dir_path(__FILE__) . 'inc/backend.php';

// Incluir el archivo del frontend
require_once plugin_dir_path(__FILE__) . 'inc/frontend.php';

// Incluir el archivo del cron
require_once plugin_dir_path(__FILE__) . 'inc/cron.php';

// Incluir la etiqueta dinamica de Elementor
require_once plugin_dir_path(__FILE__) . 'inc/elementor/dolarBlue.php';
