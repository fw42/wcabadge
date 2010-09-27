<? include "events.php" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
 <title>WCA Badge</title> 
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 <meta name="generator" content="Vi Improved, http://www.vim.org/" /> 
 <meta content="Florian Weingarten" name="author" /> 
 <meta http-equiv="content-language" content="en" /> 
 <meta name="robots" content="index" /> 
 <meta name="revisit-after" content="5 days" /> 
 <meta name="keywords" content="" /> 
 <style type="text/css">
 body {
  font-family: Tahoma, Verdana, sans-serif;
  font-size: 10pt;
  background: #f5f5f5;
 }
 img {
  border: 0px;
 }
 td,th {
  border: 1px dashed silver;
 }
 table {
  border-collapse: collapse;
 }
 </style>
</head>

<body>

<h1>WCA Badge</h1>

<p><i>by Florian Weingarten (flo@hackvalue.de)</i></p>

<form action="" method="get">
<table border="0" cellspacing="3" cellpadding="3">
<tr><th>WCA ID:</th><td><input name="id" value="<?=$_GET['id']?>" /></td></tr>
<tr>
 <th>Rankings:</th>
 <td>
<? foreach(array("NR","WR") as $foo) { ?>
  <input name="ranking" type="radio" value="<?=$foo?>" <?= ( $foo == $_GET['ranking'] || ( $foo == "NR" && $_GET['ranking'] == "")) ? 'checked="checked"' : '' ?>/><?=$foo?><br/>
<? } ?>
 </td>
</tr>
<tr>
 <th>Events:</th>
 <td>
  <table border="0" width="100%">
  <tr><th>1</th><th>2</th><th>3</th></tr>
  <tr>
<? for($i=1; $i<=3; $i++) { ?>
  <td>
  <input name="event_<?=$i?>" type="radio" value="None" <? if($_GET['event_'.$i] == "None" || $_GET['event_'.$i] == "") { echo 'checked="checked"'; } ?>/>None<br/>
<? foreach($events as $event) { ?>
  <input name="event_<?=$i?>" type="radio" value="<?=$event?>" <?= $event == $_GET['event_' . $i] ? 'checked="checked"' : '' ?>/><?=$event?><br/>
<? } ?>
  </td>
<? } ?>
  </tr>
  </table>
 </td>
</tr>
<tr>
 <th>Options:</th>
 <td>
  <input type="checkbox" name="logo" value="0" <? if($_GET['logo'] == "0") { echo 'checked="checked"'; } ?>/> No WCA Logo<br/>
  <input type="checkbox" name="transparent" value="1" <? if($_GET['transparent'] == "1") { echo 'checked="checked"'; } ?>/> Transparent background, no border<br/>
 </td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" value="Generate"/></td></tr>
</table>
</form>

<? if($_GET['id']) { ?>
<h2>Badge</h2>

<p>
<img src="img.php?<?= htmlentities($_SERVER['QUERY_STRING']) ?>" alt="<?= htmlentities($_GET['id']) ?>" />
</p>

<h2>Forum Code</h2>

<pre>
[url=http://www.worldcubeassociation.org/results/p.php?i=<?= htmlentities($_GET['id']) ?>]
[img]http://cube.hackvalue.de/badge/img.php?<?= htmlentities($_SERVER['QUERY_STRING']) ?>[/img]
[/url]
</pre>

<h2>URL</h2>
<pre>
http://cube.hackvalue.de/badge/img.php?<?= htmlentities($_SERVER['QUERY_STRING']) ?>
</pre>

<h2>HTML Code</h2>
<pre>
&lt;a href="http://www.worldcubeassociation.org/results/p.php?i=<?= htmlentities($_GET['id']) ?>"&gt;
&lt;img src="http://cube.hackvalue.de/badge/img.php?<?= htmlentities($_SERVER['QUERY_STRING']) ?>" alt="<?= htmlentities($_GET['id']) ?>"&gt;
&lt;/a&gt;
</pre>
<? } ?>

</body>
</html>
