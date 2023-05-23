<?php

include 'conn.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$provide=$_POST['company_name'];

$sql = "SELECT ServiceId as id, Name as name, description, Price as price, Provider as provider FROM service where provider = '$provide'";
$result = mysqli_query($con, $sql);
if (!$result) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}

if ($result === false) {
    // Handle query error
    $error = mysqli_error($con);
    echo json_encode(['error' => $error]);
} else if (mysqli_num_rows($result) > 0) {
    // Return array of results as JSON
    $services = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'provider' => $row['provider']
        ];
    }
    echo json_encode(['services' => $services]);
} else {
    // Return empty array
    echo json_encode(['services' => []]);
}
?>