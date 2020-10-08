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
            add_action('admin_menu', [$this, 'admin_menu']);
            add_action( 'admin_post_nds_form_response', [$this, 'the_form_response']);
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
            add_submenu_page(
                'tools.php',
                __("Wang Jin Che Table", 'wjc'),
                __("Wang Jin Che Table", 'wjc'),
                'manage_options',
                'wjc-table',
                array( $this, 'create_admin_page' ),
            );
        }


        function the_form_response()
        {
            if( isset( $_POST['nds_prune_cache_nonce'] ) && wp_verify_nonce( $_POST['nds_prune_cache_nonce'], 'nds_prune_cache_form_nonce') ) {        
                // redirect the user to the appropriate page
                $wjc_api = new WJC_API();
                $wjc_api->prune_cache(true);

                wp_redirect(add_query_arg('fetch', 'done', $_SERVER['HTTP_REFERER']));
                exit();
            } else {
                wp_die(
                    __( 'Invalid nonce specified', 'wjc'),
                    __( 'Error', 'wjc'),
                    [
                        'response' 	=> 403,
                    ]
                );
            }
        }

        function create_admin_page()
        {
            $wjc_api = new WJC_API();        
            $data = $wjc_api->get_data();

            // Generate a custom nonce value.
            $nds_add_meta_nonce = wp_create_nonce( 'nds_prune_cache_form_nonce' );
            ?>
            <div class="wrap">
                <h1>Wang Jin Che Table</h1>
                <?php
                    if( isset($_GET['fetch']) && $_GET['fetch'] === 'done' ){
                ?>
                <div class="notice notice-success is-dismissible"> 
                    <p><strong>Pruned cache and fetched fresh data</strong></p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
                <?php
                    }
                ?>
                <?php echo $wjc_api->render_table($data); ?>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                    <input type="hidden" name="action" value="nds_form_response">
                    <input type="hidden" name="nds_prune_cache_nonce" value="<?php echo $nds_add_meta_nonce ?>" />
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Prune Cache"></p>
                </form>
            </div>
            <?php
        }
    }

endif; // class_exists check
