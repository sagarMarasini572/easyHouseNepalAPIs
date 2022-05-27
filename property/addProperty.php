<?php
header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include '../DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
// checking user input
if (
    isset($_POST['property_title']) &&
    isset($_POST['property_price']) &&
    isset($_POST['property_area']) &&
    isset($_POST['property_age']) &&
    isset($_POST['no_of_floors']) &&
    isset($_POST['no_of_rooms']) &&
    isset($_POST['facing_direction']) &&
    isset($_POST['contract_duration']) &&
    isset($_POST['no_of_bedrooms']) &&
    isset($_POST['no_of_bathrooms']) &&
    isset($_POST['no_of_kitchens']) &&
    isset($_POST['province']) &&
    isset($_POST['district']) &&
    isset($_POST['city']) &&
    isset($_POST['tole']) &&
    isset($_POST['property_house_no']) &&
    isset($_POST['property_description']) &&
    isset($_POST['owner_name']) &&
    isset($_POST['owner_email']) &&
    isset($_POST['owner_contact']) 
) {
    // storing user input    
    $title = $_POST['property_title'];
    $price = $_POST['property_price'];
    $area = $_POST['property_area'];
    $age = $_POST['property_age'];
    $no_floors = $_POST['no_of_floors'];
    $no_rooms = $_POST['no_of_rooms'];
    $facing_dir = $_POST['facing_direction'];
    $contract_dur = $_POST['contract_duration'];
    $no_bed = $_POST['no_of_bedrooms'];
    $no_bath = $_POST['no_of_bathrooms'];
    $no_kitchen = $_POST['no_of_kitchens'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $tole = $_POST['tole'];
    $house_no = $_POST['property_house_no'];
    $description = $_POST['property_description'];
    $owner_name = $_POST['owner_name'];
    $owner_email = $_POST['owner_email'];
    $owner_contact = $_POST['owner_contact'];

    // Create PHP Code to get maximum property id from the database.
    $maxsql = "SELECT max(property_id) from properties";
    // Executing SQL Query.
    $maxresult = mysqli_query($connect, $maxsql);
    // fetching the maxresult
    $maxcheck = mysqli_fetch_array($maxresult);
    // storing property id of index 0 to the variable
    $maxId = $maxcheck[0];
    // incrementing the maxId by 1
    $requiredId = isset($_POST['property_id']) ? $_POST['property_id'] : $maxId + 1;
    // checking if the property id is already in the database
    if (isset($_POST['property_id'])) {
        // Create PHP Code to get update the property details in the database.
        $upsql = "UPDATE properties SET property_title = '$title', property_price = '$price' , property_area = '$area' , property_age = '$age' , no_of_floors = '$no_floors' , no_of_rooms = '$no_rooms' , facing_direction = '$facing_dir' , contract_duration = '$contract_dur' , no_of_bedrooms = '$no_bed' , no_of_bathrooms = '$no_bath' , no_of_kitchens = '$no_kitchen' , province = '$province' , district = '$district' , city = '$city' , tole = '$tole' , property_house_no = '$house_no' , property_description = '$description' , owner_name = '$owner_name' , owner_email = '$owner_email' , owner_contact = '$owner_contact' where property_id = '$requiredId'";
        // Executing SQL Query.
        $result = mysqli_query($connect, $upsql);
    } else {
        // if the property id is not in the database
        // Create PHP Code to insert the property details in the database.
        $sql = "INSERT INTO properties (property_id, property_title, property_price, property_area, property_age, no_of_floors, no_of_rooms, facing_direction, contract_duration, no_of_bedrooms, no_of_bathrooms, no_of_kitchens, province, district, city, tole, property_house_no, property_description, owner_name, owner_email, owner_contact) VALUES ('$requiredId', '$title',  '$price', '$area', '$age', '$no_floors', '$no_rooms', '$facing_dir', '$contract_dur', '$no_bed', '$no_bath', '$no_kitchen', '$province', '$district', '$city', '$tole', '$house_no', '$description', '$owner_name', '$owner_email', '$owner_contact' )";
        // Executing SQL Query.
        $result = mysqli_query($connect, $sql);
    }

    // if the query is executed successfully
    if ($result) {
        // checking image is set or not
        if (isset($_FILES['images'])) {
            // storing the image in the variable
            $countfiles = count($_FILES['images']['name']);
            // looping through the images
            for ($i = 0; $i < $countfiles; $i++) {
                // get the details of the image
                $filename = $_FILES['images']['name'][$i];
                // storing the image type
                $valid_ext = array('png', 'jpeg', 'jpg');
                // get the extension of the selected image (jpg, png, jpeg)
                $photoExt1 = explode('.', $filename);
                // storing the image extension in the variable
                $photoExt2 = strtolower($photoExt1[1]);

                // create the new name for image
                $newFileName = $photoExt1[0] . time() . '.' . $photoExt2;
                // source path of the image
                $location = '../images/' . $newFileName;
                // destination path to store the image
                $img_loc = 'images/' . $newFileName;
                // storing the image path
                $file_type = pathinfo($location, PATHINFO_EXTENSION);
                // converting the image path to lowercase
                $file_ext = strtolower($file_type);
                // checking if the extension is valid
                if (in_array($file_ext, $valid_ext)) {
                    // compress the image
                    $compressed = move_uploaded_file($_FILES['images']['tmp_name'][$i], $location);
                    // if the image is compressed
                    if ($compressed) {
                        // Create PHP Code to insert the image path in the database.
                        $imgsql = "INSERT INTO property_images (property_id, img_path) VALUES ('$requiredId', '$img_loc')";
                        // Executing SQL Query.
                        mysqli_query($connect, $imgsql);
                    }
                }
            }
        }
        // defining the message to be displayed
        $data = ['success' => true, 'message' => !isset($_POST['property_id']) ? ['Property added successfully'] : ['Property updated successfully']];
        // encoding the message to json format
        echo json_encode($data);
    } else {
        // displaying the error message
        $data = ['success' => false, 'message' => ['Something went wrong!']];
        // encoding the data to json format
        echo json_encode($data);
    }
} else {
    // displaying the error message
    $data = ['success' => false, 'message' => ['Please fill all the fields.']];
    // encoding the data to json
    echo json_encode($data);
}

// function to compress the image
function compress_image($src, $dest, $quality)
{
    // getting the image size
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
        // creating the image from the source
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
