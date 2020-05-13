</div><br><br><br><br><br>



<!-- Footer with a bootstrap class of text -->
<div class="col-md-12 text-center">&copy; Copryright 2018 Sports Online</div>

<script>

  //Update the sizes and quantity in the modal in products.php
  function updateSizes(){
    var sizeString = '';
    for(var i=1; i<=12;i++){
      if(jQuery('#size'+i).val()!=''){
        sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
      }
    }
    //Plug the value to sizes & quantity preview in products.php
    jQuery('#sizes').val(sizeString);
  }




  function get_child_options(selected){
    if(typeof selected == 'undefined'){
      var selected = '';
    }
    //Find out the value of the select below
    var parentID = jQuery('#parent').val();
    jQuery.ajax({
      //Create object
      url: '/admin/parsers/child_categories.php',
      type: 'POST',
      //Creates a data object - parentID is the key of the object and the second parentID is from the variable above
      data: {parentID : parentID, selected : selected},
      //get the data from the child_categories.php and put inside the function below
       success: function(data){
         jQuery('#child').html(data);
       },
       error: function(){
         alert("Something went wrong with the child options")},
    });
  }
  //Listen to the change - change function below
  jQuery('select[name="parent"]').change(function(){
    get_child_options();
  });
</script>






<br><br>

</body>
</html>
