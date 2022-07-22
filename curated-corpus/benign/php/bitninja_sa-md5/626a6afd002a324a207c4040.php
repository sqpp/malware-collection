<?php
session_start();

$errors = [];
require_once 'sendEmails.php';
$conn = new mysqli('192.3.204.226', 'navsang', ')7hGTT22z0r:Oc', 'navsang_db');
require_once '../classes/manage.php';
 $query = new Manage();

// Udate User Data
if (isset($_POST['reg'])) {
    
    
       $name = htmlspecialchars($_POST['name']) ;
         
          $email = htmlspecialchars($_POST['email']) ;
             $phone = htmlspecialchars($_POST['phone']) ;
                $location = htmlspecialchars($_POST['loc']) ;
                $address = htmlspecialchars($_POST['add']) ;
                $cac = htmlspecialchars($_POST['cac']) ;
              //  $pa = htmlspecialchars($_POST['loc']) ;
                
                 $password =  base64_encode($_POST['pass']);
                    
                       //    $pend = 1;
    
   //   $farmID = $query->getToken(12);
  

     if (count($errors) === 0) {
       $query = "INSERT INTO supply_comp SET comp_name=?, comp_email = ?,comp_phone=?, comp_loc=?, comp_address=?, comp_cac=?, comp_pass=? ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssss', $name, $email, $phone, $location, $address, $cac, $password);
        $result = $stmt->execute();
        $_SESSION['message'] = "Registration successful";
     header("location:../account");
     }
}


//Association

if (isset($_POST['signup-btn'])) {
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username required';
    }
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email required';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password required';
    }
    
   // if (isset($_POST['password']) && $_POST['password'] !== $_POST['passwordConf']) {
        //$errors['passwordConf'] = 'The two passwords do not match';
   // }

    $username = htmlspecialchars($_POST['username']) ;
    $ip =  $_SERVER['REMOTE_ADDR'];
   
    $email = $_POST['email'];
    $binary = "11111001";
   $token = bin2hex(random_bytes(50));// generate unique token
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt password

    
    

    if (count($errors) === 0) {
        $query = "INSERT INTO farmers_data SET username=?, f_email=?, auth_token=?, password=?, ip_address = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $username, $email, $token, $password, $ip);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // TO DO: send verification email to user
            sendVerificationEmail($email, $token);

            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = false;
            $_SESSION['message'] = 'You are logged in!';
            $_SESSION['type'] = 'alert-success';
            header('location: ../v_account');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}

// ADD Farm
if (isset($_POST['add_farm'])) {
    if (empty($_POST['own'])) {
        $errors['own'] = 'Farm ownership confirmation is required';
    }
   
     
     if (empty($_POST['loc'])) {
        $errors['loc'] = 'Farm location is required';
    }
    
     
    
    if (empty($_POST['fname'])) {
        $errors['fname'] = 'Farm Name is required';
    }

    $own = $_POST['own'];
    $farm_name = htmlspecialchars($_POST['fname']);
 //   $years = $_POST['years'];
      $size = $_POST['size'];
    $soil = $_POST['soil'];

    $location = htmlspecialchars($_POST['loc']) ;
    $vhead = htmlspecialchars($_POST['hname']) ;
     $vnumber = htmlspecialchars($_POST['num']) ;
 //coodinates
      $lat1 = $_POST['lat1'];
     $lon1 = $_POST['lon1'];
     
     
     
    
     
     $id = $_POST['user'];
    $farmID = $query->getToken(8);
    if (count($errors) === 0) {
        $query = "INSERT INTO f_farm SET farmer_id=?, farm_name = ?,farm_loc=?, point1_latitude=?, point1_longitude=?, soil_type=?, land_size=?, village_head=?, village_headN=?, farmID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssssssss', $id, $farm_name, $location, $lat1, $lon1, $soil, $size, $vhead, $vnumber, $farmID);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // TO DO: send verification email to user
           // sendVerificationEmail($email, $token);

            $_SESSION['id'] = $id;
           // $_SESSION['username'] = $username;
           // $_SESSION['email'] = $email;
           // $_SESSION['verified'] = false;
            $_SESSION['message'] = 'Farm added successfully!';
            $_SESSION['type'] = 'alert-success';
            header('location: ../v_account/farms');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}


//livestock farm
if (isset($_POST['add_farm1'])) {
   
   
     
     if (empty($_POST['loc'])) {
        $errors['loc'] = 'Farm location is required';
    }
    
     
    
    if (empty($_POST['fname'])) {
        $errors['fname'] = 'Farm Name is required';
    }

   // $own = $_POST['own'];
    $farm_name = htmlspecialchars($_POST['fname']);
 //   $years = $_POST['years'];
      $num = $_POST['num'];
    $liv =htmlspecialchars( $_POST['liv']);

    $location = htmlspecialchars($_POST['loc']) ;
    $vhead = htmlspecialchars($_POST['hname']) ;
     $vnumber = htmlspecialchars($_POST['num']) ;
 //coodinates
      $lat1 = $_POST['lat1'];
     $lon1 = $_POST['lon1'];
     
     
     
    
     
     $id = $_POST['user'];
    $farmID = $query->getToken(8);
    if (count($errors) === 0) {
        $query = "INSERT INTO l_farm SET farmer_id=?, farm_name = ?,farm_loc=?, point1_latitude=?, point1_longitude=?, liv_type=?, numbers=?, village_head=?, village_headN=?, farmID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssssssss', $id, $farm_name, $location, $lat1, $lon1, $liv, $num, $vhead, $vnumber, $farmID);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // TO DO: send verification email to user
           // sendVerificationEmail($email, $token);

            $_SESSION['id'] = $id;
           // $_SESSION['username'] = $username;
           // $_SESSION['email'] = $email;
           // $_SESSION['verified'] = false;
            $_SESSION['message'] = 'Farm added successfully!';
            $_SESSION['type'] = 'alert-success';
            header('location:livestock');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}


//Profession

if (isset($_POST['signup-btn'])) {
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username required';
    }
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email required';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password required';
    }
    
   // if (isset($_POST['password']) && $_POST['password'] !== $_POST['passwordConf']) {
        //$errors['passwordConf'] = 'The two passwords do not match';
   // }

    $username = htmlspecialchars($_POST['username']) ;
    $ip =  $_SERVER['REMOTE_ADDR'];
   
    $email = $_POST['email'];
    $binary = "11111001";
   $token = bin2hex(random_bytes(50));// generate unique token
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt password

    
    

    if (count($errors) === 0) {
        $query = "INSERT INTO farmers_data SET username=?, f_email=?, auth_token=?, password=?, ip_address = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $username, $email, $token, $password, $ip);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // TO DO: send verification email to user
            sendVerificationEmail($email, $token);

            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = false;
            $_SESSION['message'] = 'You are logged in!';
            $_SESSION['type'] = 'alert-success';
            header('location: ../account');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}
//I will try this mistake
if(isset($_POST['planted'])){
       if ($_POST['crops'] =="NULL" ) {
        $errors['own'] = 'Please select crop to add';
    }
   
  
   

    $crops = $_POST['crops'] ;
    $farm = $_POST['farm'];
   
 
     
    
     
     $id = $_POST['user'];
   
    if (count($errors) === 0) {
        $query = "INSERT INTO planted_crops SET f_id=?, farm_id =?,crop=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss',$id, $farm, $crops);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // TO DO: send verification email to user
           // sendVerificationEmail($email, $token);

            //$_SESSION['id'] = $id;
           // $_SESSION['username'] = $username;
           // $_SESSION['email'] = $email;
           // $_SESSION['verified'] = false;
            $_SESSION['message'] = 'Farm added successfully!';
            $_SESSION['type'] = 'alert-success';
            header('location: ../v_account/afarm');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}
    
//passport Upload
 error_reporting( ~E_NOTICE ); // avoid notice
 
  
 if(isset($_POST['pass']))
 {
 
    $id = $_POST['user'];
       //generating random password for the student
    $file_name =$_FILES['passport']['name'];
		$file_size =$_FILES['passport']['size'];
		$file_tmp =$_FILES['passport']['tmp_name'];
		$file_type=$_FILES['passport']['type'];	
                
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
                
     if(in_array($file_name, $valid_extensions)){   
    $errors['passport'] = 'The file extension is not valid';
     }
     
    if($file_size > 2097152)    {
     $errors['passport'] = 'Image is too large';
    }
        
    
   if (count($errors) === 0) {
  move_uploaded_file($file_tmp,"../passport_files/".$file_name);
   
     $update = $query->updateRow("update farmers_data set f_passport = ? where farmer_id = ?", ["$file_name", "$id"]);
     header("location:../v_account/farmer.php");
 
   }
   }
   //password update
   if(isset($_POST['password']))
 {
 
  if (empty($_POST['sec'])) {
        $errors['sec'] = 'Password required';
    }
    $id = $_POST['user'];
       $password = htmlspecialchars($_POST['sec']) ;
         $password = password_hash($_POST['sec'], PASSWORD_DEFAULT);
   // $password = $_POST['sec'];
      
    
   if (count($errors) === 0) {
  move_uploaded_file($file_tmp,"../passport_files/".$file_name);
   
     $update = $query->updateRow("update farmers_data set password = ? where farmer_id = ?", ["$password", "$id"]);
     header("location:../v_account/farmer.php");
 
   }
   }
   
   
     if(isset($_POST['upnumber']))
 {
 
  if (empty($_POST['num'])) {
        $errors['num'] = 'Please select number to update';
    }
    $id = $_POST['farm'];
     //  $password = htmlspecialchars($_POST['sec']) ;
      //   $password = password_hash($_POST['sec'], PASSWORD_DEFAULT);
    $num = $_POST['num'];
      
    
   if (count($errors) === 0) {
 
   
     $update = $query->updateRow("update l_farm set numbers = numbers+? where lfarm_id = ?", ["$num", "$id"]);
     header("location:(livestock)");
 
   }
   }
   
   
   
?>
