<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('elementor/dynamic_tags/register', function ($dynamic_tags_manager) {

    // Verificar si Elementor está activado
    if (!function_exists('is_plugin_active') || !is_plugin_active('elementor/elementor.php')) {
        return;
    }
    // Verificar si es la pagina de un producto
    if (!is_singular('producto')) {
        return;
    }

    // Define la clase del control personalizado
    class Elementor_Dolar_Blue extends \Elementor\Core\DynamicTags\Tag
    {

        /**
         * Obtiene el nombre de la etiqueta dinamica.
         *
         * Devuelve el nombre de la etiqueta de Dolar Blue.
         *
         * @since 1.0.0
         * @access public
         * @return string Dynamic tag name.
         */
        public function get_name()
        {
            return 'elementor_dolar_blue';
        }

        /**
         * Obtiene el titulo de la etiqueta dinamica.
         *
         * Devuelve el titulo de la etiqueta de Dolar Blue.
         *
         * @since 1.0.0
         * @access public
         * @return string Dynamic tag title.
         */
        public function get_title()
        {
            return esc_html__('Producto - Precio en ARS', 'dolarblue-woocommerce');
        }

        /**
         * Obtiene el grupo de la etiqueta dinamica.
         *
         * Devuelve la lista de grupos de la etiqueta de Dolar Blue.
         *
         * @since 1.0.0
         * @access public
         * @return array Dynamic tag groups.
         */
        public function get_group()
        {
            return ['woocommerce'];
        }

        /**
         * Obtiene la categoria de la etiqueta dinamica
         *
         * Devuelve la lista de categorias de la etiqueta de Dolar Blue.
         *
         * @since 1.0.0
         * @access public
         * @return array Dynamic tag categories.
         */
        public function get_categories()
        {
            return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
        }

        /**
         * Renderiza la salida en el frontend.
         *
         * Escrito en PHP y usado para generar el HTML final.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function render()
        {
            $product_id = get_the_ID();
            echo get_post_meta($product_id, 'precio_dolar_blue', true);
        }
    }

    // Registra el control personalizado
    $dynamic_tags_manager->register(new \Elementor_Dolar_Blue);
});
