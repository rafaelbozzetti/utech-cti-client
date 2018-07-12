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
$socket = $factory->createClient("$uri", "tcp");


$user = $conf['user']['user'];
$password = $conf['user']['password'];


/* test commands */
$cmd = array('command'=>'login', 'user'=>$user, 'password'=>$password);
$resp = $socket->send_command($cmd);
print_r($resp);


/* make call */
// $cmd = array('command'=>'make_call', 'destination'=>'996538098');
// $resp = $socket->send_command($cmd);
// print_r($resp);


$resp = $socket->logoff();

/* close socket */
$socket->close();
