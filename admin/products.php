<br><br>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';//Access to init.php

  //Check if the user is logged in, if not sends him back to login.php
  if(!is_logged_in()){
    login_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  //Delete product from DB
  if(isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted = 1, featured = 0 WHERE id = '$id'");
    header('Location: products.php');
  }

  $dbPath = '';

  //Code for the button add on the top of the page
  if(isset($_GET['add']) || isset($_GET['edit'])){
    $brand_query = $db->query("SELECT * FROM brand ORDER BY brand");
    $parent_query = $db->query("SELECT * FROM category WHERE parent = 0 ORDER BY category");
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
    $category = ((isset($_POST['child'])) && !empty($_POST['child'])?sanitize($_POST['child']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
    $sizes = rtrim($sizes,',');
    $saved_image = '';

    //Takes the value from our edit and assign it to $edit_id
    if(isset($_GET['edit'])){
      $edit_id = (int)$_GET['edit'];
      $product_results = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
      $product= mysqli_fetch_assoc($product_results);

      if(isset($_GET['delete_image'])){
        $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
        // echo $image_url;
        // unset($image_url);
        unlink($image_url);
        $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
        header('Location: products.php?edit='.$edit_id);
      }

      $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['category']);
      $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);//Comes from DB
      $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
      $parent_query2 = $db->query("SELECT * FROM category WHERE id = '$category'");//Returns the child element
      $parent_result = mysqli_fetch_assoc($parent_query2);

      $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parent_result['parent']);
      $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
      $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product['list_price']);
      $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
      $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
      $sizes = rtrim($sizes,',');
      $saved_image = (($product['image'] != '')?$product['image']:'');
      $dbPath = $saved_image;
    }

    if(!empty($sizes)){
      //If not empty and posted we build the sizes arrays
      $sizeString = sanitize($sizes);
      $sizeString = rtrim($sizeString,',');//Remove the coma from the end of the array
      $sizesArray = explode(',',$sizeString);//for each coma we make a new element in the array

      //Plug the values to the value inside the modal
      $sArray = array();
      $qArray = array();
      foreach($sizesArray as $ss){
        $s = explode(':', $ss);
        $sArray[] = $s[0];//The first element of the array is the size
        $qArray[] = $s[1];//The second element of the array is the quantity
      }

    }else{
      //If post is empty it will set to an empty array
      $sizesArray = array();
    }

    if($_POST){
      //$dbPath = '';
      $errors = array();

      //Form validation
      $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');//Fields that need to be filled out
      // foreach($required as $field){
      //   if($_POST[$field] == ''){
      //     $errors[] = 'All Fields With an * are required.';
      //     break;//If more than one field is blank it will print the message above just once because it will print once
      //   }
      // }

      //check the pic upload
      if(!empty($_FILES)){
        //var_dump($_FILES);
        //Set the name
        $photo = $_FILES['photo'];//Photo is the first element of the array
        $name = $photo['name'];
        $nameArray = explode('.',$name);
        $fileName = $nameArray[0];
        $fileExt = $nameArray[1];
        $mime = explode('/',$photo['type']);
        $mimeType = $mime[0];
        $mimeExt = $mime[1];
        $tmpLoc = $photo['tmp_name'];
        $fileSize = $photo['size'];

        $allowed = array('png','jpg','jpeg','gif');

        //Upload pic location
        //$uploadName = md5(microtime()).'.'.$fileExt;
        $uploadName = $fileName.'.'.$fileExt;
        $uploadPath = BASEURL.'images/products/'.$uploadName;
        $dbPath = '/images/products/'.$uploadName;
        //C:\xampp\htdocs\SportsOnline\images\products

        //Validate the pic to be uploaded
        if($mimeType != 'image'){
          $errors[] = 'The file must be an image.';
        }
        //Validate pic extension
        if(!in_array($fileExt, $allowed)){
          $errors[] = 'The photo extension must be a pgn, jpg, jpeg or gif.';
        }
        //Validate pic size
        if($fileSize > 25000000){
          $errors[] = 'The file size must be under 25MB.';
        }
        //Validate pic extension
         if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
           $errors[] = 'File extension does not match the file.';
         }
      }

      if(!empty($errors)){
        echo display_errors($errors);
      }else{
        if(!empty($_FILES)){
        //Upload file and insert into DB
        move_uploaded_file($tmpLoc,$uploadPath);

        //move_uploaded_file($_FILES['tmp_name']['fileExt']);

      }

        // $insertSql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `category`, `sizes`, `image`, `description`)
        // VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$sizes','$dbPath', '$description')";
        //
        // if(isset($_GET['edit'])){
        //   $insertSql = "UPDATE products SET `title` = '$title', `price` = '$price', `list_price` = '$list_price', `brand` = '$brand', `category` = '$category', `sizes` = '$sizes',
        //   `image` = '$dbPath', `descritpion` = '$description'
        //   WHERE id = '$edit_id'";



        $insertSql = "INSERT INTO products (title, price, list_price, brand, category, sizes, image, description)
        VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$sizes','$dbPath', '$description')";

        if(isset($_GET['edit'])){
          $insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', category = '$category', sizes = '$sizes',
          image = '$dbPath', descritpion = '$description'
          WHERE id = '$edit_id'";

        }

        $db->query($insertSql);
        header('Location: products.php');
      }
    }

?>

<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add New');?> Product</h2><hr>

<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
  <div class="form-group col-md-3">
    <label for="title">Title*:</label>
    <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
  </div>

  <div class="form-group col-md-3">
    <label for="brand">Brand*:</label>
    <select class="form-control" id="brand" name="brand">
      <option value=""<?=(($brand == '')?' selected':'');?>></option>
      <?php while($b = mysqli_fetch_assoc($brand_query)): ?>
        <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="form-group col-md-3">
    <label for="parent">Parent Category*:</label>
    <select class="form-control" id="parent" name="parent">
      <option value=""<?=(($parent == '')?' selected':'');?>></option>
      <?php while($p = mysqli_fetch_assoc($parent_query)): ?>
        <option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?' selected':'');?>><?=$p['category'];?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="form-group col-md-3">
    <label for="child">Child Category*:</label>
    <select id="child" name="child" class="form-control">
    </select>
  </div>

  <div class="form-group col-md-3">
    <label for="price">Price*:</label>
    <input type="text" class="form-control" id="price" name="price" value="<?=$price;?>">
  </div>

  <div class="form-group col-md-3">
    <label for="list_price">List Price:</label>
    <input type="text" class="form-control" id="list_price" name="list_price" value="<?=$list_price;?>">
  </div>

  <div class="form-group col-md-3">
    <label>Quantity & Sizes*:</label>
    <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
  </div>

  <!-- The modal will generate the info to be displayed below -->
  <div class="form-group col-md-3">
    <label for="sizes">Sizes & Quantity Preview</label>
    <input type="text" class="form-control" name="sizes" id="sizes" value="<?=$sizes;?>" readonly>
  </div>
  <div class="form-group col-md-6">
    <?php if($saved_image != ''): ?>
      <div class="saved-image">
        <img src="<?=$saved_image;?>" alt="saved image"/><br>
        <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
      </div>
    <?php else: ?>
      <label for="photo">Product Photo:</label>
      <input type="file" name="photo" id="photo" class="form-control-file">
  <?php endif; ?>
  </div>

  <div class="form-group col-md-6">
    <label for="description">Description:</label>
    <textarea style="resize:vertical;" id="description" name="description" class="form-control" rows="12"><?=$description;?></textarea>
  </div>

  <div class="form-group pull-right">
    <a href="products.php" class="btn btn-default">Cancel</a>
    <input type="submit" value="<?=((isset($_GET['edit']))?'Save Changes':'Add Product');?>" class="btn btn-success">
  </div>

<div class="clearfix"></div>

</form>

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModal">Size & Quantity</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">

        <!-- The loop runs 12 times -->
        <?php for($i=1; $i <= 12; $i++): ?>
          <div class="form-group col-md-4">
            <label for="size<?=$i;?>">Size:</label><!-- we will get size e.g. 1,2,3... from the loop -->
            <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
          </div>

          <div class="form-group col-md-2">
            <label for="qty<?=$i;?>">Quantity:</label><!-- we will get size e.g. 1,2,3... from the loop -->
            <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
          </div>

        <?php endfor; ?>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php

  }else{

  //SQL Statement to populate the table below
  //Deleted column on DB - all products are set to 0 as default, when we "delete" a product is changed to 1, it will only hide the product but delete it
  $sql = "SELECT * FROM products WHERE deleted = 0";
  $products_results = $db->query($sql);

  //if the plus/minus button is pressed we will get the id
  if(isset($_GET['featured'])){
    $id = (int)$_GET['id'];//What is determined by the link when plus/minus button are hovered
    $featured = (int)$_GET['featured'];//What is determined by the link when plus/minus button are hovered

    $featured_sql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";//Update the featured column in the table products to 1 or 0
    //$featured_resuls = $db->query($featured_sql);
    $db->query($featured_sql);
    header('Location: products.php');
  }
?>

<h2 class="text-center">Products</h2>

<!-- Button to add a new product -->
<a href="products.php?add=1" class="btn btn-success pull-right">Add New Product</a><div class="clearfix"></div>

<hr>

<!-- Table to -->
<table class="table table-bordered table-condensed table-striped table-hover">
  <thead>
    <th>Edit/Delete</th>
    <th>Product</th>
    <th>Price</th>
    <th>Categories</th>
    <th>Featured</th>
    <th>Sold</th>
  </thead>
  <tbody>
    <!-- Loop to display our products taken from the DB -->
    <!--For each product in the DB we will add it in our associative array  -->
    <?php while($product = mysqli_fetch_assoc($products_results)):

      //Set the category of the child element
      $child_id = $product['category'];
      $category_sql = "SELECT * FROM category WHERE id = '$child_id'";
      $result = $db->query($category_sql);
      $child = mysqli_fetch_assoc($result);//Associative array of $child from the child element

      //Get the parent
      $parent_id = $child['parent'];
      $parent_sql = "SELECT * FROM category WHERE id = '$parent_id'";

      $parent_result = $db->query($parent_sql);
      $parent = mysqli_fetch_assoc($parent_result);

      $category = $parent['category'].'-'.$child['category'];

      ?>
      <!-- Makes a new row for each interaction -->
      <tr>
        <td>
          <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
        <td><?=$product['title'];?></td>
        <td><?=money($product['price']);?></td>
        <td><?=$category;?></td>
        <!-- Link and the get variable FEATURED= - products.php?featured= 0 or 1 based on the oposito of what is on the DB adn the id the product id -->
        <td><a href="products.php?featured=<?=(($product['featured'] ==0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
          <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span></a>
          <!-- If the product is featured it will display Featured Product and if not Not Featured Product -->
          &nbsp <?=(($product['featured'] == 1)?'Featured Product':'Not Featured Product');?>
        </td>
        <td>Not finished yet</td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php

}//End the else statement on the very top

include 'includes/footer.php'; ?>

<script>
  jQuery('document').ready(function(){
    get_child_options('<?=$category;?>');


  });
</script>
