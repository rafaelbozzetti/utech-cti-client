<?php

chdir(__DIR__ . '/..');

require 'vendor/autoload.php';

$protocol = 'tcp';
$url = '127.0.0.1';
$port = '8000';
$uri = "$protocol://$url:$port";

$user = '2000';
$password = '12345';

$factory = new \Utech\Cti\Factory();
$socket = $factory->createClient("$uri");

$resp = $socket->login($user, $password);

$socket->close();