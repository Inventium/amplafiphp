#/usr/bin/php

#<?php
#class api_client {
     
   
$hostname = 'http://amplafi.net/apiv1/';

/*
$fp = fsockopen(
        $parsed['host'],
        $parsed['scheme'] == 'ssl' || $parsed['scheme'] == 'https' && extension_loaded('openssl') ? 443 : 80,
        $err_num,
        $err_str,
        3
      );
*/
$fp = fsockopen($hostame,80);


      if ( !$fp ) {
        $errors[-1] = "Can't connect";
        return false;
      }


#?>

