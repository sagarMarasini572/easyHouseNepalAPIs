<?php

header("Access-Control-Allow-Origin: *");

// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
    // Create PHP Code to get the properties images from the database.
    $img_sql = "SELECT * FROM property_images";
    // Executing SQL Query.
    $img_result = $connect->query($img_sql);
    // storing the properties images in array.
    $images = array();
    // looping through the properties images and pushing them into the array.
    while ($row_images[] = $img_result->fetch_assoc()) {
        $images = $row_images;
    }
    // defining date format.
    $date = date("Y-m-d");
    // Create PHP Code to get  user bookings from the database.
    $sql = "SELECT b.id, u.user_id, p.property_id, p.property_title, p.property_price, p.no_of_bedrooms, p.no_of_bathrooms, p.no_of_kitchens, p.property_description, b.date, u.full_name FROM properties p INNER JOIN bookings b ON p.property_id = b.property_id INNER JOIN users u ON u.user_id = b.user_id order by b.date desc"; 
    // Executing SQL Query.
    $result = mysqli_query($connect, $sql);
    // Creating Array to store the user bookings.
    $users_bookings = array();
    // Looping through the fetched data
    while ($row = mysqli_fetch_array($result)) {
        // storing the property images in array.
        $prty_images =  array();
        // looping through the property images and pushing them into the array.
        foreach ($images as $image) {
            // checking if the property id matches with the property id in the database.
            if ($image['property_id'] == $row['property_id']) {
                $prty_images[] = $image;
            }
        }
        // storing the user bookings in array.
        $users_bookings[] = array(
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'property_id' => $row['property_id'],
            'property_title' => $row['property_title'],
            'property_price' => $row['property_price'],
            'no_of_bedrooms' => $row['no_of_bedrooms'],
            'no_of_bathrooms' => $row['no_of_bathrooms'],
            'no_of_kitchens' => $row['no_of_kitchens'],
            'property_description' => $row['property_description'],
            'full_name' => $row['full_name'],
            'book_date' => $row['date'],
            'images' => $prty_images,
            'is_booked' => true
        );
    }
    
        // defining success message.
        $data = [
            'success' => true,
            'message' => ['Users Booking fetched successfully'],
            'data' => $users_bookings
        ];
    // encoding the data in json format.    
    echo json_encode( $data);
?>