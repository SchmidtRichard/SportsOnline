<br><br>

<?php
  require_once '../system/init.php';

  //Check if the user is logged in, if not sends him back to login.php
  if(!is_logged_in()){
    header('Location: login.php');
  }

  include 'includes/head.php';
  include 'includes/navigation.php';
?>

<br><br><br>
Administrator Home <!-- Place Holders -->

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php include 'includes/footer.php'; ?>
