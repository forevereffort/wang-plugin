<?php

namespace WJCPlugin;

use WJCPlugin\WJC_CLI;
use WJCPlugin\WJC_API;
use WP_CLI;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (! class_exists('WJC_CLI')) :

    class WJC_CLI
    {
        /*
        *  __construct
        *
        *  This function will initialize CLI
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
            // Add new CLI to bust cache
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::add_command('wjc-bust-cache', array( $this, 'wjc_bust_cache'));
            }
        }

        function wjc_bust_cache($args = array(), $assoc_args = array())
        {
            $wjc_api = new WJC_API();
            $wjc_api->bustCache();

            WP_CLI::success('bust cache successfully');
        }
    }

endif; // class_exists check
