<?php
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], "images/" . $_FILES["uploadfile"]["name"])) { 
  echo "success"; 
} else {
	echo "error";
}
?>