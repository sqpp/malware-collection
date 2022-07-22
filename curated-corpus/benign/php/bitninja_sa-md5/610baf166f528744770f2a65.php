<?php
require_once(dirname(__FILE__).'/../../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../../init.php');
if (Tools::encrypt('fieldvmegamenu/index')!= Tools::getValue('pw')) {
    die("Access denied!");
}
foreach ($_FILES["images"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $name = $_FILES["images"]["name"][$key];
        move_uploaded_file( $_FILES["images"]["tmp_name"][$key], "../uploads/" . $_FILES['images']['name'][$key]);
    }
}