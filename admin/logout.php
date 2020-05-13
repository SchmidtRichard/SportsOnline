<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';//Access to init.php
  unset($_SESSION['SBUser']);
  header('Location: login.php');
?>
