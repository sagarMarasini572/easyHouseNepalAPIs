<?php

header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

    $registration_date = date("Y-m-d");
    // Create PHP Code to get the user from the database.
    $sql = "SELECT * FROM users WHERE role = 'user' order by registration_date desc";
    // Executing SQL Query.
    $result = mysqli_query($connect, $sql);
    // storing the user data in array.
    $users = array();
    // Looping through the user and pushing them into the array.
    while ($row = mysqli_fetch_array($result)) {
        $users[] = array(
            'user_id' => $row['user_id'],
            'email' => $row['email'],
            'full_name' => $row['full_name'],
            'contact' => $row['contact'],
            'profile_image' => $row['profile_image'],
            'registration_date' => $row['registration_date'],
            'role' => $row['role'],            
            'is_verified' => $row['verification_code'] === null ? true : false,
        );
        // defining success message.
        $data = [
            'success' => true,
            'message' => ['Users fetched successfully'],
            'data' => $users
        ];
    }
    // encoding the data in json format.
    echo json_encode($data);
?>
    