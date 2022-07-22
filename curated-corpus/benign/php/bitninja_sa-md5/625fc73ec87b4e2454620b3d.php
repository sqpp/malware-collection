<?php
$VIMG_VERS = "1.05";		// script version

/* ex:set ts=4 sw=4:
 * VerifyImg PHP script.  This script requires PHP 4 or later.
 * Copyright (c) 2004-2009 Root Software.  All rights reserved.
 *
 * Visit us at http://www.tectite.com/
 * for updates and more information.
 *
 **** If you use this script, please support its development and other
 **** freeware products by putting the following link on your website:
 ****	Visit www.tectite.com for free
 *		<a href="http://www.tectite.com/">image verification</a> software.
 *
 * Author: Russell Robinson, 2nd August 2004
 *
 * Purpose:
 * ~~~~~~~~
 *	To verify that a form submission is being performed by a real
 *	human being.
 *
 *	This script displays an image containing a number of characters.  Those
 *	characters are also placed in a PHP session variable called VerifyString.
 *
 *	You can display the image to a person on an HTML form and ask them
 *	to type in the characters they see.  When they submit the form,
 *	your script can then verify that they've entered the characters
 *	correctly.
 *
 *	Our free FormMail (from www.tectite.com) can use this protocol to allow
 *	you send an auto response to the person submitting your form.
 *
 * Installation
 * ~~~~~~~~~~~~
 *	1.	Upload this script to you web server.
 *	2.	Open it with a browser.
 *	3.	If you see an image with some characters, it worked!
 *		If not, then your PHP installation may be lacking.
 *
 * Testing
 * ~~~~~~~
 *	You can also download from www.tectite.com a script called "verifytest.php".
 *	It displays a form and when you submit the form it tells you whether you
 *	entered the displayed image characters correctly or not.
 *
 * Security
 * ~~~~~~~~
 *	Security is the primary concern in accepting data from your website visitors.
 *	Our FormMail script has several security features designed into it and
 *	image verification is one of them.
 *
 *	When you want to send an auto response to someone who submits one of your
 *	forms, you must be careful that your script cannot be used by spammers.
 *
 *	Probably the *only* safe way to do this is to ensure that a real human being
 *	has submitted your form.  If it's possible for a program to use your
 *	form submission script (e.g. FormMail) then it's possible for a spammer
 *	to use your script as a spam gateway.
 *
 * Configuration
 * ~~~~~~~~~~~~~
 *	No configuraton is required.  However, your PHP must have the GD module
 *	installed.  There are some optional settings, which you can find
 *	below in the CONFIGURATION section.
 *
 * Copying and Use
 * ~~~~~~~~~~~~~~~
 *	verifyimg.php is provided free of charge and may be freely distributed
 *	and used provided that you:
 *		1. keep this header, including copyright and comments,
 *		   in place and unmodified; and,
 *		2. do not charge a fee for distributing it, without an agreement
 *		   in writing with Root Software Pty Ltd allowing you to do so; and,
 *		3. if you modify verifyimg.php before distributing it, you clearly
 *		   identify:
 *				a) who you are
 *				b) how to contact you
 *				c) what changes you have made
 *				d) why you have made those changes.
 *
 * Warranty and Disclaimer
 * ~~~~~~~~~~~~~~~~~~~~~~~
 *	verifyimg.php is provided free-of-charge and with ABSOLUTELY NO WARRANTY.
 *	It has not been verified for use in critical applications, including,
 *	but not limited to, medicine, defense, aircraft, space exploration,
 *	or any other potentially dangerous activity.
 *
 *	By using verifyimg.php you agree to indemnify Root Software Pty Ltd,
 *	its agents, employees, and directors from any liability whatsoever.
 *
 * We still care
 * ~~~~~~~~~~~~~
 *	If you report problems to us, we will respond to your report and make
 *	endeavours to rectify any faults you've detected as soon as possible.
 *	To contact us, visit http://www.tectite.com/contacts.php.
 *
 * Version History
 * ~~~~~~~~~~~~~~~
 *
 **Version 1.05: 18-Dec-2009
 *
 * Workaround problems with PHP 5.2.6 and later.
 *
 **Version 1.04: 14-Sep-2006
 *
 * Fixed bug calling mt_rand - only caused an error on some versions of PHP.
 *
 **Version 1.03: 18-Apr-2005
 *
 * Added new $SESSION_NAME configuration setting.  Removed "9" from the
 * alphabet because it looks like "g". Also removed "5" and "s".
 *
 **Version 1.02: 5-Oct-2004
 *
 * Improved detection of old versions of GD.
 *
 **Version 1.01: 1-Sep-2004
 *
 * Added two new configuration variables: $bCleanText and $bWobbleChar.
 *
 **Version 1.00: 2-Aug-2004
 *
 *	First release.
 */

/*****************************************************************************/
/* CONFIGURATION (do not alter this line in any way!!!)                      */
/*****************************************************************************
 * This is the *only* place where you need to modify things to use formmail.php
 * on your particular system.  This section finishes at "END OF CONFIGURATION".
 *
 * Each variable below is marked as LEAVE, OPTIONAL or MANDATORY.
 * What we mean is:
 *		LEAVE		you can change this if you really want to and know what
 *					you're doing, but we recommend that you leave it unchanged
 *
 *		OPTIONAL	you can change this if you need to, but its current
 *					value is fine and we recommend that you leave it unchanged
 *					unless you need a different value
 *
 *		MANDATORY	you *must* modify this for your system.  The script will
 *					not work if you don't set the value correctly.
 *
 *****************************************************************************/
 
	//
	// ** LEAVE **
	// Set $sAlphabet to the list of characters you want to display in images.
	// The displayed image is made of characters randomly selected from
	// this list.
	//
	// We've set this to a list of characters that don't look similar.
	// For example, it's a mistake to show "0" and "O" to a human.
	//
	// We've also left out vowel characters so that it's unlikely you'll
	// get a word that's offensive to anyone.
	//
$sAlphabet = "cdfhjkmnprtvwxyz34678";

	//
	// ** OPTIONAL **
	// Set $iMinLength to the minimum number of characters to be shown.
	// This needs to be at least 4, giving a minimum number of
	// permutations of over 330,000.
	//
	// You can set this to a value <= $iMaxLength
	//
$iMinLength = 4;

	//
	// ** LEAVE **
	// Set $iMaxLength to the maximum number of characters to be shown.
	// The default value of 6 gives a number of permutations of over
	// 190,000,000.
	//
	// You can't increase this without changing the image size.
	//
$iMaxLength = 6;

	//
	// ** OPTIONAL **
	// Set $iFinalWidth and $iFinalHeight to specify the size of the returned
	// image in pixels.
	//
$iFinalWidth = 200;
$iFinalHeight = 40;

	//
	// ** OPTIONAL **
	// Set $bCleanText to true to keep the graphic noise away from the
	// text.  For best security, we recommend keeping this set to false.
	//
$bCleanText = FALSE;

	//
	// ** OPTIONAL **
	// Set $bWobbleChar to false to prevent individual characters
	// from moving around. For best security, we recommend keeping this set
	// to true.
	//
$bWobbleChar = TRUE;

	//
	// ** OPTIONAL **
	// Set SESSION_NAME to a non-empty string to get a unique PHP session for
    // your FormMail processing.  You only need to do this if your site
    // is a PHP-based site that uses sessions.
    //
    // FormMail attaches to or creates a PHP session so it can pass error
    // information to bad_url.  On succcessful completion, and if you're not
    // using good_url, FormMail destroys the session.  If you use the default
    // PHP session name on your site and within FormMail, this will cause any
    // session information from your site to be discarded.
    //
    // By using a unique session in FormMail, you avoid this problem.  You
    // should set the value below to the same value you set in FormMail.
    //
$SESSION_NAME = "";

/*****************************************************************************/
/* END OF CONFIGURATION (do not alter this line in any way!!!)               */
/*****************************************************************************/

	//
	// Check for old version of PHP - die if too old.
	//
function IsOldVersion(&$a_this_version)
{
    $i_too_old = 3;             // version 3 PHP is not usable here
    $a_modern = array(4,1,0);   // versions prior to this are "old" - "4.1.0"

    $a_this_version = explode(".",phpversion());

    if ((int) $a_this_version[0] <= $i_too_old)
        die("This script requires at least PHP version 4.  Sorry.");
    $i_this_num = ($a_this_version[0] * 10000) +
                    ($a_this_version[1] * 100) +
                    $a_this_version[2];
    $i_modern_num = ($a_modern[0] * 10000) +
                    ($a_modern[1] * 100) +
                    $a_modern[2];
    return ($i_this_num < $i_modern_num);
}

	//
	// Returns true if the PHP version is at or later than the string specified
	// (can't use "version_compare" before 4.1.0).
	//
function IsPHPAtLeast($s_vers)
{
	global	$aPHPVERSION;

    $a_test_version = explode(".",$s_vers);
	if (count($a_test_version) < 3)
		return (false);
	return ($aPHPVERSION[0] > $a_test_version[0] ||
			($aPHPVERSION[0] == $a_test_version[0] &&
				($aPHPVERSION[1] > $a_test_version[1] ||
					$aPHPVERSION[1] == $a_test_version[1] &&
						$aPHPVERSION[2] >= $a_test_version[2])));
}

$bUseOldVars = IsOldVersion($aPHPVERSION);
	//
	// seed the random number generator if not version 4.2.0 or later
	//
if (!IsPHPAtLeast("4.2.0"))
	mt_srand((double)microtime() * 1000000);

	//
	// Get the GD version installed
	//
function GetGDVersion()
{
	ob_start();
	phpinfo(8);
	$s_info = ob_get_contents();
	ob_end_clean();

	if (preg_match('/gd version.*?([0-9])/i',$s_info,$a_matches) > 0)
		return ($a_matches[1]);
	return (false);
}

define('DEBUG',false);		// for production
//define('DEBUG',true);			// for development and debugging

if (DEBUG)
	error_reporting(E_ALL);		// trap everything!

if (!empty($SESSION_NAME))
    session_name($SESSION_NAME);

session_start();

    //
    // we set references to the super global vars to handle version differences
    //
if ($bUseOldVars)
{
    $aServerVars = &$HTTP_SERVER_VARS;
    $aFormVars = &$HTTP_POST_VARS;
    $aFileVars = &$HTTP_POST_FILES;
}
else
{
	$aServerVars = &$_SERVER;
	$aFormVars = &$_POST;
    $aFileVars = &$_FILES;
}

    //
    // Session data setting
    //
function    SetSession($s_name,$m_value)
{
    global  $bUseOldVars,$HTTP_SESSION_VARS;

    if ($bUseOldVars)
        $HTTP_SESSION_VARS[$s_name] = $m_value;
    else
        $_SESSION[$s_name] = $m_value;
}

	//
	// Make a random string consisting of characters from $s_alpha.
	// The length if the string is at least $i_min and at most $i_max.
	// If $b_space is true, then a space is inserted between each character.
	//
function MakeRandomString($i_min,$i_max,$s_alpha,$b_space = true)
{
	$s_str = "";
	$i_alpha_len = strlen($s_alpha);
	$i_str_len = mt_rand($i_min,$i_max);
	for ($ii = 0 ; $ii < $i_str_len ; $ii++)
	{
		$i_index = mt_rand(0,$i_alpha_len-1);
		if ($b_space && $s_str !== "")
			$s_str .= " ";
		$s_str .= $s_alpha{$i_index};
	}
	return ($s_str);
}

	//
	// Add horizontal lines to an image.
	//
function AddHorzLines($im_image,$i_width,$i_height,$s_y_func,$i_color,
						$a_text_area)
{
	global	$bCleanText;

	$i_text_left = $a_text_area[0];
	$i_text_top = $a_text_area[1];
	$i_text_right = $a_text_area[2];
	$i_text_bot = $a_text_area[3];
	for (eval('$ii = '.$s_y_func.';') ; $ii < $i_height ; eval('$ii += '.$s_y_func.';'))
	{
		$b_split = FALSE;
		if ($bCleanText && $ii > $i_text_top && $ii <= $i_text_bot)
			$b_split = TRUE;
		$i_start = mt_rand(0,$i_width/2);
		$i_end = $i_width - mt_rand(1,$i_width/2);
		if ($b_split)
		{
				//
				// split the line
				//
			imageline($im_image,min($i_text_left-4,$i_start),$ii,$i_text_left-4,$ii,$i_color);
			imageline($im_image,$i_text_right+2,$ii,max($i_text_right+2,$i_end),$ii,$i_color);
		}
		else
			imageline($im_image,$i_start,$ii,$i_end,$ii,$i_color);
	}
}

	//
	// Add vertical lines to an image.
	//
function AddVertLines($im_image,$i_width,$i_height,$s_x_func,$i_color,
						$a_text_area,$i_char_width,$i_spacing)
{
	global	$bCleanText;

	$i_text_left = $a_text_area[0];
	$i_text_top = $a_text_area[1];
	$i_text_right = $a_text_area[2];
	$i_text_bot = $a_text_area[3];
	for (eval('$ii = '.$s_x_func.';') ; $ii < $i_width ; eval('$ii += '.$s_x_func.';'))
	{
			//
			// allow vertical lines to go through spaces
			//
		$b_split = FALSE;
		$i_start = mt_rand(0,$i_height/2);
		$i_end = $i_height - mt_rand(1,$i_height/2);
		if ($bCleanText && $ii >= $i_text_left && $ii < $i_text_right)
		{
			$i_char_pos = $ii - $i_text_left;
			if (((int) ($i_char_pos / $i_char_width)) % $i_spacing == 0)
				$b_split = TRUE;
			else
			{
					//
					// if we're going thru a space, ensure it's long enough
					// not to look like the tail on an h or similar
					//
				$i_start = mt_rand(0,$i_text_top);
				$i_end = mt_rand($i_text_bot,$i_height-1);
			}
		}
		if ($b_split)
		{
				//
				// split the line
				//
			imageline($im_image,$ii,min($i_text_top-2,$i_start),$ii,$i_text_top,$i_color);
			imageline($im_image,$ii,$i_text_bot+2,$ii,max($i_text_bot+2,$i_end),$i_color);
		}
		else
			imageline($im_image,$ii,$i_start,$ii,$i_end,$i_color);
	}
}

	//
	// Add noise to an image.
	//
function AddNoise($im_image,$i_width,$i_height,$i_color,$a_text_area)
{
	global	$bCleanText;

	$i_text_left = $a_text_area[0] - 4;
	$i_text_top = $a_text_area[1];
	$i_text_right = $a_text_area[2];
	$i_text_bot = $a_text_area[3] + 4;
	$n_pixels = mt_rand(($i_width * $i_height) / 20,($i_width * $i_height) / 10);
	for ($ii = 0 ; $ii < $n_pixels ; $ii++)
	{
		$i_x_pos = mt_rand(0,$i_width-1);
		$i_y_pos = mt_rand(0,$i_height-1);
			//
			// within the text area, reduce probability by 66.67%
			//
		if ($bCleanText &&
			$i_x_pos >= $i_text_left && $i_x_pos <= $i_text_right &&
			$i_y_pos >= $i_text_top && $i_y_pos <= $i_text_bot)
			if (mt_rand(0,2) != 1)
				continue;
		imagesetpixel($im_image,$i_x_pos,$i_y_pos,$i_color);
	}
}

	//
	// Add a wave to the image.
	//
function AddWave($im_image,$i_width,$i_height,$s_func,$i_offset,$i_color,$a_text_area)
{
	global	$bCleanText;

	$i_text_left = $a_text_area[0];
	$i_text_top = $a_text_area[1];
	$i_text_right = $a_text_area[2];
	$i_text_bot = $a_text_area[3];
	$i_half_height = $i_height / 2;
	$i_old_y = 0;
	$i_mult = (3 * 360) / $i_width;
	$i_offset += mt_rand(0,3) * 45;
	$i_end = $i_width - mt_rand(0,$i_width/4);
	for ($i_x = mt_rand(0,$i_width/4) ; $i_x < $i_end ; )
	{
		$i_new_x = $i_x + 1;
		$i_new_y = $i_half_height + ($s_func(deg2rad($i_new_x*$i_mult - $i_offset)) * $i_half_height);
		$b_show = TRUE;
		if ($bCleanText &&
				$i_new_x >= $i_text_left && $i_new_x < $i_text_right &&
				$i_new_y >= $i_text_top && $i_new_y < $i_text_bot)
			$b_show = FALSE;
		if ($b_show)
			imageline($im_image,$i_x,$i_old_y,$i_new_x,$i_new_y,$i_color);
		$i_old_y = $i_new_y;
		$i_x = $i_new_x;
	}
}
	//
	// Add the string to the image; uses the $iFontHeight & $iFontWidth global
	// to wobble the characters if so configured.
	//
function AddString($im_image,$i_font,$i_x,$i_y,$s_str,$i_color)
{
	global	$bWobbleChar,$iFontHeight,$iFontWidth;

	if ($bWobbleChar)
	{
		$i_len = strlen($s_str);
		$i_pos = 0;
		$i_xpos = $i_x;
		while ($i_pos < $i_len)
		{
			$i_ypos = $i_y + mt_rand(0,$iFontHeight / 4);
			imagestring($im_image,$i_font,$i_xpos,$i_ypos,$s_str{$i_pos},$i_color);
			$i_xpos += $iFontWidth;
			$i_pos++;
		}
	}
	else
		imagestring($im_image,$i_font,$i_x,$i_y,$s_str,$i_color);
}

	//
	// Here we create a random string that will be displayed as an image.
	//
$sVerifyString = MakeRandomString($iMinLength,$iMaxLength,$sAlphabet);

function ReportError($s_mesg)
{
	Header("Content-Type: text/html");
	echo "<p>$s_mesg</p>";
}

function GetColor($im_image,$a_colors)
{
	if (($i_color = imagecolorallocate($im_image,$a_colors[0],
										$a_colors[1],$a_colors[2])) == -1)
	{
		ReportError("ImageColorAllocate failed");
		exit(1);
	}
	return ($i_color);
}

	//
	// Arrange to resize the text to about double it's original size
	//
$iTextWidth = ($iFinalWidth * 21) / 40;
$iTextHeight = ($iFinalHeight * 5) / 8;

	//
	// imagecopyresized only works reliably with imagecreatetruecolor:
	//	http://au.php.net/ImageCopyResized
	//
$bResize = false;
if (GetGDVersion() >= 2 && function_exists('imagecreatetruecolor'))
{
	$imFinal = imagecreatetruecolor($iFinalWidth,$iFinalHeight);
	$bResize = true;
}
elseif (function_exists('imagecreate'))
{
	$imFinal = imagecreate($iFinalWidth,$iFinalHeight);
	$bResize = false;
}
else
{
	ReportError("No graphics functions.  Your PHP must have GD installed.");
	exit(1);
}

$aColors = array(
				// black on white
		array("fg"=>array(0,0,0),		"bg"=>array(255,255,255)),
				// white on black
		array("fg"=>array(255,255,255),	"bg"=>array(0,0,0)),
				// black on yellow
		array("fg"=>array(0,0,0),		"bg"=>array(255,255,0)),
				// yellow on black
		array("fg"=>array(255,255,0),	"bg"=>array(0,0,0)),
				// yellow on blue
		array("fg"=>array(255,255,0),	"bg"=>array(0,0,255)),
				// blue on yellow
		array("fg"=>array(0,0,255),		"bg"=>array(255,255,0)),
	);
$nColors = count($aColors);

mt_srand((double)microtime() * 1000000);
$iIndex = mt_rand(0,$nColors-1);
$aBackGround = $aColors[$iIndex]["bg"];
$aForeGround = $aColors[$iIndex]["fg"];

$iFinalBgColor = GetColor($imFinal,$aBackGround);
$iFinalFgColor = GetColor($imFinal,$aForeGround);
imagefill($imFinal,0,0,$iFinalBgColor);
$iFont = 5;			// use builtin font #5
$iFontWidth = imagefontwidth($iFont);
$iFontHeight = imagefontheight($iFont);
$iStringWidth = strlen($sVerifyString) * $iFontWidth;
$iStringHeight = $iFontHeight;

if ($bResize)
{
	$imText = imagecreatetruecolor($iTextWidth,$iTextHeight);
	$iTextBgColor = GetColor($imText,$aBackGround);
	$iTextFgColor = GetColor($imText,$aForeGround);

	imagefill($imText,0,0,$iTextBgColor);
	AddString($imText,5,0,0,$sVerifyString,$iTextFgColor);

		//
		// Compute magnification
		//
	$iStringWidth = ($iStringWidth * $iFinalWidth) / $iTextWidth;
	$iStringHeight = ($iStringHeight * $iFinalHeight) / $iTextHeight;
	$iFontWidth = ($iFontWidth * $iFinalWidth) / $iTextWidth;
	$iFontHeight = ($iFontHeight * $iFinalWidth) / $iTextWidth;

		//
		// Compute text position
		//
	$iMidX = $iFinalWidth / 16;			// start text about a sixteenth across
	$iMidX += ($iFinalWidth/64) - mt_rand(0,$iFinalWidth/32);
	if ($iMidX + $iStringWidth >= $iFinalWidth)
		$iMidX -= $iFinalWidth - $iStringWidth + 1;
	$iMidY = ($iFinalHeight / 9) * 2;	// start text about a quarter down
	$iMidY += ($iFinalHeight/8) - mt_rand(0,$iFinalHeight/4);

		//
		// Magnify the text into the final image
		//
	imagecopyresized($imFinal,$imText,$iMidX,$iMidY,0,0,
		$iFinalWidth,$iFinalHeight,$iTextWidth,$iTextHeight);
	imagedestroy($imText);		// done with text image

		//
		// Add a grid
		//
	AddHorzLines($imFinal,$iFinalWidth,$iFinalHeight,
				"$iFinalHeight / mt_rand(2,6)",$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));
	AddVertLines($imFinal,$iFinalWidth,$iFinalHeight,
				"$iFinalWidth / mt_rand(3,8)",$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight),
				$iFontWidth,2);
		//
		// Add noise
		//
	AddNoise($imFinal,$iFinalWidth,$iFinalHeight,$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));

		//
		// Add a sine wave
		//
	AddWave($imFinal,$iFinalWidth,$iFinalHeight,'sin',90,$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));
	AddWave($imFinal,$iFinalWidth,$iFinalHeight,'cos',0,$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));
}
else
{
		//
		// Compute text position
		//
	$iMidX = $iFinalWidth / 4;			// start text about a quarter across
	$iMidX += ($iFinalWidth/16) - mt_rand(0,$iFinalWidth/8);
	$iMidY = ($iFinalHeight / 5) * 2;	// start text about half down
	$iMidY += ($iFinalHeight/8) - mt_rand(0,$iFinalHeight/4);

		//
		// Add a grid
		//
	AddHorzLines($imFinal,$iFinalWidth,$iFinalHeight,
				"$iFinalHeight / mt_rand(2,6)",$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));
	AddVertLines($imFinal,$iFinalWidth,$iFinalHeight,
				"$iFinalWidth / mt_rand(3,8)",$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight),
				$iFontWidth,2);

		//
		// Add noise
		//
	AddNoise($imFinal,$iFinalWidth,$iFinalHeight,$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));

		//
		// Add a sine wave
		//
	AddWave($imFinal,$iFinalWidth,$iFinalHeight,'sin',90,$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));
	AddWave($imFinal,$iFinalWidth,$iFinalHeight,'cos',0,$iFinalFgColor,
				array($iMidX,$iMidY,$iMidX+$iStringWidth,$iMidY+$iStringHeight));
		//
		// Add the text
		//
	AddString($imFinal,$iFont,$iMidX,$iMidY,$sVerifyString,$iFinalFgColor);
}

header("Content-Type: image/png");
imagepng($imFinal);
imagedestroy($imFinal);
	//
	// Set the verify string in the session, stripping any blank chars.
	//
SetSession("VerifyImgString",str_replace(" ","",$sVerifyString));
?>
