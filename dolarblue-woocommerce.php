<?php
/*
Plugin Name: DolarBlue WooCommerce
Plugin URI: https://www.grupo-met.com
Description: Este plugin convierte los precios a ARS utilizando el valor del dólar blue obtenido de una API externa.
Version: 1.0.1
Author: Marcos Tomassi - Grupo MET
Author URI: https://www.grupo-met.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dolarblue-woocommerce
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
