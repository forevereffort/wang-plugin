<?php

namespace WJCPlugin;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (! class_exists('WJC_API')) :

    class WJC_API
    {
        var $api_url = 'https://dummy.restapiexample.com/api/v1/employees/';
        var $expire_time = 43200; // 12 hr
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

        public function check_expire()
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

        public function prune_cache()
        {
            $current_time = current_time('timestamp');
    
            update_option($this->api_call_time_option_key, $current_time);

            $this->get_data(true);
        }
        
        public function get_data($forceRemote = false)
        {
            // first time, by API error, result maybe can be empty. for this case, we need to ignore cache
            $data = get_option($this->api_option_key);
            if( empty($data) ){
                $forceRemote = true;
            }

            if ($this->check_expire() || $forceRemote) {
                $response = wp_remote_get($this->api_url);

                // process API exception
                if( is_wp_error( $response ) ) {
                    // echo 'calling error!!!';
                    // please add exception code what you want
                } else {
                    $response_code = wp_remote_retrieve_response_code( $response );

                    if( $response_code === 200 ){
                        $responseBody = wp_remote_retrieve_body( $response );
                        $result = json_decode( $responseBody, true );

                        if ( is_array( $result ) && ! is_wp_error( $result ) ) {
                            if( $result['status'] == 'success' ){
                                // everything is okay. we can trust this result.
                                // echo 'fetched';
                                update_option($this->api_option_key, $result['data']);
                            } else {
                                // echo 'API internal error !!!';
                                // please add exception code what you want
                            }
                        } else {
                            // echo 'response format error !!!';
                            // please add exception code what you want
                        }
                    } else {
                        // $response_message = wp_remote_retrieve_response_message( $response );
                        // echo 'response error!!! - ' . $response_code . $response_message;
                        // please add exception code what you want
                    }
                }

            }

            $data = get_option($this->api_option_key);

            return $data;
        }

        public function render_table($data)
        {
            $html = '';

            if( empty($data) ){
                $html = 'by some reason, getting data was failed. you need to refresh page';
            } else {

                $html .= '<table>';
                $html .= '<tr>';
                $html .= '<td>id</td>';
                $html .= '<td>employee_name</td>';
                $html .= '<td>employee_salary</td>';
                $html .= '<td>employee_age</td>';
                $html .= '<td>profile_image</td>';
                $html .= '</tr>';

                foreach($data as $item){
                    $html .= '<tr>';    
                    $html .= '<td>' . sanitize_text_field($item['id']) . '</td>';    
                    $html .= '<td>' . sanitize_text_field($item['employee_name']) . '</td>';    
                    $html .= '<td>' . sanitize_text_field($item['employee_salary']) . '</td>';    
                    $html .= '<td>' . sanitize_text_field($item['employee_age']) . '</td>';    
                    $html .= '<td>' . sanitize_text_field($item['profile_image']) . '</td>';    
                    $html .= '</tr>';    
                }

                $html .= '</table>';
            }

            return $html;
        }
    }

endif; // class_exists check
