<?php

  //echo 'helpers';
function display_errors($errors){
  $display = '<br><br><ul class="bg-danger">';
  //For each error we will add a list item inside
  foreach($errors as $error){
    $display .= '<li class="text-danger">'.$error.'</li>';//Print the list and concatenate $error
  }
  $display .= '</ul>';
  return $display;
}

function sanitize($dirty){
  //Pre built PHP function that will turn the tags into HTML entites and print to the screen
  //instead of being enacted and also escape quotes and any other harmful code

  //ENT_QUOTES takes care of single and double quotes
  //UTF-8 defines our character set in order to block people using other characters set so they cannot pass by
    return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

//To add the euro simbol to it and it format to 2 decimal places
function money($number){
  return '€'.number_format($number,2);
}

function login($user_id){
    //create the session
    $_SESSION['SBUser'] = $user_id;//Session name SBUser - set the session for the $user_id

    //Update the DB with the last login
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");

    //Set another session - creates a success flash and refirects to the index.php
    $_SESSION['success_flash'] = 'You are now logged in';
    header('Location: index.php');
}

function is_logged_in(){
  if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
    return true;
  }
  return false;
}

//Redirects the user back to the login.php if he is not logged in
function login_error_redirect($url = 'login.php'){
  //Set section
  $_SESSION['error_flash'] = 'You must be logged in to access that page!';
  header('Location: '.$url);
}

//Redirects the user back to the login.php if he is not logged in
function permission_error_redirect($url = 'login.php'){
  //Set section
  $_SESSION['error_flash'] = 'You do not have permission to access that page!';
  header('Location: '.$url);
}

//Check if the user have specific permission
function has_permission($permission = 'admin'){
  global $user_data;
  $permissions = explode(',', $user_data['permissions']);

  //checks if permision of admin exists inside of $permissions array
  if(in_array($permission, $permissions,true)){
    return true;
  }
  return false;
}

//Format dates to display on the users table
function pretty_date($date){
  return date("d M Y h:i A", strtotime($date));
}

//Function to get the child elements from the categories and display once the menu on the top is clicked
function get_category($child_id){
  global $db;
  $id = sanitize($child_id);
  $sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id 'cid', c.category aS 'child'
          FROM category c
          INNER JOIN category p
          ON c.parent = p.id
          WHERE c.id = '$id'";

  $query = $db->query($sql);
  $category = mysqli_fetch_assoc($query);

  return $category;//return the array





}






















//
