<!-- Check if the user has permission to be on the page -->

<br><br>

<?php
  require_once '../system/init.php';

  //Check if the user is logged in, if not sends him back to login.php
  if(!is_logged_in('admin')){//Make sure it is an admin
    login_error_redirect();
  }

  if(!has_permission()){
    permission_error_redirect('index.php');
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  //Delete user
  if(isset($_GET['delete'])){
    $delete_id = sanitize($_GET['delete']);
    $db->query("DELETE FROM users WHERE id ='$delete_id'");
    $_SESSION['success_flash'] = 'User has been delete!';
    header('Location: users.php');
  }
  //Add new user
  if(isset($_GET['add'])){
    $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
    //Validate
    $errors = array();
    
    if($_POST){
      //Checks if the email already exist in DB
      $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
      $emailCount = mysqli_num_rows($emailQuery);
      if($emailCount != 0){
        $errors[] = 'That email already exists in the DB. Please try a different email.';
      }
      //Checks if all the fields are filled in
      $required = array('name', 'email', 'password', 'confirm', 'permissions');
      foreach($required as $f){//$f = field
        if(empty($_POST[$f])){
          $errors[] = 'You must fill out all fields.';
          break;
        }
      }
      //Check if the password is more than 6 characters
      if(strlen($password) < 6){
        $errors[] = 'Your password must be at least 6 characters.';
      }
      //Check if the passwords match
      if($password != $confirm){
        $errors[] = 'Your passwords do not match. Please try again.';
      }
      //Validates if email entered is valid
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'You must enter a valid email. Please try again.';
      }
      if(!empty($errors)){
        echo display_errors($errors);
      }else{
        //Add user to DB
        $hashed = password_hash($password,PASSWORD_DEFAULT);
        $db->query("INSERT INTO users (full_name, email, password, permissions) VALUES ('$name', '$email', '$hashed', '$permissions')");
        $_SESSION['success_flash'] = 'User has been added.';
        header('Location: users.php');
      }
    }

    ?>
    <h2 class="text-center">Add A New User</h2><hr>
    <form action="users.php?add=1" method="post">

      <div class="form-group col-md-6">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
      </div>

      <div class="form-group col-md-6">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
      </div>

      <div class="form-group col-md-6">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
      </div>

      <div class="form-group col-md-6">
        <label for="confirm">Confirm Password:</label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
      </div>

      <div class="form-group col-md-6">
        <label for="name">Permissions:</label>
        <select class="form-control" name="permissions">
          <option value="" <?=(($permissions == '')?' selected':'');?>></option>
          <option value="editor" <?=(($permissions == 'editor')?' selected':'');?>>Editor</option>
          <option value="admin, editor" <?=(($permissions == 'admin,editor')?' selected':'');?>>Admin</option>
        </select>
      </div>

      <div class="form-group col-md-6 text-right" style="margin-top: 25px;">
        <a href="users.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="Add User" class="btn btn-primary">
      </div>

    </form>
    <?php

  }else{

  $userQuery = $db->query("SELECT * FROM users ORDER BY full_name");

?>

<br>
<h2>Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add New User</a>
<br>
<hr>

<table class="table table-bordered table-striped table-condensed">
  <thead>
    <th></th>
    <th>Name</th>
    <th>Email</th>
    <th>Join Date</th>
    <th>Last Login</th>
    <th>Permissions</th>
  </thead>
  <tbody>
    <?php while($user = mysqli_fetch_assoc($userQuery)): ?>
      <tr>
        <td>
          <!-- if the user is the one logged in then he will not be able to delete himself -->
          <?php if($user['id'] != $user_data['id']): ?>
            <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove-sign"></span></a>
          <?php endif; ?>
        </td>
        <td><?=$user['full_name'];?></td>
        <td><?=$user['email'];?></td>
        <td><?=pretty_date($user['join_date']);?></td>
        <td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'User has never logged in.':pretty_date($user['last_login']));?></td>
        <td><?=$user['permissions'];?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php } include 'includes/footer.php'; ?>
