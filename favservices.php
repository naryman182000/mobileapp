<?php
include 'conn.php';
$service=$_POST['service'];
$price=$_POST['price'];
$provider=$_POST['provider'];
$description=$_POST['description'];

$sql = mysqli_query($con, "INSERT  into favouriteservices (price ,provider ,name ,description) 
values('$price','$provider','$service','$description')");
 

$arr['result']=true;
echo json_encode($arr);