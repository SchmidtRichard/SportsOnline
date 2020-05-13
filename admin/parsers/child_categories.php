<!-- When someone selects something on the parent category a AJAX request fires off to the child_categories.php and return the options to the child category -->
<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';

//require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';

  $parentID = (int)$_POST['parentID'];//in the AJAX request inside the footer.php we are sending the parentID as a post data child_categories.php
  $selected = sanitize($_POST['selected']);

  $child_query = $db->query("SELECT * FROM category WHERE parent = '$parentID' ORDER BY category");

  //pre built PHP function that starts buffering
  ob_start();

?>

<option value=""></option>

<?php while($child = mysqli_fetch_assoc($child_query)): ?>
  <option value="<?=$child['id'];?>"<?=(($selected == $child['id'])?' selected':'');?>><?=$child['category'];?></option>
<?php endwhile; ?>








<!-- Release the memory -->
<?php echo ob_get_clean();?>
