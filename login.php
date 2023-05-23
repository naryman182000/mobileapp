<?php
include 'conn.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($con, "SELECT * FROM company WHERE email = '$email' AND password = '$password'");
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $location = $row['location']; 
    $name=$row['company_name'];
    $arr = array('logged' => true, 'location' => $location,'name'=>$name);
} else {
    $arr = array('logged' => false);
}

echo json_encode($arr);
?>