<?php

require_once('inc/class.pdowrapper.php');
// require('inc/class.pdohelper.php');
//db
$dbCreds = array(
    'user' => 'root',
    'password' => 'solaR3625',
    'database' => 'sivan',
    'host' => 'localhost',
);
$dbConfig = array(
    "host"=>$dbCreds['host'],
    "dbname"=>$dbCreds['database'],
    "username"=>$dbCreds['user'],
    "password"=>$dbCreds['password']
);

$db = new PdoWrapper($dbConfig);
$db->setErrorLog(true);

?>