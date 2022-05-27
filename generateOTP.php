<?php

// function to generate OTP
function generateOTP()
{
    // defining digits
    $digits = "0123456789";
    // initializing the variable
    $OTP = "";
    // looping through the digits
    for ($i = 0; $i < 6; $i++) {
        $OTP .= $digits[rand(0, strlen($digits) - 1)];
    }
    // returning the OTP
    return $OTP;
}
