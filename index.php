<?php
include "config.php";
?>
<!DOCTYPE html>
<html>

<head>
	<title>deHasher v<?=ENGINE_VERSION?></title>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<meta name='description' content='Хэширование текста и дехэширование хэша'>
	<meta name='keywords' content='hash,md5,sha1,base64,хэш,md5_decode,sha1_decode,base64_encode,base64_decode'>
	<meta name='author' content='ZiGGi'>
	<link rel='stylesheet' type='text/css' href='style.css'>
	<link rel='shortcut icon' type='image/x-icon' href='images/favicon.ico'>
	<script type="text/javascript" src="jscripts/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="jscripts/scripts.js"></script>
</head>

<body>

	<div id='middle'>
		<div id="middle_api">
			<span id='close'>X</span>
			<table>
				<tr>
					<td valign=top><b>Encode:</b></td>
					<td>
						http://hash.ziggi.org/api.php?type=TYPE&text=TEXT
						<p><b>type</b> - тип хэша, на данный момент доступно: md5, md5(md5()), sha1, base64</p>
						<p><b>text</b> - любой текст</p>
						<p>Результатом выполнения запроса будет вывод хэша текста TEXT методом TYPE в виде html кода</p>
						<br><br>
					</td>
				</tr>
				<tr>
					<td valign=top><b>Decode:</b></td>
					<td>
						http://hash.ziggi.org/api.php?type=TYPE&hash=HASH&uot=0/1
						<p><b>type</b> - тип хэша, на данный момент доступно: md5, md5(md5()), sha1, base64</p>
						<p><b>hash</b> - хэш строка</p>
						<p><b>uot</b> (опционально) - если 1, то будут задействованы внешние базы данных, если 0, то только локальная</p>
						<p>Результатом выполнения запроса будет вывод строки до её хэширования методом TYPE в виде html кода</p>
					</td>
				</tr>
			</table>
			<br>
		</div>
		<div id='middle_table'>
			<table>
				<tr>
					<td>Decode</td>
					<td>Encode</td>
				</tr>
				<tr>
					<td><textarea id='input_hash' autofocus></textarea></td>
					<td><textarea id='input_text'></textarea></td>
				</tr>
				<tr>
					<td>
						<div id='method_hash'>
							<span class='clicked'>md5</span>
							<span>md5(md5())</span>
							<span>sha1</span>
							<span>base64</span>
						</div>
					</td>
					<td >
						<div id='method_text'>
							<span class='clicked'>md5</span>
							<span>md5(md5())</span>
							<span>sha1</span>
							<span>base64</span>
						</div>
					</td>
				</tr>
				<tr>
					<td>Найденые результаты (<span id='found_count'>0</span>)</td>
					<td>Не найденые результаты (<span id='notfound_count'>0</span>)</td>
				</tr>
				<tr>
					<td><textarea id='output_found' readonly></textarea></td>
					<td><textarea id='output_notfound' readonly></textarea></td>
				</tr>
				<tr>
					<td colspan='2'><div id='use_other_db'><link><input type="checkbox" checked>Использовать внешние базы данных</link></div></td>
				</tr>
			</table>
			<center><input type='button' id='result' value='Результат'></center>
		</div>
		<div id='footer'>
			<div id='api'>
				<a>API</a>
			</div>
			<div id='copyright'>
				<div style='float:left'><a href='http://ziggi.org/category/developments/deHasher/' target='_blank'>v<?=ENGINE_VERSION?></a></div>
				<div style='float:right'><a href='http://ziggi.org/' target='_blank'>ZiGGi</a></div>
			</div>
		</div>
	</div>
	
</body>

</html>
