#!/usr/bin/php
<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use Symfony\Component\Console\Application;

$controlCommand = new \Robot\Command\ControlCommand();

chdir( __DIR__ . "/../../" );

$application = new Application( 'robot', '0.0.0' );
$application->add( $controlCommand );
$application->run();