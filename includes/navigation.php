<?php
$sql = "SELECT * FROM category WHERE parent = 0";

// use our DB object ($db) - call a method of our object (query) - run our sql statement above ($sql)
$parent_query = $db->query($sql);

?>




<!-- Top Nav Bar -->
<!-- bootstrap classes below -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Sports Online</a>
    <ul class="nav navbar-nav">

      <!-- List item for each parent in our DB -->
      <!-- Loop to get the items in our DB - loop for each parent in ou DB -->
      <!-- fetch_assoc - does a fetch associative array by taking it and storing in $parent -->
      <?php while($parent = mysqli_fetch_assoc($parent_query)): ?>

        <!-- Store the id of the parent in the DB in the variable below -->
        <!-- each iteration on the loop will store the next data row on the array - the id key below comes from the primary key on the DB -->
        <?php
          $parent_id = $parent['id'];
          //Selects everything in the category table where the parent is equal $parent_id
          $sql2 = "SELECT * FROM category WHERE parent = '$parent_id'";
          //Run the query - use our $db object
          $child_query = $db->query($sql2);
        ?>

        <!-- Items to be displayed on the top menu -->
        <li class="dropdown">

          <!-- Data Toggle allow us to run a JS script function without us calling JS, it is predifined on Bootstrap -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <!-- Runs the loop below to print the child from the DB -->
            <?php while($child = mysqli_fetch_assoc($child_query)) : ?>
              <li><a href="category.php?cat=<?=$child['id'];?>"><?php echo $child['category']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>









</nav>
