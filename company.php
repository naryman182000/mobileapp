<?php
include 'conn.php';

$companyName = $_GET['name']; // Assuming you pass the company name as a parameter in the URL
$escapedCompanyName = $con->real_escape_string($companyName);
$sql = "SELECT company_name, person_name, industry, phone, email, address, location, size, image FROM company WHERE company_name = '$escapedCompanyName'";
$result = $con->query($sql);

// Check if any results were found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Read the image data from the database
    $imageData = $row['image'];

    // Convert the image data to base64
    $base64Image = base64_encode($imageData);

    // Check if the base64 encoding was successful
    if ($base64Image === false) {
        echo "Error encoding image data to base64";
    } else {
        // Add the base64-encoded image to the $row array
        $row['imageData'] = $base64Image;

        // Convert the $row array to JSON format
        $jsonResponse = json_encode($row, JSON_INVALID_UTF8_IGNORE);

        // Check if the JSON encoding was successful
        if ($jsonResponse === false) {
            echo "Error encoding JSON: " . json_last_error_msg();
        } else {
            echo $jsonResponse;
        }
    }
} else {
    echo "No company profile found";
}

// Close the connection
$con->close();
?>
