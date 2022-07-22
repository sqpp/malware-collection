<?php
$fname = $_POST["fullname"];
$gender = $_POST["gen"];
$bdate = $_POST["dob"];
$caste = $_POST["caste"];
$religion = $_POST["religion"];
$height = $_POST["height"];
$nic = $_POST["nic"];
$province = $_POST["province"];
$city = $_POST["city"];
$delflag = "0";


$target_dir = "uploads/prop/";
$target_file = $target_dir . basename($_FILES["filebutton"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
move_uploaded_file($_FILES["filebutton"]["tmp_name"], "../" . $target_dir . $_FILES["filebutton"]["name"]);
if(isset($_POST["submit"])) {
        include "../connection/DB.php";
		
		mysqli_query($connection, "INSERT INTO proposaltbl(profname, progender, probdate, procaste, proreligion, proheight, pronic, proprovine, procity, delflag, proimage) VALUES('".$fname."','".$gender."','".$bdate."','".$caste."','".$religion."','".$height."','".$nic."','".$province."','".$city."','".$delflag."','".$target_file."')");
		echo "<script>
		alert('Proposal added Successfuly');
		window.location.href='../admin-dashboard-add-proposal.php';
		</script>";  
}
?>