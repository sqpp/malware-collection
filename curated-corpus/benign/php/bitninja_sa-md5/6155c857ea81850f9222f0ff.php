<?php
ob_start();
// error_reporting(-1);
// ini_set('display_errors', 'On');
include("../../db/config.php");
//add pack
if (isset($_POST['flag']) && $_POST['flag'] == 'add_pack_data') {

	$categoriesname = mysqli_real_escape_string($db,$_POST['categoriesname']);
	$titlename = mysqli_real_escape_string($db,$_POST['titlename']);
	$identifiername = mysqli_real_escape_string($db,$_POST['identifiername']);
	$publishername = mysqli_real_escape_string($db,$_POST['publishername']);
	$publisherwebsite = mysqli_real_escape_string($db,$_POST['publisherwebsitename']);
	$privacyPolicyWebsite = mysqli_real_escape_string($db,$_POST['ppwebsitename']);
	$licenseAgreementWebsite = mysqli_real_escape_string($db,$_POST['lawebsitename']);
	$fileToUpload=mysqli_real_escape_string($db, $_POST['fileToUpload']);

	//image upload//
	$target_dir = "../../uploadpack/";
	$target_file = $target_file .basename($_FILES["fileToUpload"]["name"]);
	$extension = explode("/", $_FILES["fileToUpload"]["type"]);
	$new_image="try_icone_".$titlename.".".$extension[1];

	$target_filepath=$target_dir.$new_image;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_filepath,PATHINFO_EXTENSION));

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target_filepath)) {
		$image=basename( $_FILES["fileToUpload"]["name"]); 
	}else {

	}
	$image=$new_image;
	$query = "SELECT title from categories_pack where title='".$titlename."'";
	$result1=mysqli_query($db,$query);
	$count=mysqli_num_rows($result1);

	if($count > 0)
	{
		$response['packs'] ="";
		$response['message'] = "Title already inserted!";
		$response['success']=0;    
		echo json_encode($response);
	}else{
		$sql = "INSERT INTO categories_pack (cat_name_id,title,identifier,publisher,publisher_website,pp_website,la_website,try_icone) VALUES ('".$categoriesname."','".$titlename."','".$identifiername."','".$publishername."','".$publisherwebsite."','".$privacyPolicyWebsite."','".$licenseAgreementWebsite."','".$image."')";

		$result=mysqli_query($db,$sql);
		$last_id = $db->insert_id;
		$sql1 = "INSERT INTO stickers (pack_id,sticker) VALUES ('".$last_id."','')";
		$result=mysqli_query($db,$sql1);

		$sql2 = "SELECT cp.*,cat.categories_name FROM `categories_pack` as cp join categories as cat WHERE cp.cat_name_id=cat.id AND cp.id='$last_id'";

		// $sql2 = "SELECT cp.*,cat.categories_name FROM `categories_pack` as cp join categories as cat WHERE cp.cat_name_id=cat.id AND cp.id='0'";
		$result2 = mysqli_query($db,$sql2);

		foreach ($result2 as $key => $value) {
			$response['packs'] = $value;
			// $response['packs'] = "";
			$response['success']=1;
			$response['message'] = "successfully added";
			echo json_encode($response);
		}
	}
}
// edit pack
if (isset($_POST['flag']) && $_POST['flag'] == 'edit_pack_data') {
	$id=$_POST['hiddenid'];
	$catid=$_POST['ecategorieid'];
	$tname=$_POST['etitlename'];
	$iname=$_POST['eidentifiername'];
	$pname=$_POST['epublishername'];
	$pwname=$_POST['epublisherwebsitename'];
	$ppwname=$_POST['eppwebsitename'];
	$lawname=$_POST['elawebsitename'];
	$hiddenimg=$_POST['hiddenimg'];

	$target_dir = "../../uploadpack/";
	if (!empty($_FILES["efileToUpload1"]["name"])) {
		$target_file = $target_file .basename($_FILES["efileToUpload1"]["name"]);
		$ex=mt_rand(100000, 999999);
		$extension = explode("/", $_FILES["efileToUpload1"]["type"]);
		$new_image="try_icone_".$tname."_".$ex.".".$extension[1];
		$target_filepath=$target_dir.$new_image;
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_filepath,PATHINFO_EXTENSION));

		if (move_uploaded_file($_FILES["efileToUpload1"]["tmp_name"],$target_filepath)) {
			$image=$new_image; 
		}
	}else{
		$image=$hiddenimg;
	}

	$query = "SELECT title,id from categories_pack where title='".$tname."' AND id!='".$id."'" ;

	$result=mysqli_query($db,$query);
	$ecount=mysqli_num_rows($result);
	if($ecount > 0)
	{
		$response['packs'] ="";
		$response['message'] = "Title already inserted!";
		$response['success']=0;  
		echo json_encode($response);

	}else{

		$sql = "UPDATE categories_pack SET title='".$tname."',identifier='".$iname."',publisher='".$pname."',publisher_website='".$pwname."',pp_website='".$ppwname."',la_website='".$lawname."',try_icone='".$image."' WHERE id='".$id."'";
		mysqli_query($db,$sql);
		
		$sql1 = "SELECT cp.*,cat.categories_name FROM `categories_pack` as cp join categories as cat WHERE cp.cat_name_id=cat.id AND cp.id='$id'"; 
		$result1 = mysqli_query($db,$sql1);

		foreach ($result1 as $key => $value) {
			$response['packs'] = $value;
			$response['success']=1;
			$response['message'] = "successfully added";   
		}
		echo json_encode($response);
	}
}
// filter pack
if($_POST['data']=="filter_category" && !isset($_GET['flag'])){

	$id=$_POST['id'];
	if(!empty($id)){
		$sql="SELECT * FROM categories_pack WHERE cat_name_id='$id'";
		$response = mysqli_query($db,$sql);
		$i=0;
		while ($data = mysqli_fetch_assoc($response)) {
			$data1[]=$data;
			$sql1 = "SELECT categories_name FROM categories WHERE id='".$data['cat_name_id']."'";
			$row = mysqli_query($db,$sql1);
			$data2 = mysqli_fetch_assoc($row);
			$data1[$i]['categories_name']=$data2['categories_name'];
			$i++;	
		}
	}else{
		$sql="SELECT * FROM categories_pack";
		$response = mysqli_query($db,$sql);
		$i=0;
		while ($data = mysqli_fetch_assoc($response)) {
			$data1[]=$data;
			$sql1 = "SELECT categories_name FROM categories WHERE id='".$data['cat_name_id']."'";
			$row = mysqli_query($db,$sql1);
			$data2 = mysqli_fetch_assoc($row);
			$data1[$i]['categories_name']=$data2['categories_name'];
			$i++;	
		}
	}
	$result['data']=$data1;
	$result['message']="success";
	$result['success']=1;
	echo json_encode($result);
}
// download pack data
if(isset($_GET['action']) && $_GET['action']=="download"){
	$id=$_GET['id'];

	$sql="SELECT categories_pack.*, categories.categories_name AS categories_name, stickers.sticker AS sticker FROM categories_pack LEFT JOIN categories ON categories_pack.cat_name_id = categories.id LEFT JOIN stickers ON categories_pack.id=stickers.pack_id WHERE categories_pack.id='$id'";
	$result = mysqli_query($db,$sql);
	$data = mysqli_fetch_assoc($result);
	$stickers=explode(',', $data['sticker']);

	// $de_url=explode('/', $_SERVER['REQUEST_URI']);
	$url="http://" . $_SERVER['SERVER_NAME']."/";

	foreach ($stickers as $key => $value){

		$img_size= filesize(BASE_URL."uploadpack/".$value);
		$img_size=$img_size/1024;
		$images[$key]['emojis']=[];
		$images[$key]['imageFileName']=$value;
		$images[$key]['size']=$img_size." KB";
		$images[$key]['uri']=BASE_URL."uploadpack/".$value;
		$total=$total+$img_size;
	}
	$data3 = array(
		'identifier' => $data['identifier'],
		'isWhitelisted' => "",
		'licenseAgreementWebsite' => $data['la_website'],
		'name' => $data['title'],
		'privacyPolicyWebsite' => $data['pp_website'],
		'publisher' => $data['publisher'],
		'publisherEmail' => "",
		'publisherWebsite' => $data['publisher_website'],
		'stickers' => $images,
		'stickersAddedIndex' => "",
		'totalSize' => $total." KB",
		'trayImageFile' => $data['try_icone'],
		'trayImageUri' => BASE_URL."uploadpack/".$data['try_icone']);

	$data2=json_encode($data3, JSON_PRETTY_PRINT);
	$filename =str_replace(' ','_',$data['title'].".json");
	$handle = fopen("../../uploadjson/".$filename, "w+") or die("Unable to open file!");
	fwrite($handle,$data2);
	fclose($handle);

	$file_url =BASE_URL."uploadjson/".$filename;
	header('Content-Type: application/json');
	header("Content-disposition: attachment; filename=\"".$filename."\""); 
	readfile($file_url);
	exit;
}
// remove pack
if($_GET['flag']="remove_pack" && isset($_GET['flag'])){
	// $id = $_REQUEST['id'];

	// $query = "UPDATE categories_pack SET is_delete='1'  WHERE id=".$id.""; 
	// $result = mysqli_query($db, $query);

	// $query2 = "UPDATE stickers SET is_delete='1'  WHERE pack_id=".$id.""; 
	// $result2 = mysqli_query($db, $query2);

	// header("Location:../packs.php");

	$id = $_REQUEST['id'];

	$query = "DELETE FROM categories_pack WHERE id=".$id.""; 
	$result = mysqli_query($db, $query);

	$query2 = "DELETE FROM stickers WHERE pack_id=".$id.""; 
	$result2 = mysqli_query($db, $query2);

	header("Location:../packs.php");
}
?>