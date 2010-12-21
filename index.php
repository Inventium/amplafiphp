<?php

if (file_exists('firephp/lib/FirePHPCore/FirePHP.class.php')) {
  require_once('firephp/lib/FirePHPCore/FirePHP.class.php'); // (object oriented API)

  echo "firephp\n";
}

if (file_exists('amplafi_client.php')) {
  require_once('amplafi_client.php'); 

  echo "Loaded Amplafi client...\n";
}


require_once('basic_client.php');


?>

<h1>Test code for Amplafi PHP client</h1>

<?php


include('fsockopen_test.php');


//FB::log('Log message');

$ac = new AmplafiApiClient();

$ac->myecho("<p>foo</p>");

?>

<script src="https://gist.github.com/737497.js?file=ruby_2d_array.rb"></script>

<p>Below Amplafi results...</p>

<?php

?>