<? include "events.php" ?>
<html>
<head>
<style type="text/css">
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

<p><i>by Florian Weingarten</i></p>

<form action="" method="GET">
<table border="0" cellspacing="3" cellpadding="3">
<tr><th>WCA ID:</th><td><input name="id" value="<?=$_GET['id']?>"></td></tr>
<tr>
 <th>Rankings:</th>
 <td>
<? foreach(array("NR","WR") as $foo) { ?>
  <input name="ranking" type="radio" value="<?=$foo?>" <?= ( $foo == $_GET['ranking'] || ( $foo == "NR" && $_GET['ranking'] == "")) ? 'checked="checked"' : '' ?>><?=$foo?><br/>
<? } ?>
 </td>
</tr>
<tr>
 <th>Events</th>
 <td>
  <table border="0">
  <tr><th>1</th><th>2</th><th>3</th></tr>
  <tr>
<? for($i=1; $i<=3; $i++) { ?>
  <td>
  <input name="event_<?=$i?>" type="radio" value="None" <? if($_GET['event_'.$i] == "None" || $_GET['event_'.$i] == "") { echo 'checked="checked"'; } ?>>None<br/>
<? foreach($events as $event) { ?>
  <input name="event_<?=$i?>" type="radio" value="<?=$event?>" <?= $event == $_GET['event_' . $i] ? 'checked="checked"' : '' ?>><?=$event?><br/>
<? } ?>
  </td>
<? } ?>
  </tr>
  </table>
 </td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" value="Generate"></td></tr>
</table>
</form>

<? if($_GET['id']) { ?>
<h2>Badge</h2>
<img src="img.php?<?=$_SERVER['QUERY_STRING']?>"><br/>

<h2>URL</h2>
<pre>
http://cube.hackvalue.de/badge/img.php?<?=$_SERVER['QUERY_STRING']?>
</pre>

<h2>HTML Code</h2>
<pre>
&lt;a href="http://www.worldcubeassociation.org/results/p.php?i=<?=$_GET['id']?>"&gt;
&lt;img src="http://cube.hackvalue.de/badge/img.php?<?=$_SERVER['QUERY_STRING']?>" alt="<?=$_GET['id']?>"&gt;
&lt;/a&gt;
</pre>
<? } ?>

</body>
</html>
