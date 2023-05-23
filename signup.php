<?php
include 'conn.php';
$email=$_POST['email'];
$password=$_POST['password'];
$address=$_POST['address'];
$company=$_POST['company'];
$person=$_POST['person'];
$size=$_POST['size'];
$address=$_POST['address'];
$phone=$_POST['phone'];
$loc=$_POST['location'];
$industry=$_POST['industry'];
$sql = mysqli_query($con, "INSERT  into company (company_name,person_name,industry,phone,email,address,password,size,location) 
values('$company','$person','$industry','$phone','$email','$address','$password','$size','$loc')");
 

$arr['result']=true;
echo json_encode($arr);