<?php
include 'conn.php';

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

  // Generate a JSON response containing the list of industries
  header('Content-Type: application/json');
  echo json_encode($industries);
  
} else {
  // Return an error message if no industries were found
  header('Content-Type: application/json');
  echo json_encode(array('message' => 'Error retrieving company industries: ' . mysqli_error($con)));
}

mysqli_close($con);
?>