<?php

header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
//  Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

$userId = isset($_POST['userId']) ? $_POST['userId'] : "";
// define array to store the user data
$user_data = array();
// check if user id and image is set 
if (isset($_POST['userId']) && isset($_FILES['image'])) {
    // storing the user id
    $userId = $_POST['userId'];
    // storing the time
    $timestamp = time();
    // get the details of the image
    $filename = $_FILES['image']['name'];
    // defining the image type
    $valid_ext = array('png', 'jpeg', 'jpg');
    // get the extension of the selected image (jpg, png, jpeg)
    $photoExt1 = explode('.', $filename);
    // converting the image extension to lower case
    $photoExt2 = strtolower($photoExt1[1]);
    // create the new name for image
    $newFileName = $photoExt1[0] . time() . '.' . $photoExt2;
    // source path of the image
    $location = 'images/' . $newFileName;
    // destination path to store the image
    $img_loc = 'images/' . $newFileName;
    // storing the image path
    $file_type = pathinfo($location, PATHINFO_EXTENSION);
    // converting the image path to lowercase
    $file_ext = strtolower($file_type);
    // checking if the extension is valid
    if (in_array($file_ext, $valid_ext)) {
        // compress the image
        $compressed = move_uploaded_file($_FILES['image']['tmp_name'], $location);
        // if the image is compressed
        if ($compressed) {
            // Create PHP Code to update the user image in the database.
            $updateDetails = "UPDATE users set profile_image = '$img_loc' where user_id = '$userId'";
            // if query is executed successfully
            if (mysqli_query($connect, $updateDetails)) {
                // defining the message to be displayed
                $data = ['success' => true, 'message' => ['Successfully updated!'], 'data' => $img_loc];
                // encoding the data to json format
                echo json_encode($data);
            } else {
                // defining the message to be displayed
                $data = ['success' => false, 'message' => ['Something went wrong!']];
                // encoding the data to json format
                echo json_encode($data);
            }
        } else {
            // defining the message to be displayed
            $data = ['success' => false, 'message' => ['Something went wrong!']];
            // encoding the data to json format
            echo json_encode($data);
        }
    }
} else {
    // defining the message to be displayed
    $data = ['success' => false, 'message' => ['UserId required']];
    // encoding the data to json format
    echo json_encode($data);
}

// function to compress the image
function compress_image($src, $dest, $quality)
{
    // gettin the image size
    $info = getimagesize($src);
    // checking if the image is jpeg
    if ($info['mime'] == 'image/jpeg') {
        // creating the image from the source
        $image = imagecreatefromjpeg($src);
    // checking if the image is gif  
    } elseif ($info['mime'] == 'image/gif') {
        // creating the image from the source
        $image = imagecreatefromgif($src);
    // checking if the image is png
    } elseif ($info['mime'] == 'image/png') {
        //  creating the image from the source
        $image = imagecreatefrompng($src);
    // else the image is not valid
    } else {
        // die with error
        die('Unknown image file format');
    }
    //compress and save file to jpg
    imagejpeg($image, $dest, $quality);

    //return destination file
    return true;
}
