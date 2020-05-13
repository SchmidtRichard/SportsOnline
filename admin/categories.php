<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';//Access to our helpers and config

  //Check if the user is logged in, if not sends him back to login.php
  if(!is_logged_in()){
    login_error_redirect();
  }

//require_once '../system/init.php';

//C:\xampp\htdocs\SportsOnline\admin

  include 'includes/head.php';
  include 'includes/navigation.php';

  $sql="SELECT * FROM category WHERE parent = 0";
  $result = $db->query($sql);

  $errors = array();
  $category = '';
  $post_parent = '';

  //Edit Category
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);#
    $edit_sql = "SELECT * FROM category WHERE id = '$edit_id'";//Return the info
    $edit_result = $db->query($edit_sql);
    $edit_category = mysqli_fetch_assoc($edit_result);
  }
  //Delete Category
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];//Pull the value from the URL
    $delete_id = sanitize($delete_id);
    //Check if item to be deleted is not a parent
    $sql = "SELECT * FROM category WHERE id = '$delete_id'";
    $result = $db->query($sql);
    $category = mysqli_fetch_assoc($result);//Returns the category
    if($category['parent'] == 0){
      $sql = "DELETE FROM category WHERE parent = '$delete_id'";//Delete the parent and its child
      $db->query($sql);
    }
    $delete_sql = "DELETE FROM category WHERE id = '$delete_id'";
    $db->query($delete_sql);
    header('Location: categories.php');
  }

  //Process Form
  if(isset($_POST['parent']) && !empty($_POST['category'])){
  //if(isset($_POST) && !empty($_POST)){
    $post_parent = $_POST['parent'];
    $post_parent = sanitize($post_parent);

    $category = $_POST['category'];
    $category = sanitize($category);

    $$post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);

    $sql_form = "SELECT * FROM category WHERE category = '$category' AND parent = '$post_parent'";
    //to edit
    if(isset($_GET['edit'])){
      $id = $edit_category['id'];//Return the $edit_category associative array set above and set it equal $id
      $sql_form = "SELECT * FROM category WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'";//check if the id is not equal id category otherwise we cannot update the id
    }
    $form_result = $db->query($sql_form);
    $count = mysqli_num_rows($form_result);//Tell us how many are in our DB with the where clause above

    //Errors
    //Check if category is blank
    if($category == ''){
      $errors[] .= 'The category cannot be blank. Please fill in the form.';
    }

    //if alredy exists in the DB
    if($count > 0){
      $errors[] .= $category. ' already exists. Please choose a new category to add to the DB.';
    }

    //Display errors or update DB
    if(!empty($errors)){
      //Display errors that were built in the helpers file
      $display = display_errors($errors);?>

      <!-- jQuery to plug the HTML into here -->
      <script>
        //When the page is downloading
        jQuery('document').ready(function(){
          jQuery('#errors').html('<?=$display;?>');//Select the div with the id of errors
        });
      </script>

    <?php }else{
      //Insert and Update DB
      $updatesql = "INSERT INTO category (category, parent) VALUES ('$category','$post_parent')";
      //To update
      if(isset($_GET['edit'])){
        $updatesql = "UPDATE category SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
      }
      $db->query($updatesql);
      header('Location: categories.php');
    }
  }

  //To add what is been edited in the category field inside the form
  $category_value = '';
  $parent_value = 0;
  if(isset($_GET['edit'])){
    $category_value = $edit_category['category'];
    $parent_value = $edit_category['parent'];
  }else{
    //If edit is not set, check if post is set in order to throw an error when the form is submited to show a e.g. typo etc
    if(isset($_POST)){
      $category_value = $category;//Get the post value set in the process form if statement
      $parent_value = $post_parent;
    }
  }
?>

<h2 style="padding-top: 35px" class="text-center">Categories</h2><hr>
<div class="row">

    <!-- Form -->
    <div class="col-md-6">
      <!-- In order to edit we need to post back with the edit info in the URL - PHP ternary operator used for it below -->
      <!-- If edit is set in the URL as a get variable we will add the TRUE to our action, if it is not set we will add nothing (blank string) -->
      <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
        <legend><?=((isset($_GET['edit']))?'Edit':'Add A');?> Category</legend>
        <div id="errors"></div>
        <div class="form-group">
          <label for="parent">Parent</label>
          <select class="form-control" name="parent" id="parent">

              <option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>Parent</option>

                <?php while($parent = mysqli_fetch_assoc($result)): ?>
                  <option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['id'])?' selected="selected"':'');?>><?=$parent['category'];?></option>
                <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="category">Category</label>
          <input type="text" class="form-control" id="category" name="category" value="<?=$category_value;?>">
        </div>
        <div class="form-group">
          <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Category" class="btn btn-success">
        </div>
      </form>
    </div>

    <!-- Category Table -->
    <div class="col-md-6">
      <table class="table table-bordered table-hover">
        <thead>
          <th>Category</th>
          <th>Parent</th>
          <th>Edit/Delete</th>
        </thead>
        <tbody>
          <?php
            $sql="SELECT * FROM category WHERE parent = 0";
            $result = $db->query($sql);

            while($parent = mysqli_fetch_assoc($result)):
                $parent_id = (int)$parent['id'];
                $sql2 = "SELECT * FROM category WHERE parent = '$parent_id'";
                $child_result = $db->query($sql2);
            ?>
            <tr class="bg-primary">
              <td><?=$parent['category'];?></td>
              <td>Parent</td>
              <td>
                <!-- edit=1 will be the id of the brand taken from our DB - btn is a bootstrap class to create a button like shape around the glyphicon -->
                <a href="categories.php?edit=<?=$parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="categories.php?delete=<?=$parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
              </td>
            </tr>
              <!-- nested loop to get the child -->
              <?php while($child = mysqli_fetch_assoc($child_result)): ?>
                <tr class="bg-info">
                  <td><?=$child['category'];?></td>
                  <td><?=$parent['category'];?></td>
                  <td>
                    <a href="categories.php?edit=<?=$child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="categories.php?delete=<?=$child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                  </td>
                </tr>
              <?php endwhile; ?>
          <?php endwhile;?>
        </tbody>
      </table>
    </div>
</div>

<?php include 'includes/footer.php';
