#!/usr/bin/php
<?php

require_once('PHPUnit/Autoload.php');

class DataTest extends PHPUnit_Framework_TestCase {

  function test() {
    $stack = array();
    $this->assertEquals(0, count($stack));
    // This will throw an exception
    //$this->assertEquals(1, count($stack));
  }
}

$dt = new DataTest();

// Runs silently from command line:
$dt->test();

?>
