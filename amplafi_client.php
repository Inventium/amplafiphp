<?php

if (file_exists('firephp/lib/FirePHP/fb.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)
}

/**
 * Use http://www.firephp.org/ to help debug
 */
class AmplafiApiClient {
    var $amplafi_uri = 'http://amplafi.net/';
    var $content_type = 'application/json; charset=utf-8';
    var $version = '0.1';
    var $user_agent = 'AmplafiPhpClient/'. $version;
    var $admin = 0;

    var $request = null;
    var $response = null;

    var $requests = array();
    var $responses = array();

    var $errors = array();

    function AmplafiApiClient( ) {
        $this->partnerGUID = $partnerGUID;
        $this->userCode = $userCode;
    }

    function send_request() {
        $this->request_xml  = "";

        if ( function_exists( 'wp_remote_post' ) ) {
            $response = wp_remote_post( $this->amplafi_uri, array(
                'headers' => array( 'Content-Type' => $this->content_type, 'Content-Length' => strlen( $this->request ) ),
                'user-agent' => $this->version,
                'body' => $this->request
            ) );
            if ( !$response || is_wp_error( $response ) ) {
                $errors[-1] = "Can't connect";
                return false;
            }
            $this->response_xml = wp_remote_retrieve_body( $response );
        } else {
            $parsed = parse_url( $this->polldaddy_url );

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

            if ( function_exists( 'stream_set_timeout' ) )
                stream_set_timeout( $fp, 3 );

            if ( !isset( $parsed['path']) || !$path = $parsed['path'] . ( isset($parsed['query']) ? '?' . $parsed['query'] : '' ) )
                $path = '/';

            $request  = "POST $path HTTP/1.0\r\n";
            $request .= "Host: {$parsed['host']}\r\n";
            $request .= "User-agent: ". $this->user_agent . "\r\n";
            $request .= "Content-Type: ". $content_type . "\r\n";
            $request .= 'Content-Length: ' . strlen( $this->request_xml ) . "\r\n";

            fwrite( $fp, "$request\r\n$this->request_xml" );

            $response = '';
            while ( !feof( $fp ) )
                $response .= fread( $fp, 4096 );
            fclose( $fp );


            if ( !$response ) {
                $errors[-2] = 'No Data';
            }

            list($headers, $this->response_xml) = explode( "\r\n\r\n", $response, 2 );
        }

        $this->responses[] = $this->response_xml;

        $parser = new PollDaddy_XML_Parser( $this->response_xml );
        $this->response =& $parser->objects[0];
        if ( isset( $this->response->errors->error ) ) {
            if ( !is_array( $this->response->errors->error ) )
                $this->response->errors->error = array( $this->response->errors->error );
            foreach ( $this->response->errors->error as $error )
                $this->errors[$error->_id] = $error->___content;
        }
    }
    function reset() {
        $this->request = null;
        $this->response = null;
        $this->request_xml = '';
        $this->response_xml = '';
        $this->errors = array();
    }



}

?>

