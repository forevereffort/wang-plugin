<?php

namespace WJCPlugin;

use WJCPlugin\WJC_API;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (! class_exists('WJC_Admin')) :

    class WJC_Admin
    {
        /*
        *  __construct
        *
        *  This function will initialize Admin
        *
        *  @type    function
        *  @date    07/19/2020
        *  @since   1.0.0
        *
        *  @param   n/a
        *  @return  n/a
        */

        public function __construct()
        {
            add_action('admin_menu', array($this, 'admin_menu'));

            add_action('wp_ajax_wjc_refresh_ajax_func', array( $this, 'wjc_refresh_ajax_func' ));
            add_action('wp_ajax_nopriv_wjc_refresh_ajax_func', array( $this, 'wjc_refresh_ajax_func' ));
        }

        /*
        *  admin_menu
        *
        *  This function will add the WJC menu item to the WP admin
        *
        *  @type    action (admin_menu)
        *  @date    23/12/2019
        *  @since   1.0.0
        *
        *  @param   n/a
        *  @return  n/a
        */
        
        function admin_menu()
        {
            // vars
            $parent_slug = 'wjc-table';
            $cap = 'manage_options';
            
            
            // add parent
            add_menu_page(
                __("Wang Jin Che Table", 'wjc'),
                __("Wang Jin Che Table", 'wjc'),
                $cap,
                $parent_slug,
                array( $this, 'create_admin_page' ),
                'dashicons-admin-site'
            );
        }

        function create_admin_page()
        {
            ?>
            <div class="wrap">
                <h1>Wang Jin Che Table</h1>
                <div class="wjc-data-table" data-nonce="<?php echo wp_create_nonce("wjc_ajax_nonce"); ?>"></div>
                <input id="wjc-refresh-button" class="button button-primary" type="button" value="Refresh" data-nonce="<?php echo wp_create_nonce("wjc_refresh_ajax_nonce"); ?>" />
                <div class="wjc-ajax-mask hide"></div>
            </div>
            <?php
        }

        function wjc_refresh_ajax_func()
        {
            if (!wp_verify_nonce($_REQUEST['nonce'], "wjc_refresh_ajax_nonce")) {
                exit("No naughty business please");
            }
    
            $wjc_api = new WJC_API();
            $result = $wjc_api->bustData();
            
            echo $result;
        
            die();
        }
    }

endif; // class_exists check
