<?php
include "app.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>deHasher v<?=Hasher::$config['version']?></title>
  <meta name="description" content="<?=Hasher::$config['description']?>">
  <meta name="keywords" content="<?=Hasher::$config['keywords']?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
  <link href="css/style.css" rel="stylesheet" media="screen">
</head>

<body>

  <div class="window" id="api_info">
	<span class="close">&times;</span>
	<div class="content">
	  <table>
	    <tr>
		  <td valign="top"><b>Encode:</b></td>
		  <td>
		    http://hash.ziggi.org/api.php?type=TYPE&text=TEXT
		    <p><b>type</b> - type of hash, currently available: md5, md5_md5, sha1, base64</p>
		    <p><b>text</b> - any text</p>
		    <br>
		  </td>
	    </tr>
	    <tr>
		  <td valign="top"><b>Decode:</b></td>
		  <td>
	        http://hash.ziggi.org/api.php?type=TYPE&hash=HASH&uot=0/1
		    <p><b>type</b> - type of hash, currently available: md5, md5_md5, sha1, base64</p>
		    <p><b>hash</b> - hash string</p>
		    <p><b>uot</b> (optional) - Use an external database. 1 - true, 0 - false.</p>
		  </td>
	    </tr>
	    <tr>
		  <td valign="top"><b>Count:</b></td>
		  <td>
		    http://hash.ziggi.org/api.php?type=TYPE&count
		    <p><b>type</b> - type of hash table, currently available: all, md5, md5_md5, sha1</p>
		    <p>Returns the number of elements</p>
		  </td>
	    </tr>
	  </table>
	</div>
  </div>

  <div class="window" id="db_info">
    <span class="close">&times;</span>
    <div class="content">
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

  <div id="info">
    <span>deHasher v<?=Hasher::$config['version']?></span>
    <span><a href="#" data-type="window" data-target="db_info">DB info</a></span>
    <span><a href="#" data-type="window" data-target="api_info">API</a></span>
    <span><a href="https://github.com/ziggi/deHasher" target="_blank">GitHub</a></span>
    <span><a href="http://ziggi.org/" target="_blank">Home</a></span>
  </div>

  <div id="middle">
	<table>
	  <tr>
		<td>
		  <div class="head">Decode</div>
		  <div class="text">
			  <textarea id="input_decode"></textarea>
		  </div>
	    </td>
		<td>
		  <div class="head">Encode</div>
		  <div class="text">
			  <textarea id="input_encode"></textarea>
		  </div>
		</td>
	  </tr>
	  <tr>
		<td>
		  <div id="method_hash">
			<button class="btn active">md5</button>
			<button class="btn">md5_md5</button>
			<button class="btn">sha1</button>
			<button class="btn">base64</button>
		  </div>
	    </td>
		<td>
		  <div id="method_text">
			<button class="btn active">md5</button>
			<button class="btn">md5_md5</button>
			<button class="btn">sha1</button>
			<button class="btn">base64</button>
		  </div>
		</td>
	  </tr>
	  <tr>
		<td>
		  <div class="head">Found (<span id="found_count">0</span>)</div>
		  <div class="text">
			  <textarea id="output_found"></textarea>
		  </div>
	    </td>
		<td>
		  <div class="head">Not found (<span id="notfound_count">0</span>)</div>
		  <div class="text">
			  <textarea id="output_notfound"></textarea>
		  </div>
		</td>
	  </tr>
    </table>
    <div id="use_other_db">
		<label><input type="checkbox" checked>Use an external database</label>
    </div>
    <div id="result">
		<button class="btn">Result</button>
	</div>
  </div>

  <script src="js/jquery-2.0.3.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="js/scripts.js"></script>
</body>
</html>
