<?php
header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include '../DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
// checking for user id and property id
if (isset($_POST['user_id']) && isset($_POST['property_id'])) {
    // storing the user id and property id
    $user_id = $_POST['user_id'];
    $property_id = $_POST['property_id'];
    // Create PHP Code to user wishlist from the database.
    $sql = "SELECT * FROM wishlist where user_id=$user_id and property_id=$property_id";
    // Executing SQL Query.
    $result = mysqli_query($connect, $sql);
    // if the property is already in the wishlist then delete it
    if ($result->num_rows > 0) {
        // Create PHP Code to delete the property from the database.
        $sql = "DELETE from wishlist where user_id=$user_id and property_id=$property_id";
        // if query is successful
        if (mysqli_query($connect, $sql)) {
            // showing success message
            $data = ['success' => true, 'message' => ['Property removed from wishlist!']];
        } else {
            // showing error message
            $data = ['success' => false, 'message' => ['Something went wrong!']];
        }
    } else {
        // Create PHP Code to insert the user wishlist into the database.
        $sql = "INSERT INTO wishlist (user_id, property_id) VALUES ($user_id, $property_id)";
        // if query is successful
        if (mysqli_query($connect, $sql)) {
            // showing success message
            $data = ['success' => true, 'message' => ['Property added to wishlist!']];
        } else {
            // showing error message
            $data = ['success' => false, 'message' => ['Something went wrong!']];
        }
    }
    // echoing json data
    echo json_encode($data);
} else {
    // showing error message
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // echoing json data
    echo json_encode($data);
}
