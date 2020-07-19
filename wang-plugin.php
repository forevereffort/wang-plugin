<?php

/*
Plugin name: Wang Jin Che Test Plugin
Plugin URI: http://PLUGIN_URI.com/
Description: Wang Jin Che Test Plugin
Author: Wang Jin Che
Author URI: http://AUTHOR_URI.com
Version: 1.0.0
Text Domain: wjc
*/

namespace WJCPlugin;

use WJCPlugin\WJC_API;

require_once __DIR__ . '/vendor/autoload.php';

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (! class_exists('WJC')) :
    class WJC
    {

        /** @var string The plugin version number. */
        var $version = '1.0.0';

        /**
         * __construct
         *
         * A dummy constructor to ensure WJC is only setup once.
         *
         * @date    07/19/2020
         * @since   1.0.0
         *
         * @param   void
         * @return  void
         */
        function __construct()
        {
            // Do nothing.
        }
    
        /**
         * initialize
         *
         * Sets up the WJC plugin.
         *
         * @date    07/19/2020
         * @since   1.0.0
         *
         * @param   void
         * @return  void
         */
        function initialize()
        {
        
            // Define constants.
            $this->define('WJC', true);
            $this->define('WJC_PATH', plugin_dir_path(__FILE__));
            $this->define('WJC_BASENAME', plugin_basename(__FILE__));
            $this->define('WJC_VERSION', $this->version);
        
            // Define settings.
            $this->settings = array(
                'name'                      => __('Wang Jin Che Plugin', 'wjc'),
                'slug'                      => dirname(WJC_BASENAME),
                'version'                   => WJC_VERSION,
                'basename'                  => WJC_BASENAME,
                'path'                      => WJC_PATH,
                'file'                      => __FILE__,
                'url'                       => plugin_dir_url(__FILE__),
            );
        
            // Add actions.
            add_action( 'wp_enqueue_scripts', array( $this, 'wjc_scripts') );

            add_action( 'wp_ajax_wjc_ajax_func', array( $this, 'wjc_ajax_func' ) );
            add_action( 'wp_ajax_nopriv_wjc_ajax_func', array( $this, 'wjc_ajax_func' ) );

            // Add shortcodes
            add_shortcode('wjc', [$this, 'wjc_shortcode_func']);
        }
    
        /**
         * define
         *
         * Defines a constant if doesnt already exist.
         *
         * @date    3/5/17
         * @since   5.5.13
         *
         * @param   string $name The constant name.
         * @param   mixed $value The constant value.
         * @return  void
         */
        function define($name, $value = true)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        function wjc_shortcode_func()
        {
            return '<div class="wjc-data-table" data-nonce="' . wp_create_nonce("wjc_ajax_nonce") . '"></div>';
        }

        function wjc_ajax_func(){
            if ( !wp_verify_nonce( $_REQUEST['nonce'], "wjc_ajax_nonce")) {
                exit("No naughty business please");
            }
    
            $wjc_api = new WJC_API();
        
            $result = $wjc_api->getData();
            
            echo $result;
        
            die();
        }

        function wjc_scripts(){
            wp_enqueue_script('wjc-js', $this->settings['url']  . 'dist/assets/main.js' , array('jquery'), $this->version, true);
            wp_localize_script( 'wjc-js', 'wjcWpAjax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

            wp_enqueue_style('wjc-css', $this->settings['url']  . 'dist/assets/main.css', array(), $this->version, 'all');
        }
    }

    /*
    * wjc
    *
    * The main function responsible for returning the one true wjc Instance to functions everywhere.
    * Use this function like you would a global variable, except without needing to declare the global.
    *
    * Example: <?php $wjc = wjc(); ?>
    *
    * @date 07/19/2020
    * @since    1.0.0
    *
    * @param    void
    * @return   WJC
    */
    function wjc()
    {
        global $wjc;
    
        // Instantiate only once.
        if (!isset($wjc)) {
            $wjc = new WJC();
            $wjc->initialize();
        }
        return $wjc;
    }

    // Instantiate.
    wjc();
endif; // class_exists check
