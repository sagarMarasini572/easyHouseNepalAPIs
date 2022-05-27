<?php

header("Access-Control-Allow-Origin: *");
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

// checking user input
if (
    isset($_POST['user_id']) &&
    isset($_POST['password']) &&
    isset($_POST['new_password'])
) {
    // Getting the received JSON into $json variable.
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $newPassword = $_POST['new_password'];
    // Create PHP Code to get the users from the database.
    $sql = "SELECT * from users where user_id='$user_id'";
    // Executing SQL Query.
    $result = mysqli_query($connect, $sql);
    // if ($result->num_rows > 0)  then we have a user with this id else we donot have user with this id.
    if ($result->num_rows > 0) {
        // looping through all the users and storing them in $tem variable.
        while ($row[] = $result->fetch_assoc()) {
            $tem = $row;
        }
        // stroing the databse user password in $dbPWD variable.        
        $dbPWD = $tem[0]['password'];
        // if the password is correct then we will update the password
        if (password_verify($password, $dbPWD)) {
            // encrypting the new password
            $newPw = password_hash($newPassword, PASSWORD_DEFAULT);
            // Creating PHP Code to update the user password in the database.
            $updateDetails = "UPDATE users set password='$newPw' where user_id='$user_id'";
            // if the query is executed successfully then we will send a succes message to the user.
            if ($result = mysqli_query($connect, $updateDetails)) {
                $data = ['success' => true, 'message' => ['Password Sucessfully updated!']];
            } else {
                // if the query is not executed successfully then we will send a failure message to the user.
                $data = ['success' => false, 'message' => ['Something went wrong!']];
            }
            // encoding the data in json format.
            echo json_encode($data);
        } else {
            // if the password is incorrect then we will send a failure message to the user.
            $data = ['success' => false, 'message' => ['Old Password is incorrect!']];
            // encoding the data in json format.
            echo json_encode($data);
        }
    } else {
        // if the user is not found then we will send a failure message to the user.
        $data = ['success' => false, 'message' => ['User not found!']];
        // encoding the data in json format.
        echo json_encode($data);
    }
} else {
    // if any of the fields are empty then we will send a failure message to the user.
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // encoding the data in json format.
    echo json_encode($data);
}
