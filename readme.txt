=== DolarBlue WP ===
Contributors: grupomet
Tags: inflacion, dolar blue, dolar, argentina, precio, precio dolar, precio dolar blue, cambiar precio
Requires at least: 6.1.0
Tested up to: 6.2.2
Requires PHP: 7.4
Stable tag: 1.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Harto de tener que ajustar constantemente los precios debido a la inflación? Esta herramienta te permite mantener los precios actualizados en toda tu tienda de forma automática, ajustándolos según la cotización del "Dólar Blue". También tienes la opción de utilizar el "Dólar Oficial" o establecer un valor personalizado manualmente.

== Description ==
Crea un campo automaticamente de los precios de los productos WooCommerce. 

Los precios son editados automaticamente desde la base de datos. 

Utiliza la API de bluelytics para traer los datos del Dolar Blue.

Las actualizaciones de precios se realizan de forma automatica cada 15 minutos. 

Esta es la primer version del plugin, toda sugerencia, opinion o critica sera bienvenida. 

[Reporta cualquier error](https://wordpress.org/support/plugin/dolarblue-wp/) que encuentres si piensas que esta relacionado al plugin.

¿Podrias por favor [dejar un review positivo aqui](https://wordpress.org/support/plugin/dolarblue-wp/reviews/#new-post)? Eso seria una gran ayuda. Gracias.

[Contacta al autor](https://profiles.wordpress.org/grupomet/)  del plugin para enviarle un mensaje.


== Frequently Asked Questions == 
=Que pasa con los precios si desactivo el plugin=
Los precios son actualizados desde la base de datos, por lo que al desactivar o desinstalar el plugin nada cambiara

=Como vuelvo a ingresar los precios en mis productos al desactivar el plugin?=
Simplemente utiliza el campo "precio" en la pagina de edicion de producto, como lo harias normalmente. Si el plugin sigue activo, asegurate de que el campo "valor TDC" se encuentra vacio

== LIMITACIONES ==

* NO SE HAN ENCONTRADO AUN

== Screenshots == 

== Changelog == 

# Changelog

## Version 1.1.3

- Archivo `dolarblue-wp.php`: Se realizó un cambio de nombre en el plugin para que sea exclusivamente reconocible por WordPress.
- Se sube el Plugin al Repositorio de Wordpress.

## Version 1.1.2

- Archivo `wp-dolarblue.php`: Se realizó un cambio de nombre en el plugin para que sea exclusivamente reconocible por WordPress.
- Se sube el Plugin al Repositorio de Wordpress.

## Version 1.1.1

- Archivo `dolarblue.php`: Cambio de nombre del plugin por derechos de WooCommerce.

## Version 1.1.0

- Archivo `dolarBlue.php`: Contiene la funcionalidad de agregar la etiqueta dinamica en Elementor Pro.
- Actualizado Plugin principal para incluir el archivo `dolarBlue.php`.

### Funciones Elementor / dolarBlue:

- `dynamic_tags_manager`: Registra la etiqueta dinamica en Elementor.

## Version 1.0.1

### Cambios en la organización de archivos:

- Archivo `backend.php`: Contiene las funciones relacionadas con el backend del plugin.
- Archivo `frontend.php`: Contiene las funciones relacionadas con el frontend del plugin.
- Archivo `cron.php`: Contiene las funciones relacionadas con la programación de tareas cron para actualizar los precios.

### Funciones Backend:

- Se ha agregado un registro de errores en el caso que no pueda obtener el API del Dolar Blue.

## Version 1.0

### Funciones Backend:

- `dolarblue_wc_is_woocommerce_active()`: Verifica si WooCommerce está activo.
- `dolarblue_wc_show_install_notice()`: Muestra un mensaje de aviso si WooCommerce no está activo.
- `dolarblue_wc_check_woocommerce_dependency()`: Verifica si WooCommerce está activo y muestra el mensaje de aviso si no lo está.
- `add_product_custom_field()`: Muestra el campo personalizado "precio_dolar_blue" en el backend del producto.
- `save_product_custom_field($post_id)`: Guarda el valor del campo personalizado como meta data del producto.
- `update_product_prices_in_ars()`: Actualiza los precios en ARS de todos los productos basado en el valor del dólar blue.
- `schedule_update_product_prices_cron()`: Programa la tarea cron para actualizar cada 15 minutos.
- `define_every_15_minutes_interval($schedules)`: Define el intervalo de tiempo "every_15_minutes".

### Funciones Frontend:

- `get_dolar_blue_value()`: Obtiene el valor del dólar blue desde la API.
- `add_dolar_blue_value_to_price_html($price_html, $product)`: Agrega el valor del 'precio_dolar_blue' en el HTML del precio.
- `add_custom_styles_to_frontend()`: Agrega el CSS en el frontend para estilizar el contenido.
- `get_product_dolar_blue_value($product_id)`: Obtiene el valor del 'precio_dolar_blue' desde el producto.
- `display_cart_and_checkout_prices($product_name, $cart_item, $cart_item_key)`: Muestra el precio en ARS en el carrito y en el checkout.

### Otras funciones:

- `get_dolar_blue_value()`: Obtiene el valor del dólar blue desde la API.


