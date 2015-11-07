<?php

// get params
$text = isset($_GET['text']) ? $_GET['text'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

// api processing
include_once 'include/dehasher.class.php';

$dehasher = new deHasher($conf['db']['host'], $conf['db']['base'], $conf['db']['user'], $conf['db']['pass']);

$result = $dehasher->getHash($text, $type);

if (is_array($result)) {
	echo json_encode($result);
} else {
	echo $result;
}
