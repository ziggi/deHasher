<?php

// get params
$hash = isset($_GET['hash']) ? $_GET['hash'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;
$include_external_db = isset($_GET['include_external_db']) ? true : false;

// api processing
include_once 'include/dehasher.class.php';

$dehasher = new deHasher($conf['db']['host'], $conf['db']['base'], $conf['db']['user'], $conf['db']['pass']);

echo $dehasher->getText($hash, $type, $include_external_db);
