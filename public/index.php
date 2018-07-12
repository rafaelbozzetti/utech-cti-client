<?php

chdir(__DIR__ . '/..');

require 'vendor/autoload.php';


/* load extenal settings*/
$conf = parse_ini_file('conf/config.ini', true);


/* This is a test file */
$protocol = $conf['socket']['protocol'];
$server = $conf['socket']['server'];
$port = $conf['socket']['port'];
$uri = "$protocol://$server:$port";


/* connection */
$factory = new \Utech\Cti\Factory();
$socket = $factory->createClient("$uri");


$user = $conf['user']['user'];
$password = $conf['user']['password'];


/* test commands */
$resp = $socket->login($user, $password);
print_r($resp);

$socket->close();
