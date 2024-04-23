<?php
/*
Plugin Name: Ust Testimonial
Plugin URI: https://itdevstudio.com/plugins
Description: Display Testimonial Using Widgets
Version: 0.1.0
Author: Gaurav Khandelwal
Author URI: https://itdevstudio.com/
Text Domain: ust-testimonials
Domain Path: /languages
*/

if (!defined('ABSPATH')){
    exit;
}

if (!class_exists('Ust_Testimonials')):
    class Ust_Testimonials
    {
        function __construct()
        {
            $this->load_textdomain();
            $this->define_constants();
            require_once( UST_TESTIMONIALS_PATH . 'post-types/class.ust-testimonials-cpt.php' );
            $USTTestimonialsPostType = new UST_Testimonials_Post_Type();

            require_once( UST_TESTIMONIALS_PATH . 'widgets/class.ust-testimonials-widget.php' );
            $USTTestimonialsWidget = new UST_Testimonials_Widget();    
            add_filter( 'archive_template', array( $this, 'load_custom_archive_template' ) );
            add_filter( 'single_template', array( $this, 'load_custom_single_template' ) );
        }
        public function define_constants()
        {
            define('UST_TESTIMONIALS_PATH', plugin_dir_path(__FILE__));
            define('UST_TESTIMONIALS_URL', plugin_dir_url(__FILE__));
            define('UST_TESTIMONIALS_VERSION', '0.1.0');
            define ( 'UST_TESTIMONIALS_OVERRIDE_PATH_DIR', get_stylesheet_directory() . '/ust-testimonials/' );   

            //define('UST_TESTIMONIAL_PLUGINS_URL', plugins_url( $path.'', $plugin.'') );
            //define('UST_TESTIMONIAL_PLUGINS_BASENAME' , plugin_basename( $file ));
        }
        public function load_custom_archive_template( $tpl ){
            if( current_theme_supports( 'ust-testimonials' ) ){
                if( is_post_type_archive( 'ust-testimonials' ) ){
                    $tpl = $this->get_template_part_location( 'archive-ust-testimonials.php' );
                }
            }
            return $tpl;
        }

        public function load_custom_single_template( $tpl ){
            if( current_theme_supports( 'ust-testimonials' ) ){
                if( is_singular( 'ust-testimonials' ) ){
                    $tpl = $this->get_template_part_location( 'single-ust-testimonials.php' );
                }
            }
            return $tpl;
        }

        public function get_template_part_location( $file ){
            if( file_exists(UST_TESTIMONIALS_OVERRIDE_PATH_DIR . $file ) ){
                $file = UST_TESTIMONIALS_OVERRIDE_PATH_DIR . $file;
            }else{
                $file = UST_TESTIMONIALS_PATH . 'views/templates/' . $file;
            }
            return $file;
        }

        public function load_textdomain(){
            load_plugin_textdomain(
                'ust-testimonials',
                false,
                dirname( plugin_basename( __FILE__ ) ) . '/languages/'
            );
        }
        public static function activation_hook()
        {
            update_option('rewrite_rules', '');

        }
        public static function deactivation_hook()
        {

            flush_rewrite_rules();
        }
        public static function uninstall_hook()
        {

        }
    }
endif;
if (class_exists('Ust_Testimonials')):
    register_activation_hook(__FILE__, ['Ust_Testimonials', 'activation_hook']);
    register_deactivation_hook(__FILE__, ['Ust_Testimonials', 'deactivation_hook']);
    register_uninstall_hook(__FILE__, ['Ust_Testimonials', 'uninstall_hook']);
    $ust_testimonials = new Ust_Testimonials();

endif;