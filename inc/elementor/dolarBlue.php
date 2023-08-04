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
         * Get dynamic tag name.
         *
         * Retrieve the name of the ACF average tag.
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
         * Get dynamic tag title.
         *
         * Returns the title of the ACF average tag.
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
         * Get dynamic tag groups.
         *
         * Retrieve the list of groups the ACF average tag belongs to.
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
         * Get dynamic tag categories.
         *
         * Retrieve the list of categories the ACF average tag belongs to.
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
         * Render tag output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
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
