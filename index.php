<?php

if (file_exists('firephp/lib/FirePHPCore/FirePHP.class.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)

  echo "firephp";
}

require_once('basic_client.php');


?>

<h1>Test code for Amplafi PHP client</h1>

<?php


include('fsockopen_test.php');

//FB::log('Log message');

$bc->write_me();

?>

<p>Below Amplafi results...</p>

