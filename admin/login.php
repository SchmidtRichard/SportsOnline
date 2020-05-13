<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';//Access to init.php
  include 'includes/head.php';

  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $email = trim($email);//removes blank space from start and end
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $passoword = trim($password);//removes blank space from start and end
  $errors = array();

//Hash
// $password = 'password';
// $hashed = password_hash($password, PASSWORD_DEFAULT);
// echo $hashed;
?>

<style media="screen">
  body{
    background-image: url("/images/background7.jpg");
    background-size: 100vw 100vh;
    background-attachment: fixed;
  }
</style>

<div id="login-form">
  <!-- Display errors below if there is any -->
  <div>
    <?php
      if($_POST){
        //Form validation
        if(empty($_POST['email']) || empty($_POST['password'])){
          $errors[] = 'You must enter your email and password.';
        }
        //Validate email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){//if it is not a valid email display error
          $errors[] = 'Please enter a valid email address.';
        }
        //Password is more than 6 characters
        if(strlen($password) < 6){
          $errors[] = 'Password must be at least 6 characters.';
        }
        //Check if the email exists in the DB
        $query = $db->query("SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($query);
        $userCount = mysqli_num_rows($query);
        echo $userCount;
        if($userCount < 1){
          $errors[] = 'That email does not exist. Please try again.';
        }
        //hashes the $password and check if it matches with $user['password'] (array created above)
        if(!password_verify($password, $user['password'])){
          $errors[] = 'The password does not match. Please try again.';
        }
        //Check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          //Create hashed password and verify if it matches what is in the DB
          //Log user in
          $user_id = $user['id'];
          login($user_id);//pass the user id
        }
      }
    ?>
  </div>
  <h2 class="text-center">Login</h2>
  <form action="login.php" method="post">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>

    <div class="form-group">
      <input type="submit" value="Login" class="btn btn-primary">
    </div>
  </form>

<p class="text-right"><a href="../index.php" target="_blank" alt="home">Visit Home Page</a></p>

</div>

<?php include 'includes/footer.php'; ?>
