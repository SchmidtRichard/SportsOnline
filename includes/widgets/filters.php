<?php
  //To know if we are already on the category, if we are we stay there
  $cat_id = ((isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']):'');

  $price_sort = ((isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']):'');//Using request instead of get or post because we want to use get or posted values for this
  $min_price = ((isset($_REQUEST['min_price']))?sanitize($_REQUEST['min_price']):'');
  $max_price = ((isset($_REQUEST['max_price']))?sanitize($_REQUEST['max_price']):'');

  $b = ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']):'');
  //Check for all brands and diplay
  $brandQ = $db->query("SELECT * FROM brand ORDER BY brand");
?>

<h3 class="text-center">Search By</h3><hr>
<h4 class="text-center">Price:</h4>

<form action="search.php" method="post">
  <input type="hidden" name="cat" value="<?=$cat_id;?>">
  <input type="hidden" name="price_sort" value="0"><!-- If low or hight is not checked - we show all -->

  <input type="radio" name="price_sort" value="low"<?=(($price_sort == 'low')?' checked':'');?>>Low to High<br>
  <input type="radio" name="price_sort" value="high"<?=(($price_sort == 'hight')?' checked':'');?>>High to Low<br><br>

  <input type="text" name="min_price" class="price-range" placeholder="Min €" value="<?=$min_price;?>"> To
  <input type="text" name="max_price" class="price-range" placeholder="Max €" value="<?=$max_price;?>"><br><hr>

  <h4 class="text-center">Brand</h4>
  <input type="radio" name="brand" value=""<?=(($b == '')?' checked':'');?>>All<br>
  <!-- Gets the brands and display for the user -->
  <?php while($brand = mysqli_fetch_assoc($brandQ)): ?>
    <input type="radio" name="brand" value="<?=$brand['id'];?>"<?=(($b == $brand['id'])?' checked':'');?>><?=$brand['brand'];?><br>
  <?php endwhile; ?>

  <input type="submit" value="Search" class="btn btn-xs btn-primary">









</form>
