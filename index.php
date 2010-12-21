#!/usr/bin/php
<?php

if (file_exists('firephp/lib/FirePHPCore/FirePHP.class.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)

  echo "firephp\n";
}

if (file_exists('amplafi_client.php')) {
  require_once('amplafi_client.php'); 

  echo "Loaded Amplafi client...\n";
}


//require_once('basic_client.php');


?>

<h1>Test code for Amplafi PHP client</h1>

<?php


//include('fsockopen_test.php');


//FB::log('Log message');

$ac = new AmplafiApiClient();

$ac->myecho("<p style=\"color:red;\">From PHPDevelop called from index.php.</p>");


//$ac->dummy_api_request();
//$ac->send_request('CreateAlert', array('fsRenderResult' => 'a body', 'messageHeadline' => 'a headline' ));
echo $ac->describe('CreateAlert');

// Not working, var out of scope probably.
//$ac->myecho($ac->$amplafi_uri);

?>
<p>Below Amplafi results...</p>

<?php

?>