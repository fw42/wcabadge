<?php
$wca_id = $_GET['id'];
if(!isset($wca_id) || $wca_id == "" || !preg_match("/^2\d\d\d\w+\d\d$/", $wca_id)) {
	die("Not a valid WCA ID.");
}
$wca_id = strtoupper($wca_id);

mysql_connect("localhost","MyUsername","MyPassword") or die("Database connection failed.");
mysql_select_db("MyUsername") or die("Database selection failed.");

function format_time($value,$event) {
	if ($event === "333fm") return $value;

	if ($event === "333mbf") {
		$difference = 99 - intval(substr($value,-9,2));
		$missed = intval(substr($value,-1,2));
		$time = intval(substr($value,-7,5));
		$solved = $difference + $missed;
		$tried = $solved + $missed;
		$minutes = intval(intval($time) / 60);
		$seconds = intval($time) % 60;
		if ($minutes>0) $seconds = "0".$seconds;
		return "$solved/$tried $minutes:".sprintf("%02d",$seconds);
	}

	$minutes = intval(intval($value) / 6000);
	$seconds = (intval($value) % 6000) / 100;

	if ($minutes > 0) {
		return "$minutes:".sprintf("%05.2f",$seconds);
	}

	return sprintf("%.2f",$seconds);
}

function get_average($wca, $event) {
	$query = sprintf("SELECT MIN(Average) AS average FROM Results WHERE Average > 0 AND personId='%s' AND eventId='%s'",
		mysql_real_escape_string($wca),
		mysql_real_escape_string($event)
	);
	$result = mysql_query($query);
	return mysql_result($result, 0);;
}

function get_single($wca, $event) {
	$query = sprintf("SELECT MIN(Best) AS single FROM Results WHERE Best > 0 AND personId='%s' AND eventId='%s'",
		mysql_real_escape_string($wca),
		mysql_real_escape_string($event)
	);
	$result = mysql_query($query);
	return mysql_result($result, 0);
}

function get_average_ranking($wca, $event, $country, $my) {
	$query = sprintf("SELECT MIN(Average) as avg FROM Results WHERE Average > 0 AND Average < '%s' AND eventId='%s' %s GROUP BY personId ORDER BY avg",
		mysql_real_escape_string($my),
		mysql_real_escape_string($event),
		$country != false ? ( "AND personCountryId='" . mysql_real_escape_string($country) . "'" ) : ""
	);
	$result = mysql_query($query);
	return mysql_num_rows($result)+1;
}

function get_single_ranking($wca, $event, $country, $my) {
	$query = sprintf("SELECT MIN(Best) as single FROM Results WHERE Best > 0 AND Best < '%s' AND eventId='%s' %s GROUP BY personId ORDER BY single",
		mysql_real_escape_string($my),
		mysql_real_escape_string($event),
		$country != false ? ( "AND personCountryId='" . mysql_real_escape_string($country) . "'" ) : ""
	);
	$result = mysql_query($query);
	return mysql_num_rows($result)+1;
}

if($_GET['mini'] != "1") {
	// Get name and country
	$query = sprintf("SELECT name, countryId from Persons WHERE id='%s'", mysql_real_escape_string($wca_id));
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$wca_name = $row[0];
	$wca_country = $row[1];

	// Get number of competitions
	$query = sprintf("SELECT COUNT(DISTINCT(competitionId)) AS comps FROM Results WHERE personId='%s'", mysql_real_escape_string($wca_id));
	$wca_comps = mysql_result(mysql_query($query),0);
}

$width = 450 + strlen($wca_name)*2.5;

if($_GET['mini'] == "1") {
	$width = 325;
}

$height = 54;
$base_x = 10;

if($_GET['logo'] != "0") {
	$width += 50;
	$base_x += 50;
	$logo = imagecreatefrompng("WCA_logo_2.png");
}

$img = imagecreatetruecolor($width, $height);
$border = imagecolorallocate($img, 0, 0, 0);
$background = imagecolorallocate($img, 255, 255, 255);
$text_colour = imagecolorallocate($img, 0, 0, 0);

if($_GET['transparent'] == "1") {
	imagecolortransparent($img, $background);
	imagefilledrectangle($img, 0, 0, $width-1, $height-1, $background);
} else {
	imagefilledrectangle($img, 0, 0, $width-1, $height-1, $border);
	imagefilledrectangle($img, 1, 1, $width-2, $height-2, $background);
}

if($_GET['logo'] != "0") {
	imagecopy($img,$logo,2,2,0,0,50,50);
	imagedestroy($logo);
	unset($logo);
}

// Font size, left, top, text, colour
$base_y = 5;

if($_GET['mini'] != "1") {
	imagestring($img, 5, $base_x, $base_y, $wca_name, $text_colour);
	imagestring($img, 3, $base_x, $base_y + 16, "$wca_id, $wca_country", $text_colour);
	imagestring($img, 3, $base_x, $base_y + 30, "$wca_comps WCA competition" . ($wca_comps == "1" ? "" : "s"), $text_colour);
}

include "events.php";
$xevents = array();
foreach(array($_GET['event_1'], $_GET['event_2'], $_GET['event_3']) as $foo) {
	if(in_array($foo, $events)) {
		array_push($xevents,$foo);
	}
}
$offset_y = 0;
$offset_x = 0;

if($_GET['mini'] != "1") {
	$offset_x = 10*strlen($wca_name);
	if($offset_x < 175) { $offset_x = 175; }
}

foreach($xevents as $event) {
	$avg = get_average($wca_id, $event);
	$single = get_single($wca_id, $event);
	$country = $wca_country;

	if($_GET['ranking'] == "WR") {
		$country = false;
	}

	if($avg == 0 && $single == 0)
		continue;

	$text = "$event: ";
	if($avg != 0) {
		$text .= format_time($avg,$event) . " (" . format_time($single,$event) . ")";
	} else {
		$text .= format_time($single,$event);
	}

	imagestring($img, 3, $base_x + $offset_x, $base_y + 4 + $offset_y, $text, $text_colour);

	if($country == false) {
		$text = "WR: ";
	} else {
		$text = "NR: ";
	}

	if($avg != 0) {
		$text .= "#" . get_average_ranking($wca_id,$event,$country,$avg) . " (#" . get_single_ranking($wca_id,$event,$country,$single) . ")";
	} else {
		$text .= "#" . get_single_ranking($wca_id,$event,$country,$single);
	}

	imagestring($img, 3, $base_x + $offset_x + 165, $base_y + 4 + $offset_y, $text, $text_colour);

	$offset_y += 12;

}

// Print image data as png
header("Content-type: image/png");
imagepng($img);

// Free memory
imagecolordeallocate($img, $border);
imagecolordeallocate($img, $background);
imagecolordeallocate($img, $text_color);
imagedestroy($img);
?>
