<?php
/*
Plugin Name: DolarBlue WooCommerce
Plugin URI: https://www.grupo-met.com
Description: Este plugin convierte los precios a ARS utilizando el valor del dólar blue obtenido de una API externa.
Version: 1.0
Author: Marcos Tomassi - Grupo MET
Author URI: https://www.grupo-met.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dolarblue-woocommerce
Domain Path: /languages
*/

// Verificar si WooCommerce está activo
function dolarblue_wc_is_woocommerce_active()
{
    return class_exists('WooCommerce');
}
// Función para mostrar un mensaje de aviso si WooCommerce no está activo
function dolarblue_wc_show_install_notice()
{
    $message = __('El plugin de WooCommerce es obligatorio para el funcionamiento de DolarBlue WooCommerce. Por favor, <a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">instala WooCommerce</a> para utilizar este plugin.', 'dolarblue-woocommerce');
    echo '<div class="notice notice-error is-dismissible"><p>' . $message . '</p></div>';
}

// Función para verificar si WooCommerce está activo y mostrar el mensaje de aviso si no lo está
function dolarblue_wc_check_woocommerce_dependency()
{
    if (!dolarblue_wc_is_woocommerce_active() && current_user_can('activate_plugins')) {
        add_action('admin_notices', 'dolarblue_wc_show_install_notice');
    }
}
add_action('admin_init', 'dolarblue_wc_check_woocommerce_dependency');

// Definir la URL de la API
define('DOLAR_API_URL', 'https://api.bluelytics.com.ar/v2/latest');

// Función para obtener el valor del dólar blue
function get_dolar_blue_value()
{
    $response = wp_remote_get('https://api.bluelytics.com.ar/v2/latest');
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Verificar si la respuesta contiene los datos esperados
    if (isset($data['blue']['value_sell'])) {
        return floatval($data['blue']['value_sell']);
    } else {
        return false;
    }
}

// Función para mostrar el campo personalizado en el backend del producto
function add_product_custom_field()
{
    echo '<div class="options_group">';
    woocommerce_wp_text_input(
        array(
            'id'          => 'precio_dolar_blue',
            'label'       => __('Precio en ARS (Blue)', 'dolarblue-woocommerce'),
            'desc_tip'    => 'true',
            'description' => __('Se muestra el precio en ARS basado en el valor del dólar blue.', 'dolarblue-woocommerce'),
            'type'        => 'text',
        )
    );
    echo '</div>';
}
add_action('woocommerce_product_options_pricing', 'add_product_custom_field');

// Función para guardar el valor del campo personalizado como meta data del producto
function save_product_custom_field($post_id)
{

    $product = wc_get_product($post_id);
    if ($product->get_sale_price()) :
        $precio_dolar_blue = $product->get_sale_price() * get_dolar_blue_value();
    else :
        $precio_dolar_blue = $product->get_price() * get_dolar_blue_value();
    endif;
    update_post_meta($post_id, 'precio_dolar_blue', $precio_dolar_blue);
}
add_action('woocommerce_process_product_meta', 'save_product_custom_field');

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

// Función para agregar el CSS en el frontend
function add_custom_styles_to_frontend()
{
    echo '<style>
        .dolar-blue-value {
            color: #157e23; /* Color verde, puedes cambiarlo a tu gusto */
            font-size: 1em;
            display: block;
        }
    
        .precio-dolar {
            color: #555; /* Color gris, puedes cambiarlo a tu gusto */
            font-size: 0.8em;
            display: block;
        }
    
        .dolar-blue-price-container {
            display: inline-block;
            margin-left: 5px;
        }
    </style>';
}
add_action('wp_head', 'add_custom_styles_to_frontend');

// Función para obtener el valor del 'precio_dolar_blue' desde el producto
function get_product_dolar_blue_value($product_id)
{
    return get_post_meta($product_id, 'precio_dolar_blue', true);
}

// Función para agregar el valor del 'precio_dolar_blue' en el HTML del precio
function add_dolar_blue_value_to_price_html($price_html, $product)
{
    $dolar_blue_value = get_product_dolar_blue_value($product->get_id());
    if ($dolar_blue_value) {
        $valor_en_dolar = $price_html;
        $price_html = '<span class="dolar-blue-price-container">';
        $price_html .= '<span class="dolar-blue-value">' . wc_price($dolar_blue_value) . '</span>';
        $price_html .= '<span class="precio-dolar"> USD ' . $valor_en_dolar . '</span>';
        $price_html .= '</span>';
    }
    return $price_html;
}
add_filter('woocommerce_get_price_html', 'add_dolar_blue_value_to_price_html', 10, 2);

// Función para actualizar el precio en el carrito y en el checkout
function update_cart_and_checkout_prices($cart_object)
{
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    foreach ($cart_object->cart_contents as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id'];
        $dolar_blue_value = get_product_dolar_blue_value($product_id);

        if ($dolar_blue_value) {
            $cart_item['data']->set_price($dolar_blue_value);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'update_cart_and_checkout_prices');

// Función para mostrar el precio en ARS en el carrito y en el checkout
function display_cart_and_checkout_prices($product_name, $cart_item, $cart_item_key)
{
    $dolar_blue_value = get_product_dolar_blue_value($cart_item['product_id']);

    if ($dolar_blue_value) {
        $price_in_ars = wc_price($dolar_blue_value);
        return '<span class="cart-item-price">' . $price_in_ars . '</span>';
    }

    return $product_name;
}
add_filter('woocommerce_cart_item_price', 'display_cart_and_checkout_prices', 10, 3);
add_filter('woocommerce_checkout_cart_item_quantity', 'display_cart_and_checkout_prices', 10, 3);
add_filter('woocommerce_cart_item_subtotal', 'display_cart_and_checkout_prices', 10, 3);
add_filter('woocommerce_checkout_cart_subtotal', 'display_cart_and_checkout_prices', 10, 3);
