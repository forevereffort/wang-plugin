<?php

namespace WJCPlugin;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (! class_exists('WJC_API')) :

    class WJC_API
    {
        var $api_url = 'https://miusage.com/v1/challenge/1/';
        var $expire_time = 3600; // 1 hr
        var $api_call_time_option_key = 'wjc_api_call_time';
        var $api_option_key = 'wjc_api_data';

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

        public function checkExpire()
        {
            $current_time = current_time('timestamp');
            $call_time = get_option($this->api_call_time_option_key);

            if (!$call_time) {
                // first calling time
                add_option($this->api_call_time_option_key, $current_time);

                return true;
            } else if ($current_time - $call_time > $this->expire_time) {
                // check time expire
                update_option($this->api_call_time_option_key, $current_time);

                return true;
            }

            return false;
        }

        public function bustCache()
        {
            $current_time = current_time('timestamp');
    
            update_option($this->api_call_time_option_key, $current_time);

            $this->getData(true);
        }

        public function bustData()
        {
            $current_time = current_time('timestamp');
    
            update_option($this->api_call_time_option_key, $current_time);

            return $this->getData(true);
        }
        
        public function getData($forceRemote = false)
        {
            if ($this->checkExpire() || $forceRemote) {
                $res = wp_remote_get($this->api_url);
                update_option($this->api_option_key, wp_remote_retrieve_body($res));
            }

            $data = get_option($this->api_option_key);

            return $data;
        }
    }

endif; // class_exists check
