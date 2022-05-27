<?php
header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include '../DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

// checking user id
if (isset($_POST['user_id'])) {

    $user_id = $_POST['user_id'];
    // Create PHP Code to get the properties images from the database.
    $img_sql = "SELECT * FROM property_images";
    // Executing SQL Query.
    $img_result = $connect->query($img_sql);
    // Creating Array to store the properties images.
    $images = array();
    // Looping through the properties images and pushing them into the array.
    while ($row_images[] = $img_result->fetch_assoc()) {
        $images = $row_images;
    }
    // Create PHP Code to get the user wishlist from the database.
    $wish_sql = "SELECT * FROM wishlist where user_id=$user_id";
    // Executing SQL Query.
    $wish_result = $connect->query($wish_sql);
    // Creating Array to store the user wishlist.
    $wishes = array();
    // Looping through the user wishlist and pushing them into the array.
    while ($row_wishes = mysqli_fetch_array($wish_result)) {
        array_push($wishes, $row_wishes['property_id']);
    }

    // Create PHP Code to get the bookings from the database.
    $booked_sql = "SELECT * FROM bookings";
    // Executing SQL Query.
    $book_result = $connect->query($booked_sql);
    // Creating Array to store the bookings.
    $booked_prop = array();
    // Looping through the bookings and pushing them into the array.
    while ($row_books = mysqli_fetch_array($book_result)) {
        array_push($booked_prop, $row_books['property_id']);
    }

    // Create PHP Code to get the particular user bookings from the database.
    $book_sql = "SELECT * FROM bookings where user_id=$user_id";
    // Executing SQL Query.
    $book_result = $connect->query($book_sql);
    // Creating Array to store the user bookings.
    $books = array();
    // Looping through the user bookings and pushing them into the array.
    while ($row_books = mysqli_fetch_array($book_result)) {
        $booking_data[] = array(
            'id' => $row_books['id'],
            'property_id' => $row_books['property_id'],
            'user_id' => $row_books['user_id'],
            'payment_token' => $row_books['payment_token'],
            'date' => $row_books['date'],
        );
        array_push($books, $row_books['property_id']);
    }


    // Create PHP Code to get the properties from the database.
    $sql = "SELECT * FROM properties order by property_id desc";
    // Executing SQL Query.
    $result = $connect->query($sql);
    // Looping through the properties and pushing them into the array.
    while ($row = mysqli_fetch_array($result)) {
        // if (in_array($row['property_id'], $booked_prop)) {
        //     continue;
        // }
        // Creating Array to store the user property images.
        $prty_images =  array();
        // Looping through the user property images and pushing them into the array.
        foreach ($images as $image) {

            if ($image['property_id'] == $row['property_id']) {
                $prty_images[] = $image;
            }
        }
        // Creating book date variable.
        $book_date = null;
        // checking if the property is in the book
        if (in_array($row['property_id'], $books)) {
            $book_date = $booking_data[array_search($row['property_id'], $books)]['date'];
        }

        // Creating Array to store the user properties.
        $properties[] = (object) array(
            'property_id' => $row['property_id'],
            'property_title' => $row['property_title'],
            'property_price' => $row['property_price'],
            'property_area' => $row['property_area'],
            'property_age' => $row['property_age'],
            'no_of_floors' => $row['no_of_floors'],
            'no_of_rooms' => $row['no_of_rooms'],
            'facing_direction' => $row['facing_direction'],
            'contract_duration' => $row['contract_duration'],
            'no_of_bedrooms' => $row['no_of_bedrooms'],
            'no_of_bathrooms' => $row['no_of_bathrooms'],
            'no_of_kitchens' => $row['no_of_kitchens'],
            'province' => $row['province'],
            'district' => $row['district'],
            'city' => $row['city'],
            'tole' => $row['tole'],
            'property_house_no' => $row['property_house_no'],
            'property_description' => $row['property_description'],
            'owner_name' => $row['owner_name'],
            'owner_email' => $row['owner_email'],
            'owner_contact' => $row['owner_contact'],
            'is_wished' => in_array($row['property_id'], $wishes) ? true : false,
            'is_booked' => in_array($row['property_id'], $booked_prop) ? true : false,
            'book_date' => in_array($row['property_id'], $books) ? $book_date : null,
            'images' => $prty_images
        );
    }
    // display success message and properties
    $data = ['success' => true, 'message' => $properties];
    // encoding data in json format.
    echo json_encode($data);
} else {
    // display error message.
    $data = ['success' => false, 'message' => ['User Id is required!']];
    // encoding data in json format.
    echo json_encode($data);
}
