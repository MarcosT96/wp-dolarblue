<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Verificar si WooCommerce está activo
function dolarblue_wc_is_woocommerce_active()
{
    return class_exists('WooCommerce');
}
// Función para mostrar un mensaje de aviso si WooCommerce no está activo
function dolarblue_wc_show_install_notice()
{
    $message = __('El plugin de WooCommerce es obligatorio para el funcionamiento de DolarBlue WooCommerce. Por favor, <a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">instala WooCommerce</a> para utilizar este plugin.', 'dolarblue-woocommerce');
    echo '<div class="notice notice-error is-dismissible"><p><b>DolarBlue WooCommerce</b></p><p>' . $message . '</p></div>';
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
    $response = wp_remote_get(DOLAR_API_URL);
    if (is_wp_error($response)) {
        // Guardar el error en un log
        error_log('Error al obtener la URL del Dólar de la API');
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Verificar si la respuesta contiene los datos esperados
    if (isset($data['blue']['value_sell'])) {
        return floatval($data['blue']['value_sell']);
    } else {
        // Guardar el error en un log
        error_log('Error al obtener el valor del Dólar Blue desde la API');
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
        $precio_dolar_blue = floatval($product->get_price()) * get_dolar_blue_value();

    endif;
    update_post_meta($post_id, 'precio_dolar_blue', $precio_dolar_blue);
}
add_action('woocommerce_process_product_meta', 'save_product_custom_field');

// Función para mostrar el campo personalizado en el backend del producto variable
function add_variation_custom_field($loop, $variation_data, $variation)
{
    $product = wc_get_product($variation->ID);

    if ($product->get_sale_price()) {
        $precio_dolar_blue = $product->get_sale_price() * get_dolar_blue_value();
    } else {
        $precio_dolar_blue = floatval($product->get_price()) * get_dolar_blue_value();
    }

    woocommerce_wp_text_input(
        array(
            'id'          => 'precio_dolar_blue[' . $loop . ']',
            'wrapper_class' => 'form-row form-row-first',
            'class'       => 'short wc_input_price',
            'label'       => __('Precio en ARS (Blue)', 'dolarblue-woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
            'value'       => wc_format_localized_price($precio_dolar_blue),
            'data_type'   => 'price',
            'desc_tip'    => 'true',
            'description' => __('Se muestra el precio en ARS basado en el valor del dólar blue.', 'dolarblue-woocommerce')
        )
    );
}
add_action('woocommerce_product_after_variable_attributes', 'add_variation_custom_field', 10, 3);

// Función para guardar el valor del campo personalizado como meta data del producto variable
function save_variation_custom_field($variation_id, $i)
{
    if (isset($_POST['precio_dolar_blue'][$i])) {
        $dolar_blue_value = $_POST['precio_dolar_blue'][$i];
        update_post_meta($variation_id, 'precio_dolar_blue', wc_clean($dolar_blue_value));
    }
}
add_action('woocommerce_save_product_variation', 'save_variation_custom_field', 10, 2);
