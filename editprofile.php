<?php
include 'conn.php';

// Check if a company ID was specified in the request
if (!isset($_POST['id'])) {
  echo "Error: No company ID specified";
  exit();
}

$companyId = $_POST['id'];

// Check if an update request was submitted
if (isset($_POST['update'])) {
  // Update the company data in the database
  $companyName = $_POST['company_name'];
  $personName = $_POST['person_name'];
  $industry = implode(',', $_POST['industry']);
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $location = $_POST['location'];
  $size = $_POST['size'];
  $password = $_POST['password'];
  

  // Check if an image was uploaded
  if (isset($_POST['image'])) {
    // Decode the base64-encoded image string and save it as a BLOB in the database
    $imageData = base64_decode($_POST['image']);

    $sql = "UPDATE company SET company_name=?, person_name=?, phone=?, email=?, address=?, location=?, size=?, password=?, image=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssssis', $companyName, $personName, $phone, $email, $address, $location, $size, $password, $imageData, $companyId);
  } else {
    // Update the company data without changing the image
    $sql = "UPDATE company SET company_name=?, person_name=? , phone=?, email=?, address=?, location=?, size=?, password=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssssi', $companyName, $personName, $phone, $email,$address, $location, $size, $password, $companyId);
  }

  mysqli_stmt_execute($stmt);

  if (mysqli_affected_rows($con) > 0) {
    // Return a success message
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Company data updated successfully'));
  } else {
    // Return an error message
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Error updating company data: ' . mysqli_error($con)));
  }

  mysqli_stmt_close($stmt);
}

// Retrieve all distinct industry values from the company table
$sql_industry = "SELECT DISTINCT industry FROM company";
$result_industry = mysqli_query($con, $sql_industry);

if ($result_industry) {
  $industries = array();

  while ($row_industry = mysqli_fetch_assoc($result_industry)) {
    // Add each industry value to the array
    $industries = array_merge($industries, explode(',', $row_industry['industry']));
  }

  // Remove any duplicate values from the array
  $industries = array_unique($industries);

  // Map industries into list of Map objects with 'display' and 'value' keys
  $industryOptions = array_map(function($industry) {
      return [
           $industry
      ];
  }, $industries);

  // Add an option for all industries
  array_unshift($industryOptions,);

  // Retrieve company data and image from database for the specified company ID
  $sql_company = "SELECT company_name, person_name, industry, phone, email, address, location, size, password, image FROM company WHERE id=?";
  $stmt_company = mysqli_prepare($con, $sql_company);

  // Check for errors preparing the statement
  if (!$stmt_company) {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Error retrieving company data: ' . mysqli_error($con)));
    exit();
  }

  mysqli_stmt_bind_param($stmt_company, 'i', $companyId);
  mysqli_stmt_execute($stmt_company);
  $result_company = mysqli_stmt_get_result($stmt_company);

  if (mysqli_num_rows($result_company) > 0) {
    $row_company = mysqli_fetch_assoc($result_company);

    // Generate a base64-encoded string of the image data
    $imageData = base64_encode($row_company['image']);

    // Populate an array with the data and image, as well as the list of industries
    $data = array(
      'company_name' => $row_company['company_name'],
      'person_name' =>$row_company['person_name'],
      'industry' => explode(',', $row_company['industry']),
      'phone' => $row_company['phone'],
      'email' => $row_company['email'],
      'address' => $row_company['address'],
      'location' => $row_company['location'],
      'size' => $row_company['size'],
      'password' => $row_company['password'],
      'industries' => $industryOptions,
      'image' => $imageData,
   
    );

    // Generate a JSON response containing the data, image, and list of industries
    header('Content-Type: application/json');
    echo json_encode($data);
  } else {
    // Return an error message if no data was found for the specified ID
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Error retrieving company data: ' . mysqli_error($con)));
  }

  mysqli_stmt_close($stmt_company);
} else {
  // Return an error message if no industries were found
  header('Content-Type: application/json');
  echo json_encode(array('message' => 'Error retrieving company industries: ' . mysqli_error($con)));
}

mysqli_close($con);
?>