<html>
<head>
<style type="text/css">
img {
	border: 0px;
}
</style>
</head>
<body>

<form action="" method="GET">
<input name="id">
<input type="submit" value="Generate">
</form>

<? if($_GET['id']) { ?>
<img src="img.php?id=<?=$_GET['id']?>"><br/>
<? } ?>

</body>
</html>
