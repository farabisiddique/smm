
$(document).ready(function() {

    $(".serviceBox").click(function() {
        $(this).addClass("serviceBoxActive");
        $(".serviceBox").not(this).removeClass("serviceBoxActive");

        var catId = $(this).data("servicecat");
        $(".serviceSubCatOption")

        $(".serviceSubCatOption").each(function() {
            if ($(this).data("servicecategoryid") != catId) {
                $(this).hide();

            } else {
                $(this).show();


            }
        });

        $(".serviceNameOption").each(function() {
            if ($(this).data("servicecatid") != catId) {
                $(this).hide();

            } else {
                $(this).show();


            }
        });
    });

    $('#orderSubCtgry').change(function() {
        var selectedSubCatId = $(this).val();
        var options = $('#serviceType option');

        // Hide all options in the second select initially
        options.hide();

        // Optionally, reset the second select field to a default state
        $('#serviceType').val('');
        $('<option>').val('').text('Select a Service').prependTo('#serviceType');
        $('#serviceType option:first').prop('selected', true);

        // Show only the options that match the selectedSubCatId
        options.filter(function() {
            return $(this).data('serivicesubcatid') == selectedSubCatId;
        }).show();

        // Remove the placeholder if not needed
        if($('#serviceType option:visible').length > 1){
            $('#serviceType option:first').remove();
        }
    });

    $('#serviceType').change(function() {
          var minimum = $("#serviceType option:selected").data('servicemin');
          var maximum = $("#serviceType option:selected").data('servicemax');
          var charge = $("#serviceType option:selected").data('servicecharge');
          let amount;
          
          $("#minQty").html("(Minimum is: "+minimum);
          $("#maxQty").html("Maximum is: "+maximum+")");

          $("#followQty").attr("min", minimum);
          $("#followQty").attr("max", maximum);

          var qty = $("#followQty").val();

          if(!qty){
              qty = 0;
          }

          if(!charge){
            charge = 0;
          }
          
          amount = qty * (charge/1000);
          

          amount = Math.round(amount * 100) / 100;

          

          $("#totalAmnt").val(amount);
      
    });

    $('#followQty').keyup(function() {
        var charge = $("#serviceType option:selected").data('servicecharge');
        var qty = $("#followQty").val();
      
        console.log("charge is " + charge);

        if(!qty){
          qty = 0;
        }

        if(!charge){
          charge = 0;
        }
        
        amount = qty * (charge/1000);
        

        

        amount = Math.round(amount * 100) / 100;

        $("#totalAmnt").val(amount);
      
    });

    $("#addOrder").submit(function(e){
        e.preventDefault();
        var serviceId = $("#serviceType").val();
        var pageLnk = $("#pageLnk").val();
        var followQty = $("#followQty").val();
        
      $.ajax({
          type: "POST",
          url: "./addOrder.php", 
          data: {
              serviceId: serviceId,
              pageLnk: pageLnk,
              followQty: followQty
          },
          success: function(response) {
            console.log(response);
            if(response==1){
              
            }
            else if(response==2){
              
            }
            else if(response==3){
                
                $(".addOrderResponseLabel").html("Insufficient Balance!");
                $(".addOrderResponseTxt").html("Sorry! Your account balance is insufficient to complete this order. Please add funds to your account.");
                new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
            }
          }
      });

      
    });




  
});
