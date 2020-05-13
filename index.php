<!-- include the code for the header, header menu, navigation of the page -->
<?php
  require_once 'system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/headerfull.php';
  include 'includes/leftsidebar.php';
  include 'includes/detailsmodal.php';

  //Selects everything from our products table where featured is equals to 1
  $sql = "SELECT * FROM products WHERE featured = 1";

  //Call our object $db in our init.php file and run a method called query and pass our query statement above inside ($sql)
  $featured = $db->query($sql);
?>

  <!-- The main content of the page -->
  <!-- Bootstrap Grid -->
  <div class="col-md-8">
    <div class="row">
        <h2 class="text-center">Featured Products</h2> <!-- Bootstrap class -->

        <!-- Loops the while statement and it will assign a product with featured equals 1 to the $product -->
        <?php while($product = mysqli_fetch_assoc($featured)) : ?>

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
