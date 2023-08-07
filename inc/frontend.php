<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Incluir el archivo del backend
require_once plugin_dir_path(__FILE__) . '/backend.php';

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

// Funcion que muestra el precio cuando es variacion
add_filter('woocommerce_available_variation', 'modify_variation_prices_display');
function modify_variation_prices_display($data)
{
    $variation_id = $data['variation_id'];
    $product = wc_get_product($variation_id);
    $precio_dolar_blue = get_post_meta($variation_id, 'precio_dolar_blue', true);

    if ($precio_dolar_blue) {
        $data['price_html'] = '<span class="price">' . wc_price($precio_dolar_blue) . '</span>';
    }

    return $data;
}

// Funcion que calcula el precio de la variacion en el carrito y checkout
add_action('woocommerce_before_calculate_totals', 'adjust_cart_item_prices');
function adjust_cart_item_prices($cart_object)
{
    foreach ($cart_object->cart_contents as $key => $value) {
        $product_id = $value['product_id'];
        $variation_id = $value['variation_id'];

        if ($variation_id) {
            $precio_dolar_blue = get_post_meta($variation_id, 'precio_dolar_blue', true);
            if ($precio_dolar_blue) {
                $value['data']->set_price($precio_dolar_blue);
            }
        }
    }
}

// Actualiza el Precio del carrito de compras
add_filter('woocommerce_cart_item_price', 'adjust_cart_table_item_prices', 10, 3);
function adjust_cart_table_item_prices($price, $cart_item, $cart_item_key)
{
    $product = $cart_item['data'];
    $variation_id = $cart_item['variation_id'];

    if ($variation_id) {
        $precio_dolar_blue = get_post_meta($variation_id, 'precio_dolar_blue', true);
        if ($precio_dolar_blue) {
            return wc_price($precio_dolar_blue);
        }
    }

    return $price;
}

// Actualiza el Subtotal del carrito de compras
add_filter('woocommerce_cart_item_subtotal', 'adjust_cart_table_item_subtotals', 10, 3);
function adjust_cart_table_item_subtotals($subtotal, $cart_item, $cart_item_key)
{
    $product = $cart_item['data'];
    $variation_id = $cart_item['variation_id'];

    if ($variation_id) {
        $precio_dolar_blue = get_post_meta($variation_id, 'precio_dolar_blue', true);
        if ($precio_dolar_blue) {
            $subtotal_value = $precio_dolar_blue * $cart_item['quantity'];
            return wc_price($subtotal_value);
        }
    }

    return $subtotal;
}

// Oculta el precio minimo en el titulo de la tabla del Finalizar Compra
add_filter('woocommerce_cart_item_name', 'adjust_checkout_product_name_price', 10);

function adjust_checkout_product_name_price($product_name)
{ ?>
    <style>
        span.cart-item-price {
            display: none;
        }
    </style>
<?php
    return $product_name;
}
