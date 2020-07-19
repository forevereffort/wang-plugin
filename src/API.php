<?php

namespace WJCPlugin;

if (! class_exists('WJC_API')) :

    class WJC_API
    {
        var $api_url = 'https://miusage.com/v1/challenge/1/';

        /*
        *  __construct
        *
        *  This function will initialize API
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
            
        }
        public function getData(){
            $res = wp_remote_get( $this->api_url );
            return wp_remote_retrieve_body($res);
        }
    }

endif; // class_exists check
