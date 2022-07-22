<?php
$title1 = $_POST["catename"];
$veregdate = date("Y/m/d");


$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["filebutton"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
move_uploaded_file($_FILES["filebutton"]["tmp_name"], "../" . $target_dir . $_FILES["filebutton"]["name"]);
//echo "The file ". basename( $_FILES["filebutton"]["tmp_name"]). " has been uploaded.";
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    //$check = getimagesize($_FILES["filebutton"]["name"]);
    //if($check !== false) {
        include "../connection/DB.php";
		
		mysqli_query($connection, "INSERT INTO vendorcat(catname, catimage, delflag, regdate) VALUES('".$title1."','".$target_file."','0','".$veregdate."')");
		echo "<script>
		alert('Category added Successfuly');
		window.location.href='../admin-dashboard-add-catogery.php';
		</script>";  
}
?>