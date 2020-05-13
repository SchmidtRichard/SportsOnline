<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';//Access to init.php

  if(!is_logged_in()){
    login_error_redirect();
  }

  include 'includes/head.php';

  //Grab the hashed password from DB
  $hashed = $user_data['password'];

  $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
  $old_password = trim($old_password);//removes blank space from start and end

  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $passoword = trim($password);//removes blank space from start and end

  $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
  $confirm = trim($confirm);//removes blank space from start and end

  $new_hashed = password_hash($password, PASSWORD_DEFAULT);
  $user_id = $user_data['id'];
  $errors = array();

?>

<div id="login-form">
  <!-- Display errors below if there is any -->
  <div>
    <?php
      if($_POST){
        //Form validation
        if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
          $errors[] = 'You must fill out all fields.';
        }

        //Password is more than 6 characters
        if(strlen($password) < 6){
          $errors[] = 'Password must be at least 6 characters.';
        }

        //if new password matches confirm
        if($password != $confirm){
          $errors[] = 'The new password and confirm new password does not match. Please try again.';
        }

        //hashes the $password and check if it matches with $user['password'] (array created above)
        if(!password_verify($old_password, $hashed)){
          $errors[] = 'Your old password does not match our records.';
        }

        //Check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          //Change password
          $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
          $_SESSION['success_flash'] = 'Your password has been updated!';
          header('Location: index.php');
        }
      }
    ?>
  </div>
  <h2 class="text-center">Change Password</h2>
  <form action="change_password.php" method="post">
    <div class="form-group">
      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
    </div>

    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>


      <div class="form-group">
        <label for="confirm">Confirm New Password:</label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
      </div>

    <div class="form-group">
      <a href="index.php" class="btn btn-default">Cancel</a>
      <input type="submit" value="Change Password" class="btn btn-primary">
    </div>
  </form>

<p class="text-right"><a href="../index.php" target="_blank" alt="home">Visit Home Page</a></p>

</div>











<?php include 'includes/footer.php'; ?>
