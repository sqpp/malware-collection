<?php
/*
* This file can be used to retrieve archives from a remote SendStudio installation and display on a local web server with different formatting, look and feel etc.
* This is an example file only, please modify to suit your needs.
*
* @package SendStudio
*/

/*
The location of the RSS feed in the SendStudio installation. To show an archive of all mailing lists available, then this path will point to the rss.php file. To specify which mailing list to show, use the ID of the list eg. rss.php?List=24

Inside your SendStudio control panel, browse to the View Mailing Lists option and click on the RSS icon. This will be the path to the RSS feed for that specific mailing list.
*/

define('XML_URL', 'http://domain.com/ssnx/rss.php');

// How many newsletters should we show in the archives?
define('NUMBER_OF_ENTRIES_TO_SHOW', 50);

// The length of the email summary to show (number of characters).
define('MAX_POST_LENGTH', 40);

// Setup your HTML header here
$htmlheader = "
<html>
<head>
<title>Newsletter Archives</title>
<style>

body {
	font-family: Tahoma;
	font-size: 11px;
	color: black;
	line-height: 1.5;
	background-color: #F3F2E9;
	margin: 20px;
}

a {
	font-size: 14px;
}

.container {
	border: 1px #CAC7BD solid;
	background-color: #FFFFFF;
	padding: 20px
}

.smalltext {
	font-size: 9px;
}

.heading
{
	font-size: 18px;
	font-weight: normal;
	font-family: Tahoma;
}
</style>
</head>
<body>
	<div class='container'>
";

$htmlfooter = "
	</div>
</body>
</html>
";

$content = GetContent();
if ($content == '') {
	echo 'No content retrieved from feed, aborting.<br/>';
	exit();
}

$items = FetchXmlNode('item', $content, true);

$mycontent = $htmlheader . "<table border='0' cellspacing='0' cellpadding='0'>";
$mycontent .= "<tr><td class='heading'>Newsletter Archives<br><br></td></tr>";
$numbershown = 1;
foreach ($items as $itempos => $item) {
	if ($numbershown > NUMBER_OF_ENTRIES_TO_SHOW) {
		break;
	}

	$title = FetchXmlNode('title', $item);
	$link = FetchXmlNode('link', $item);
	$author = FetchXmlNode('author', $item);
	$postdate = FetchXmlNode('pubdate', $item);

	$postcontent = urldecode(FetchXmlNode('content', $item));

	$post = '';
	preg_match('%<\!\[cdata\[(.*?)\]\]>%is', $postcontent, $match);
	if (!empty($match)) {
		$post = $match[1];
	}
	$shortpost = GetShortPost($post);

	$mycontent .= '
		<tr>
			<td>
				<a href="' . $link . '" class="heading1">' . $title . '</a><br>
				<a href="' . $link . '" class="smalltext">' . $shortpost . '</a><br>
				<span class="smalltext">Posted: ' . $postdate . ', By: ' . $author . '</span><br><br>
			</td>
		</tr>
	';
	$numbershown++;
}

$mycontent .= '</table>';
echo $mycontent;

/**
 * GetShortPost
 * Based on the content passed in, it returns a shortened/trimmed down version of that content.
 * If the content is shorter than the length specified at the top, nothing is trimmed down.
 * If it is longer than the length specified, then it's trimmed to a max of that length.
 *
 * @param String $content The content to trim down to a particular length.
 *
 * @see MAX_POST_LENGTH
 *
 * @return String Returns the trimmed down version of the content.
 */
function GetShortPost($content='')
{
	$content = trim($content);
	$post = str_replace("\r", "\n", $content);
	$post = str_replace("\n", "", $post);
	if (strlen($post) > MAX_POST_LENGTH) {
		$shortpost = substr($post, 0, MAX_POST_LENGTH);
		if (substr($shortpost, -3, 3) != '...') {
			$shortpost .= '...';
		}
	} else {
		$shortpost = $post;
	}
	return $shortpost;
}

/**
 * TimeDifference
 * Works out the relative difference between when an item was posted and now.
 * Eg turns 125 seconds into 2 minutes, 5 seconds.
 * If the difference is longer than a week, it's just returned as a properly formatted date (d-M-Y)
 *
 * @param Int $posttime The time the item was posted
 * @param Int $timenow The time now
 *
 * @return String Returns the relative time difference as a string (eg '2 minutes, 5 seconds').
*/
function TimeDifference($posttime, $timenow)
{
	$difference = $timenow - $posttime;

	if ($difference < ONE_MINUTE) {
		$timechange = number_format($difference, 0) . ' second';
		if ($difference > 1) {
			$timechange .= 's';
		}
	}
	if ($difference > ONE_MINUTE && $difference < ONE_HOUR) {
		$num_mins = floor($difference / ONE_MINUTE);
		$timechange = number_format($num_mins, 0) . ' minute';
		if ($num_mins > 1) {
			$timechange .= 's';
		}
	}

	if ($difference >= ONE_HOUR && $difference < ONE_DAY) {
		$hours = floor($difference/ONE_HOUR);
		$mins = floor($difference % ONE_HOUR) / ONE_MINUTE;

		$timechange = number_format($hours, 0) . ' hour';
		if ($hours > 1) {
			$timechange .= 's';
		}

		$timechange .= ', ' . number_format($mins, 0) . ' minute';
		if ($mins > 1) {
			$timechange .= 's';
		}
	}

	if ($difference >= ONE_DAY && $difference < ONE_WEEK) {
		$days = floor($difference / ONE_DAY);
		$timechange = number_format($days, 0) . ' day';
		if ($days > 1) {
			$timechange .= 's';
		}
	}

	if ($difference >= ONE_WEEK) {
		return date('d-M-Y', $posttime);
	}

	$timechange .= ' ago';
	return $timechange;
}

/**
 * FetchXmlNode
 * Looks for a node based on the name of the node you're looking for and the xml passed in.
 * Either returns a particular node (if you're looking for one) or all of those nodes if you're looking for all.
 *
 * @param String $node The name of the 'node' you're looking for.
 * @param String $xml The xml you're looking for the node in.
 * @param Boolean $all Whether you are looking for all nodes in a tree or just one particular node.
 *
 * @return False|Array Returns false if the node is not found in the xml, otherwise returns the array of items for that node.
*/
function FetchXmlNode($node='', $xml='', $all=false)
{
	if ($node == '') {
		return false;
	}
	if ($all) {
		preg_match_all('%<('.$node.'[^>]*)>(.*?)</'.$node.'>%is', $xml, $matches);
	} else {
		preg_match('%<('.$node.'[^>]*)>(.*?)</'.$node.'>%is', $xml, $matches);
	}

	if (!isset($matches[2])) {
		return false;
	}

	return $matches[2];
}

/**
 * GetContent
 * Gets the content from the XML_URL defined at the top and returns it as a string.
 * Tries to use curl (if it's available) or fopen (if curl isn't available).
 * If neither are available, echos an error message and then dies.
 *
 * @see XML_URL
 * @see NUMBER_OF_ENTRIES_TO_SHOW
 *
 * @return String|Void Returns the page content if it can fetch it, otherwise shows an error and dies.
*/
function GetContent()
{
	$url = XML_URL . '?Fetch=' . NUMBER_OF_ENTRIES_TO_SHOW;

	if (function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$pagedata = curl_exec($ch);

		if (!$pagedata) {
			$error = curl_error($ch);
		}
		curl_close($ch);

		if (!$pagedata) {
			echo "Error: " . $error . "<br/>";
		}
		$pagedata = trim($pagedata);
		return $pagedata;
	}

	if (ini_get('allow_url_fopen')) {
		if (!$fp = fopen($url, 'r')) {
			echo 'Unable to open url, aborting.<br/>';
			exit();
		}
		$pagedata = '';
		while (!feof($fp)) {
			$pagedata .= fread($fp, 1024);
		}
		fclose($fp);
		$pagedata = trim($pagedata);
		return $pagedata;
	}

	echo 'Your server does not support curl or have allow_url_fopen switched on. Unfortunately we cannot get content without either of these options being available. Please speak to your administrator.<br/>';
	exit();
}
?>
