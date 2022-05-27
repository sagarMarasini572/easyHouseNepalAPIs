<?php
header("Access-Control-Allow-Origin: *");
// including required files
include './mailing.php';
include './generateOTP.php';
include './DatabaseConfig.php';
//  Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);


// check user input
if (
    isset($_POST['full_name']) &&
    isset($_POST['email']) &&
    isset($_POST['contact']) &&
    isset($_POST['password'])
) {
    // storing user input
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    // storing the current date
    $registration_date = date("Y-m-d");
    // encrypting the password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Create PHP Code to get the user details from the database where email matches the email provided by the user.
    $sql = "SELECT * from users where email = '$email'";
    // Executing SQL Query.
    $res = mysqli_query($connect, $sql);
    // if the user exists
    if ($res->num_rows > 0) {
        // showing failure message
        $data = ['success' => false, 'message' => ['Email already exists!']];
    } else {
        // storing the OTP
        $otp = generateOTP();
        // Create PHP Code to get max user id from the database.
        $maxsql = "SELECT max(user_id) from users";
        // Executing SQL Query.
        $maxresult = mysqli_query($connect, $maxsql);
        // fetching the max user id
        $maxcheck = mysqli_fetch_array($maxresult);
        // storing the max user id
        $maxId = $maxcheck[0];
        // incrementing the max user id
        $requiredId = $maxId + 1;
        // Create PHP Code to insert the user details in the database.
        $query = "INSERT INTO users (user_id,  full_name, email, contact, password, verification_code, registration_date) VALUES ('user_id','$fullName', '$email', '$contact', '$password','$otp','$registration_date')";
        // Executing SQL Query.
        if ($result = mysqli_query($connect, $query)) {
            // sending the otp to the provided email
            sendMail('OTP ', 'gadeshsharma00572@gmail.com', 'Gadesh Sharma', $otp);
            // getting user email
            $data = getUser($email, $connect);
        } else {
            // showing failure message
            $data = ['success' => false, 'message' => ['Something went wrong!']];
        }
    }
    // encoding the data in json format.
    echo  json_encode($data);
} else {
    // showing failure message
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // encoding the data in json format.
    echo json_encode($data);
}

// function to get user details
function getUser($email, $connect)
{
    // Create PHP Code to get the user details from the database.
    $query = "SELECT * from users";
    // Executing SQL Query.
    $result = mysqli_query($connect, $query);
    // looping through the result
    while ($row = mysqli_fetch_array($result)) {
        // if the email matches
        if ($row['email'] === $email) {
            // storing the user details
            $user_data = (object) array(
                'user_id' => $row['user_id'],
                'email' => $row['email'],
                'full_name' => $row['full_name'],
                'contact' => $row['contact'],
                'role' => $row['role'],
                'profile_image' => $row['profile_image'],
                'is_verified' => $row['verification_code'] === null ? true : false,
                'registration_date' => $row['registration_date']
            );
            // defining success message.
            $data = [
                'success' => true,
                'message' => ['Welcome ' . $row['full_name']],
                'data' => $user_data
            ];

            break;
        } else {
            // defining failure message.
            $data = ['success' => false, 'message' => ['User not found!']];
        }
    }
    // returning the data
    return $data;
}
