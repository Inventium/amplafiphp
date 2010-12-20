<?php

if (file_exists('firephp/lib/FirePHP/fb.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)
}

/**
 * Use http://www.firephp.org/ to help debug
 */
class api_client {
    var $amplafi_uri = 'http://amplafi.net/';
    var $partnerGUID;
    var $userCode;
    var $admin = 0;
    var $version = '1.0';

    var $request = null;
    var $response = null;
    var $request_xml = '';
    var $response_xml = '';

    var $requests = array();
    var $responses = array();

    var $errors = array();

    function api_client( $partnerGUID = '', $userCode = null ) {
        $this->partnerGUID = $partnerGUID;
        $this->userCode = $userCode;
    }

    function send_request() {
        $this->request_xml  = "";

        if ( function_exists( 'wp_remote_post' ) ) {
            $response = wp_remote_post( $this->polldaddy_url, array(
                'headers' => array( 'Content-Type' => 'text/xml; charset=utf-8', 'Content-Length' => strlen( $this-
>request_xml ) ),
                'user-agent' => 'PollDaddy PHP Client/0.1',
                'body' => $this->request_xml
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
                $parsed['scheme'] == 'ssl' || $parsed['scheme'] == 'https' && extension_loaded('openssl') ? 443 : 8
0,
                $err_num,
                $err_str,
                3            );

            if ( !$fp ) {
                $errors[-1] = "Can't connect";
                return false;
            }

            if ( function_exists( 'stream_set_timeout' ) )
                stream_set_timeout( $fp, 3 );

            if ( !isset( $parsed['path']) || !$path = $parsed['path'] . ( isset($parsed['query']) ? '?' . $parsed['quer
y'] : '' ) )
                $path = '/';

            $request  = "POST $path HTTP/1.0\r\n";
            $request .= "Host: {$parsed['host']}\r\n";
            $request .= "User-agent: PollDaddy PHP Client/0.1\r\n";
            $request .= "Content-Type: text/xml; charset=utf-8\r\n";
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

