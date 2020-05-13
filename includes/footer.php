</div><br><br><br><br>

<hr>

<!-- Footer with a bootstrap class of text -->
<div class="col-md-12 text-center">&copy; Copryright 2018 Sports Online</div>

<br><br>

<!-- Target the window scroll function -->
<script>

    jQuery(window).scroll(function(){ //Anonimous function
      var verticalScroll = jQuery(this).scrollTop(); //Assigns the variable vertialScroll to the scrollTop function

      //console.log(verticalScroll); //Uses the console on the web browser to see how many pixes we scroll
      jQuery('#logotext').css({

        //Makes the logo moves with the background image as we scroll the page down
        //Takes the amount of pixes we scroll and divide by 2 and it translate it down
        "transform" : "translate(0px, "+verticalScroll/1.3+"px)"
      });
    });


    //JS function to send the JSON object DATA to our detailsmodal.php file and get information
    function detailsmodal(id){//Annomiour function
      var data = {"id" : id};//the data object equals a JSON string - get the ID that is set on the button on the index.php file

      //AJAX call below - the page does not need to be reloaded for the function below to run
      jQuery.ajax({
        url : '/includes/detailsmodal.php',
        method : "post",
        data: data, //the data will be post to our page where our url goes to

        //Annonimous function - pass the data from success to the function
        success: function(data){

          //Add all the information we get back from our detailsmodal.php file
          jQuery('body').append(data);//append what comes from success to the body (data)

          //Open our modal - uses the id given to it on the detailsmodal.php file (details-modal)
          jQuery('#details-modal').modal('toggle');//
        },

        error: function(x,e){

          //alert("Something did not work as it was expected, try again later.");

          if (x.status==0) {
              alert('You are offline!!\n Please Check Your Network.');
          } else if(x.status==404) {
              alert('Requested URL not found.');
          } else if(x.status==500) {
              alert('Internel Server Error.');
          } else if(e=='parsererror') {
              alert('Error.\nParsing JSON Request failed.');
          } else if(e=='timeout'){
              alert('Request Time out.');
          } else {
              alert('Unknow Error.\n'+x.responseText);
          }

        }
      });
    }


</script>
</body>
</html>
