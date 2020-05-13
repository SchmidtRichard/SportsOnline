<!-- include the code for the header, header menu, navigation of the page -->
<?php
  require_once 'system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/headerfull.php';
  include 'includes/leftsidebar.php';
  //include 'includes/detailsmodal.php';

  $sql = "SELECT * FROM products";
  $cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
  
  if($cat_id == ''){
    $sql .= " WHERE deleted = 0";
  }else{
    $sql .= " WHERE category = '{$cat_id}' AND deleted = 0";
  }

  $price_sort = (($_POST['price_sort'] != '')?sanitize($_POST['price_sort']):'');
  $min_price = (($_POST['min_price'] != '')?sanitize($_POST['min_price']):'');
  $max_price = (($_POST['max_price'] != '')?sanitize($_POST['max_price']):'');
  $brand = (($_POST['brand'] != '')?sanitize($_POST['brand']):'');

  if($min_price != ''){
    $sql .= " AND price >= '{$min_price}'";
  }
  if($max_price != ''){
    $sql .= " AND price <= '{$max_price}'";
  }
  if($brand != ''){
    $sql .= " AND brand ='{$brand}'";
  }
  if($price_sort == 'low'){
    $sql .= " ORDER BY price";
  }
  if($price_sort == 'high'){
    $sql .= " ORDER BY price DESC";
  }

  //Call our object $db in our init.php file and run a method called query and pass our query statement above inside ($sql)
  $productQ = $db->query($sql);
  $category = get_category($cat_id);
?>

  <!-- The main content of the page -->
  <!-- Bootstrap Grid -->
  <div class="col-md-8">
    <div class="row">
      <?php if($cat_id != ''): ?>
        <h2 class="text-center"><?=$category['parent']. ' ' . $category['child'];?></h2> <!-- Bootstrap class -->
      <?php else: ?>
        <h2 class="text-center">Sports Online</h2>
      <?php endif; ?>

        <!-- Loops the while statement and it will assign a product with featured equals 1 to the $product -->
        <?php while($product = mysqli_fetch_assoc($productQ)) : ?>

          <!-- Below each time we access the array using the $product above, we pass the index of the array e.g. ['image']
          that is printed by the echo statement -->
          <!-- Create columns with grid of 4 -->
          <div class="col-md-3 text-center">
            <h4><?= $product['title']; ?></h4>
            <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-design">
            <p class="list-price text-danger">List Price <s>€ <?= $product['list_price']; ?></s></p>
            <p class="price">Sports Online Price: € <?= $product['price']; ?></p>

            <!-- Button that uses bootstrap modal, pop-up that din the background -->
            <!-- Bootstrap data toggle modal, it is a custom attribute that comes from JS function that opens the modal -->
            <!-- onclick allow us to run our own JS function -->
            <!-- we pass the id of our product from our DB as the parameter of the JS function to get the relevant product,
            we use our $product "array" to get the id from the DB -->
            <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

<?php
include 'includes/rightsidebar.php';
include 'includes/footer.php';
?>
