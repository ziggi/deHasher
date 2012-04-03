<?php

include "config.php";

if (!mysql_connect(MySQL_HOSTNAME,MySQL_USER,MySQL_PASSWORD))
{
	echo "MySQL >> Not connected";
	exit;
}
mysql_select_db(MySQL_DB);
mysql_query("SET NAMES utf8");


$type = "md5";
if (isset($_GET['type']))
{
	$type = filter_params($_GET['type']);
}
if (isset($_GET['count']))
{
	echo get_hash_count($type);
}
else if (isset($_GET['text']))
{
	$text = urldecode(filter_params($_GET['text']));
	$text_hash = "";
	switch ($type)
	{
		case "md5":
			$text_hash = md5($text);
			break;
		
		case "md5_md5":
			$text_hash = md5(md5($text));
			break;
		
		case "sha1":
			$text_hash = sha1($text);
			break;
		
		case "base64":
			echo base64_encode($text);
			exit;
		
		default:
			$text_hash = md5($text);
			break;
	}
	
	$result = mysql_query("SELECT `Hash` FROM `deHasher_$type` WHERE `Text`='$text'");
	if (mysql_num_rows($result) == 0)
	{
		mysql_query("INSERT INTO `deHasher_$type` (`Hash`,`Text`) VALUES ('$text_hash','$text')");
	}
	echo $text_hash;
}
else if (isset($_GET['hash']))
{
	$hash = filter_params($_GET['hash']);
	$result_print = "";
	switch ($type)
	{
		case "md5":
			if (!preg_match('/^[a-f0-9]{32}$/i',$hash)) $result_print = '';
			break;
		
		case "md5_md5":
			if (!preg_match('/^[a-f0-9]{32}$/i',$hash)) $result_print = '';
			break;
		
		case "sha1":
			if (!preg_match('/^[a-f0-9]{40}$/i',$hash)) $result_print = '';
			break;
		
		case "base64":
			echo urldecode(base64_decode($hash));
			exit;
		
		default:
			if (!preg_match('/^[a-f0-9]{32}$/i',$hash)) $result_print = '';
			break;
	}
	
	$result = mysql_query("SELECT `Text` FROM `deHasher_$type` WHERE `Hash`='$hash'");
	$text = null;
	if (mysql_num_rows($result) != 0)
	{
		$text = mysql_result($result,0);
	}
	if (empty($text))
	{
		// check other databases
		$uot = isset($_GET['uot']) ? $_GET['uot'] : 0;
		if ($uot == 1)
		{
			$content = @file_get_contents('http://md5.darkbyte.ru/api.php?q='.$hash);
			if (!$content)
			{
				$result_print = '';
			}
			else
			{
				// check on valid
				if (md5($content) == strtolower($hash))
				{
					$result_print = $content;
					mysql_query("INSERT INTO `deHasher_$type` (`Hash`,`Text`) VALUES ('$hash','$content')");
				}
				else // found, but not valid
				{
					$result_print = '';
				}
			}
		}
		else
		{
			$result_print = '';
		}
	}
	else
	{
		$result_print = urldecode($text);
	}
	echo $result_print;
}

function get_hash_count($type)
{
	if ($type == "all")
	{
		return get_hash_count("md5")+get_hash_count("md5_md5")+get_hash_count("sha1");
	}
	$result = mysql_query("SELECT COUNT(*) FROM `deHasher_$type`");
	return mysql_result($result,0);
}

function filter_params($param)
{
	return mysql_real_escape_string($param);
}

?>
