<!-- Database connection -->

<?php
$db = mysqli_connect('127.0.0.1','cms_www','7QMzcSgF2svJMcTk','sports_online');

if(mysqli_connect_error()){
  echo 'Database connection failed, check the error: '. mysqli_connect_error();
  die();
}

//Session
session_start();

//Create a constante
//Defines a constante (BASEURL) and sets it to SportsOnline

 //define('BASEURL', '/SportsOnline/');


 require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

//Functions to be used on the shop
 require_once BASEURL.'../helpers/helpers.php';

 //If there is a session set we get the user data to have access to it on other parts of the website
 if(isset($_SESSION['SBUser'])){
   $user_id = $_SESSION['SBUser'];
   $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");

   $user_data = mysqli_fetch_assoc($query);
   $fn = explode(' ', $user_data['full_name']);
   $user_data['first'] = $fn[0];
   $user_data['last'] = $fn[1];
 }

//Session flash checks
if(isset($_SESSION['success_flash'])){
  echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
  unset($_SESSION['success_flash']);//if the admin navigates to another page and/or refresh the page it will unset the satement above - shows that only once
}

if(isset($_SESSION['error_flash'])){
  echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
  unset($_SESSION['error_flash']);//if the admin navigates to another page and/or refresh the page it will unset the satement above - shows that only once
}
