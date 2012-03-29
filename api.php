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

if (isset($_GET['text']))
{
	$text = urlencode(filter_params($_GET['text']));
	$text_hash = "";
	switch ($type)
	{
		case "md5":
		{
			$text_hash = md5($text);
			break;
		}
		case "md5(md5())":
		{
			$text_hash = md5(md5($text));
			break;
		}
		case "sha1":
		{
			$text_hash = sha1($text);
			break;
		}
		case "base64":
		{
			echo base64_encode($text);
			exit;
		}
		default:
		{
			$text_hash = md5($text);
			break;
		}
	}
	
	$result = mysql_query("SELECT * FROM `deHasher` WHERE `Text`='$text' AND `Type`='$type'");
	if (mysql_fetch_row($result) == false)
	{
		mysql_query("INSERT INTO `deHasher` (`Type`,`Hash`,`Text`) VALUES ('$type','$text_hash','$text')");
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
		{
			if(!preg_match('/^[a-f0-9]{32}$/i',$hash)) $result_print = '';
			break;
		}
		case "md5(md5())":
		{
			if(!preg_match('/^[a-f0-9]{32}$/i',$hash)) $result_print = '';
			break;
		}
		case "sha1":
		{
			break;
		}
		case "base64":
		{
			echo urldecode(base64_decode($hash));
			exit;
		}
		default:
		{
			if(!preg_match('/^[a-f0-9]{32}$/i',$hash)) $result_print = '';
			break;
		}
	}
	
	$result = mysql_query("SELECT * FROM `deHasher` WHERE `Hash`='$hash' AND `Type`='$type'");
	$array = mysql_fetch_assoc($result);
	if (empty($array['ID']))
	{
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
				//found, but not valid
				if (md5($content) != strtolower($hash))
				{
					$result_print = '';
				}
				else
				{
					$result_print = $content;
					mysql_query("INSERT INTO `deHasher` (`Type`,`Hash`,`Text`) VALUES ('$type','$hash','$content')");
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
		if (empty($array['Text']))
		{
			$result_print = "\0";
		}
		else
		{
			$result_print = urldecode($array['Text']);
		}
	}
	echo $result_print;
}

function filter_params($param)
{
	return mysql_real_escape_string($param);
}

?>
