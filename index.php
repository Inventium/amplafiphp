<?php

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
    fclose($fp);
}

$ap_client = new AmplafiClient();

?>

