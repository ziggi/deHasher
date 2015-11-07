<?php

// get params
$is_count = isset($_GET['count']);
$is_algo = isset($_GET['algo']);

$type = isset($_GET['type']) ? $_GET['type'] : null;

// api processing
include_once 'include/dehasher.class.php';

$dehasher = new deHasher($conf['db']['host'], $conf['db']['base'], $conf['db']['user'], $conf['db']['pass']);

if ($is_count) {
	echo $dehasher->getCount($type);
} else if ($is_algo) {
	if (!is_null($type)) {
		echo (int)$dehasher->isSupportedType($type);
	} else {
		echo json_encode($dehasher->getTypeList());
	}
}
