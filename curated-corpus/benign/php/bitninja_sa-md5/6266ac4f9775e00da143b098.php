<?php
include("../includes/config.php");
include("../includes/functions.php");
$db = @mysqli_connect($conf['host'], $conf['user'], $conf['pass'], $conf['name']);
mysqli_query($db, 'SET NAMES utf8');

if(!$db) {	
	echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
}
$confUrl = $conf['url'];

$resultSettings = mysqli_fetch_row(mysqli_query($db, getSettings($querySettings)));

$queryToday = "SELECT COUNT(*) FROM `links` WHERE date(date) = '".date("Y-m-d")."'";
$resultToday = mysqli_fetch_row(mysqli_query($db, $queryToday));

$queryTotal = "SELECT COUNT(*) FROM `links`";
$resultTotal = mysqli_fetch_row(mysqli_query($db, $queryTotal));

?>
<div class="one h1">
	<?php echo number_format($resultToday[0], 0, '.', ','); ?>
</div>
<div class="two h1">
	<?php echo number_format($resultTotal[0], 0, '.', ','); ?>
</div>