<?php

if (file_exists('firephp/lib/FirePHP/fb.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)
}

include('php_develop.php');


/**
 * Use http://www.firephp.org/ to help debug
 */
class AmplafiApiClient extends PHPDevelop {
  
  
    var $amplafi_uri = 'http://amplafi.net/apiv1/';
    var $content_type = 'application/json; charset=utf-8';
    var $flowTypeName;
    var $version = '0.1';
    var $user_agent = 'AmplafiPhpClient/0.1'; //. $this->version;
    var $admin = 0;

    var $request = null;
    var $response = null;

    var $requests = array();
    var $responses = array();

    var $errors = array();

    function AmplafiApiClient($flowTypeName) {
        $this->flowTypeName = $flowTypeName;
    }

    
//    function myecho($message) {
//      echo $message;
//    }



    function send_request() {
      
        $response_json = '';
        $request_json = '';
        
        if ( function_exists( 'wp_remote_post' ) ) {
            $response = wp_remote_post( $this->amplafi_uri, array(
                'headers' => array( 'Content-Type' => $this->content_type, 'Content-Length' => strlen( $this->request ) ),
                'user-agent' => $this->user_agent,
                'body' => $this->request
            ) );
            if ( !$response || is_wp_error( $response ) ) {
                $errors[-1] = "Can't connect";
                return false;
            }
            $response_json = wp_remote_retrieve_body( $response );
        } else {
            $parsed = parse_url( $this->amplafi_url );

            if ( !isset( $parsed['host'] ) && !isset( $parsed['scheme'] ) ) {
                $errors[-1] = 'Invalid API URL';
                return false;
            }

            $fp = fsockopen(
                $parsed['host'],
                $parsed['scheme'] == 'ssl' || $parsed['scheme'] == 'https' && extension_loaded('openssl') ? 443 : 80,
                $err_num,
                $err_str,
                3);

            if ( !$fp ) {
                $errors[-1] = "Can't connect";
                return false;
            }

            if ( function_exists( 'stream_set_timeout' ) ){
                stream_set_timeout( $fp, 3 );
            }
            if ( !isset( $parsed['path']) || !$path = $parsed['path'] . ( isset($parsed['query']) ? '?' . $parsed['query'] : '' ) ) {
                $path = '/';
            }
            
            $request  = "POST $path HTTP/1.0\r\n";
            $request .= "Host: {$parsed['host']}\r\n";
            $request .= "User-agent: ". $this->user_agent . "\r\n";
            $request .= "Content-Type: ". $this->content_type . "\r\n";
            $request .= 'Content-Length: ' . strlen( $request_json ) . "\r\n";

            fwrite( $fp, "$request\r\n$this->request" );

            $response = '';
            while ( !feof( $fp ) )
                $response .= fread( $fp, 4096 );
            fclose( $fp );

            if ( !$response ) {
                $errors[-2] = 'No Data';
            }

            list($headers, $response_json) = explode( "\r\n\r\n", $response, 2 );
        }

        $this->responses[] = $response_json;

    }
    function reset() {
        $this->request = null;
        $this->response = null;
        $this->errors = array();
    }


}

?>

