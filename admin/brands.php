<br><br>

<?php
  require_once '../system/init.php';

  //Check if the user is logged in, if not sends him back to login.php
  if(!is_logged_in()){
    login_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  //Get brands from the DB
  $sql = "SELECT * FROM brand ORDER BY brand";
  //Pass the above string inside the query method query($sql)
  $results = $db->query($sql);

  $errors = array();

  //Edit brand
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    //Grab the row out of the DB
    $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
    $edit_result = $db->query($sql2);

    //eBrand is an associative array of our row that we are editing and getting from our sql statement
    $eBrand = mysqli_fetch_assoc($edit_result);
  }

  //Delete brand
  //isset checks if $_GET checks if the set and not empty
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);

    //SQL statement to delete the brand from our DB
    $sql = "DELETE FROM brand WHERE id = '$delete_id'";
    $db->query($sql);
    //Redirect back to the page
    header('Location: brands.php');
  }

  //If add form is subitted do the below
  if(isset($_POST['add_submit'])){ //After hits the submit button
    //Variable $brand that comes from our $_POST['brand'] array from our form
    $brand = sanitize($_POST['brand']);
    //Check if the form is not blank
    if($_POST['brand'] == ''){
      $errors[] .= 'You must enter a brand!';
    }
    //Check if the brand exists in DB
    $sql = "SELECT * FROM brand WHERE brand = '$brand'";
    if(isset($_GET['edit'])){
      $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";//If we are editing we will use this SQL statement isntead of the above one
    }
    $result = $db->query($sql);

    //Count how many rows are returned
    $count = mysqli_num_rows($result);
    if($count > 0){
        $errors[] .= $brand.' already exists. Please choose another brand name!';
    }
    //Display the errors
    if(!empty($errors)){
      echo display_errors($errors);
    }else{
      //Add brand to the DB
      $sql = "INSERT INTO brand (brand) VALUES ('$brand')";

      //If we are editing we will use this SQL statement isntead of the above one
      if(isset($_GET['edit'])){
        $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
      }
      //Run the query above
      $db->query($sql);
      //PHP function header to refresh the page to display the new added brand
      header('Location: brands.php');
    }
  }

?>

<br><br><br>
<h2 class="text-center"> Brands</h2><hr> <!-- Place Holders -->

<!-- Form to add brands - Bootstrap -->
<div class="text-center">
  <!-- PHP ternary operator - before the : is true and after is false - if it is set-->
  <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
    <div class="form-group">

      <?php
      $brand_value = '';
      if(isset($_GET['edit'])){
        $brand_value = $eBrand['brand'];//If true it will echo below on the $brand_value the value taken from our associative array
      }else{
        if(isset($_POST['brand'])){
          $brand_value = sanitize($_POST['brand']);
        }
      } ?>

      <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add a New'); ?> Brand:</label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value; ?>">
        <?php if(isset($_GET['edit'])): ?>
          <a href="brands.php" class="btn btn-default">Cancel</a>
        <?php endif; ?>
      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Brand" class="btn btn-success">
    </div>
  </form>
</div><hr>


<!-- Bootstrap Table -->
<table class="table table-bordered table-striped table-auto table-hover table-condensed">
  <thead>
    <th></th>
    <th>Brand</th>
    <th></th>
  </thead>
  <tbody>
    <?php while($brand = mysqli_fetch_assoc($results)): ?>
      <tr>
        <!-- edit=1 will be the id of the brand taken from our DB - btn is a bootstrap class to create a button like shape around the glyphicon -->
        <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
        <td><?=$brand['brand'];?></td>
        <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>









<?php include 'includes/footer.php'; ?>
