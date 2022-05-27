<?php
header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

// check user input
if (isset($_POST['email']) && isset($_POST['password'])) {
    // storing user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create PHP Code to get the user details from the database.
    $query = "SELECT * from users";
    // Executing SQL Query.
    $result = mysqli_query($connect, $query);

    // looping through the user and pushing them into the array.
    while ($row = mysqli_fetch_array($result)) {
        // verifying email and password
        if ($row['email'] === $email && password_verify($password, $row['password'])) {
            $user_data = (object) array(
                'user_id' => $row['user_id'],
                'email' => $row['email'],
                'full_name' => $row['full_name'],
                'contact' => $row['contact'],
                'role' => $row['role'],
                'profile_image' => $row['profile_image'],
                'is_verified' => $row['verification_code'] === null ? true : false,
            );
            // defining success message.
            $data = [
                'success' => true,
                'message' => ['Welcome ' . $row['full_name']],
                'data' => $user_data
            ];

            break;
        } else {
            // defining the message to be displayed
            $data = ['success' => false, 'message' => ['User not found!']];
        }
    }
    // encoding the data in json format.
    echo json_encode($data);
} else {
    // showing error message
    $data = ['success' => false, 'message' => ['Email or Password required!']];
    // encoding the data in json format.
    echo json_encode($data);
}
