<?php 
session_start();
include('../lib/connectdb.php');
    $user=$_REQUEST['user'];
    $password=$_REQUEST['password'];
 
  
$sel="SELECT * FROM admin WHERE username='$user' AND password='$password'";
$query=$db->query($sel) ;
$result=mysqli_fetch_array($query);
$num_rows=mysqli_num_rows($query);

if($num_rows>0){
	
$_SESSION["user"] = $result['username'];
$_SESSION["admin_name"] = $result['name'];


 echo "1";
 }
 else{
	 	 
 echo "2";	 
	 }
?>