<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Incluir el archivo del backend
require_once plugin_dir_path(__FILE__) . '/backend.php';

// Función para actualizar los precios en ARS de todos los productos
function update_product_prices_in_ars()
{
    $dolar_blue_value = get_dolar_blue_value();
    if ($dolar_blue_value === false) {
        return;
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );

    $products = new WP_Query($args);

    while ($products->have_posts()) {
        $products->the_post();
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        if ($product->get_sale_price()) :
            $precio_dolar_blue = $product->get_sale_price() * get_dolar_blue_value();
        else :
            $precio_dolar_blue = $product->get_price() * get_dolar_blue_value();
        endif;
        update_post_meta($product_id, 'precio_dolar_blue', $precio_dolar_blue);
    }

    wp_reset_postdata();
}
add_action('dolarblue_update_product_prices_in_ars', 'update_product_prices_in_ars');

// Programar la tarea cron para actualizar cada 15 minutos
function schedule_update_product_prices_cron()
{
    if (!wp_next_scheduled('dolarblue_update_product_prices_in_ars')) {
        wp_schedule_event(time(), 'every_15_minutes', 'dolarblue_update_product_prices_in_ars');
    }
}
add_action('wp', 'schedule_update_product_prices_cron');

// Definir el intervalo de tiempo "every_15_minutes"
function define_every_15_minutes_interval($schedules)
{
    $schedules['every_15_minutes'] = array(
        'interval' => 900, // 15 minutos en segundos (15 * 60)
        'display'  => __('Cada 15 minutos', 'dolarblue-woocommerce'),
    );

    return $schedules;
}
add_filter('cron_schedules', 'define_every_15_minutes_interval');
