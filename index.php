<?php
include "config.php";
?>
<!DOCTYPE html>
<html>

<head>
	<title>deHasher v<?=ENGINE_VERSION?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Хэширование текста и дехэширование хэша">
	<meta name="keywords" content="hash,md5,sha1,base64,хэш,md5_decode,sha1_decode,base64_encode,base64_decode">
	<meta name="author" content="ZiGGi">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="zishell/style.css">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	<script type="text/javascript" src="jscripts/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="jscripts/scripts.js"></script>
	<script type="text/javascript" src="zishell/scripts.js"></script>
</head>

<body>
	<div id="zishell">
		<div class="menu">
			<div style="float:left"></div>
			<div style="float:right">
				<span class="button db_info">DB info</span>
				<span class="button api_info">API</span>
				<span>
					<a href="https://github.com/ZiGGi/deHasher" target="_blank">v<?=ENGINE_VERSION?></a>
					&nbsp;
					<a href="http://ziggi.org/" target="_blank">ZiGGi</a>
				</span>
			</div>
		</div>
		<div class="window" id="api_info">
			<span class="close">x</span>
			<table>
				<tr>
					<td valign=top><b>Encode:</b></td>
					<td>
						http://hash.ziggi.org/api.php?type=TYPE&text=TEXT
						<p><b>type</b> - type of hash, currently available: md5, md5_md5, sha1, base64</p>
						<p><b>text</b> - any text</p>
						<br>
					</td>
				</tr>
				<tr>
					<td valign=top><b>Decode:</b></td>
					<td>
						http://hash.ziggi.org/api.php?type=TYPE&hash=HASH&uot=0/1
						<p><b>type</b> - type of hash, currently available: md5, md5_md5, sha1, base64</p>
						<p><b>hash</b> - hash string</p>
						<p><b>uot</b> (optional) - Use an external database. 1 - true, 0 - false.</p>
					</td>
				</tr>
				<tr>
					<td valign=top><b>Count:</b></td>
					<td>
						http://hash.ziggi.org/api.php?type=TYPE&count
						<p><b>type</b> - type of hash table, currently available: all, md5, md5_md5, sha1</p>
						<p>Returns the number of elements</p>
					</td>
				</tr>
			</table>
		</div>
		<div class="window" id="db_info">
			<span class="close">x</span>
			<table>
				<tr>
					<td colspan="2">Entries in DB</td>
				</tr>
				<tr>
					<td>Total</td>
					<td><div id="count_all" class="hash_count"></div></td>
				</tr>
				<tr>
					<td>md5</td>
					<td><div id="count_md5" class="hash_count"></div></td>
				</tr>
				<tr>
					<td>md5_md5</td>
					<td><div id="count_md5_md5" class="hash_count"></div></td>
				</tr>
				<tr>
					<td>sha1</td>
					<td><div id="count_sha1" class="hash_count"></div></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="middle">
		<div id="middle_table">
			<table>
				<tr>
					<td>Decode</td>
					<td>Encode</td>
				</tr>
				<tr>
					<td><textarea id="input_hash" autofocus></textarea></td>
					<td><textarea id="input_text"></textarea></td>
				</tr>
				<tr>
					<td>
						<div id="method_hash">
							<span class="clicked zishell">md5</span>
							<span class="zishell">md5_md5</span>
							<span class="zishell">sha1</span>
							<span class="zishell">base64</span>
						</div>
					</td>
					<td>
						<div id="method_text">
							<span class="clicked zishell">md5</span>
							<span class="zishell">md5_md5</span>
							<span class="zishell">sha1</span>
							<span class="zishell">base64</span>
						</div>
					</td>
				</tr>
				<tr>
					<td>Found (<span id="found_count">0</span>)</td>
					<td>Not found (<span id="notfound_count">0</span>)</td>
				</tr>
				<tr>
					<td><textarea id="output_found" readonly></textarea></td>
					<td><textarea id="output_notfound" readonly></textarea></td>
				</tr>
				<tr>
					<td colspan="2"><div id="use_other_db"><link><input type="checkbox" checked>Use an external database</link></div></td>
				</tr>
			</table>
			<center><input type="button" id="result" class='zishell' value="Result"></center>
		</div>
	</div>
	
</body>

</html>
