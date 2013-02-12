<?php

/*
  The main logic of the application. There are a few steps here:
   1. Get the user input from the form
   2. Confirm the form was submitted
   3. Validate the form submission
   4. Open the database connection
   5. Insert the information into the database
   6. Trigger the display of the signature regeneration
*/

// Tracks what fields have validation errors
$errors = array();
// Default to showing the form
$show_form = true;

// 1. Get the input from the form
//  Using the PHP filters are the most secure way of doing it
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$output = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);

// 2. Confirm the form was submitted before doing anything else
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 3. Validate that a name was typed in
  if (empty($name)) {
    $errors['name'] = true;
  }

  // 3. Validate that the submitted signature is in an acceptable format
  if (!json_decode($output)) {
    $errors['output'] = true;
  }

  // No validation errors exist, so we can start the database stuff
  if (empty($errors)) {
  


    // My database credentials are stored in environment variables for portability
   
 
    $sig_hash = sha1($output);
    $created = time();
    $ip = $_SERVER['REMOTE_ADDR'];


  $hostname = "localhost";
  $database = "lwslab_sig";
  $username = "lwslab_sig";
  $password = "qbs537";
  
$fields = "signator, signature, sig_hash, ip, created";
$values = "'$name','$output','$sig_hash','$ip','$created'";

mysql_connect($hostname, $username, $password) or die("Unable to connect to database");
mysql_select_db("$database") or die("Unable to select database");
$query = "insert into signatures ($fields) values ($values)";

mysql_query($query);



    // 6. Trigger the display of the signature regeneration
    $show_form = false;
  }
}