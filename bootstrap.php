<?php

$conf = include 'config.php';

if (empty($conf['uri_param'])) {
	return;
}

// get api method
$matches = array();
$is_matched = preg_match('/api\/([a-z]+)\.get/', $conf['uri_param'], $matches);
if ($is_matched === false) {
	return;
}

if (!isset($matches[1])) {
	return;
}

$api_action = $matches[1];

// include file
$include_file = 'api/' . $api_action . '.php';

if (is_file($include_file)) {
	include_once $include_file;
}

exit;
