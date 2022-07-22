<?php

$default_color = "";
$path_to_css_file = '../css/style.css';
$file_contents = file_get_contents($path_to_css_file);

if( $_POST['action'] == "load_default_color" ){
    getLineWithString($path_to_css_file, "default_color");
}

if( !empty( $_POST['action'] ) == "change_color" && !empty( $_POST['new_color'] ) && !empty( $_POST['default_color'] ) ){
    $file_contents = str_replace( $_POST['default_color'], $_POST['new_color'] , $file_contents );
    file_put_contents( $path_to_css_file, $file_contents );
    echo $_POST['new_color'];
}

function getLineWithString($fileName, $str) {
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            preg_match_all("/\[([^\]]*)\]/", $line, $color);
            $default_color = $color[1][0];
            echo $default_color;
        }
    }
    return -1;
}