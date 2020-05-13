<?php

  //require_once 'system/init.php'; //stoped working, the below seems to be working now

require_once $_SERVER['DOCUMENT_ROOT'].'../system/init.php';

if(isset($_GET['id'])){
  $id = $_POST['id'];
  $id = (int)$id;
  $sql = "SELECT *FROM products WHERE id = '$id'";
  $result = $db->query($sql);
  $product = mysqli_fetch_assoc($result);

  if(isset($_GET['brand'])){
  $brand_id = $product['brand'];
  $sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
  $brand_query = $db->query($sql);
  $brand = mysqli_fetch_assoc($brand_query);
}
if(isset($_GET['sizes'])){
  $sizestring = $product['sizes'];
  $sizestring = rtrim($sizestring,',');
  $size_arrray = explode(',', $sizestring);
}
}

?>

<!-- php function that starts a buffer that reads all the code below and add to the buffer and send it back the AJAX request
as the DATA object and when it gets to be bottom it will free the memory (cleans the buffer) with the ob_get_clean(); function -->
<?php ob_start(); ?>

<!-- Details Modal - Light Box -->
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">

  <!-- Large modal -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" onclick="closeModal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        console.log($product);

        <h4 class="modal-title text-center"><?= $product['title']; ?></h4>
        console.log($product);
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <div class="center-block">
                console.log($product);
                <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="details img-responsive"> <!-- Bootstrap class that makes image responsive -->
                console.log($product);
              </div>
            </div>
            <div class="col-sm-6">
              <h4>Details</h4>
              <p><?= nl2br($product['description']); ?></p>

              <!-- Horizontal Row -->
              <hr>
              <p>Price: € <?=$product['price']; ?></p>
              console.log($brand);
              <p>Brand: <?=$brand['brand']; ?></p>
              <form action="add_cart.php" method="post">
                <div class="form-group">

                  <!-- Adds an input inside the div below and give a class with a column to control its size -->
                  <div class="col-xs-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                  </div>
                  <div class="col-xs-9"></div>
                </div><br><br><br>
                <div class="form-group">
                  <label for="size">Size:</label>
                  <select name="size" id="size" class="form-control">
                    <option value=""></option>

                    <?php foreach($size_array as $string){
                      $string_array = explode(':', $string);
                      $size = $string_array[0];
                      $quantity = $string_array[1];
                      echo '<option value="'.$size.'">'.$size.' ('.$quantity.' Available)</option>';
                    } ?>

                  </select>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-default" onclick="closeModal()">Close</button>

        <!-- Bootstrap glyphs from the Glyphicon Halflings set to add the cart icon -->
        <button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Basket</button>
      </div>
    </div>
  </div>
</div>

<script>
  function closeModal(){
    jQuery('#details-modal').modal('hide');
    setTimeout(function(){
      jQuery('#details-modal').remove();//Removes details modal
      jQuery('.modal-backdrop').remove();//Removes the black background
    },500);
  }
</script>

<?php echo ob_get_clean(); ?>
