<?php
header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include '../DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
// checking for user id, property id and payment token
if (isset($_POST['user_id']) && isset($_POST['property_id']) && isset($_POST['token'])) {
    // storing the user id, property id and payment token
    $user_id = $_POST['user_id'];
    $property_id = $_POST['property_id'];
    $token = $_POST['token'];
    // storing the current date
    $date = date("Y-m-d");
    // Create PHP Code to get the user bookings from the database where the user id is equal to the user id and the property id is equal to the property id.
    $checkSql = "SELECT * from bookings where user_id=$user_id and property_id=$property_id";
    // Executing SQL Query.
    $checkResult = mysqli_query($connect, $checkSql);
    // if the user has already booked the property
    if ($checkResult->num_rows > 0) {
        // showing error message
        $data = ['success' => false, 'message' => ['You have already booked this property!']];
    } else {
        // if the user has not booked the property yet
        // Create PHP Code to insert the user bookings into the database.
        $sql = "INSERT into bookings (property_id, user_id, payment_token, date) values('$property_id','$user_id','$token','$date')";
        // Executing SQL Query.
        $result = mysqli_query($connect, $sql);
        // if query is successful
        if ($result) {
            // showing success message
            $data = ['success' => true, 'message' => ['Booking successful!']];
        } else {
            // showing error message
            $data = ['success' => false, 'message' => ['Something went wrong!']];
        }
    }
    // echoing json data
    echo json_encode($data);
}
