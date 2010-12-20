<?php

if (file_exists('firephp/lib/FirePHP/fb.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)

  echo "firephp";
}

require_once('basic_client.php');


?>

<h1>Test code for Amplafi PHP client</h1>

<?php

//$fp = fsockopen("www.example.com", 80, $errno, $errstr, 30);
$fp = fsockopen("amplafi.net", 80, $errno, $errstr, 30);


if (!$fp) {
    echo "$errstr ($errno)<br />\n";
    
} else {
  
    $out = "GET / HTTP/1.1\r\n";
    $out .= "Host: www.example.com\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    while (!feof($fp)) {
        echo fgets($fp, 128);
    }
    
    echo "\nfoobar";

$bc = new BasicClient();
$bc->write_me();
    
    fclose($fp);


}


FB::log('Log message');

$bc->write_me();

?>

<p>Below Amplafi results...</p>