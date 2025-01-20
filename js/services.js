$(".seeServiceDetails").click(function () {
      // console.log();
      var serviceName = $(this).data("servicename");
      var servicecharge = $(this).data("servicecharge");
      var serviceMin = $(this).data("servicemin");
      var serviceMax = $(this).data("servicemax");

      $(".serviceDetailsModalLabel").html(serviceName);
});

$(".placeOrder").click(function () {
      // console.log();
      var serviceName = $(this).data("servicename");
      var servicecharge = $(this).data("servicecharge");
      var serviceMin = $(this).data("servicemin");
      var serviceMax = $(this).data("servicemax");
      var serviceType = $(this).data("servicetype");
      var serviceId = $(this).data("serviceid");
      
  
      $("#minQty").html("(Minimum is: "+serviceMin);
      $("#maxQty").html("Maximum is: "+serviceMax+")");

      $("#followQty").attr("min", serviceMin);
      $("#followQty").attr("max", serviceMax);

      $('#pageLnk').val("");
      $('#followQty').val("");
      $('#totalAmnt').val("");
      $("#followQty").attr("data-servicecharge", servicecharge); 
      $(".orderModalLabel").html(serviceName);
      $(".serviceType").val(serviceType);
      $(".serviceId").val(serviceId);
});

$(".serviceBox").click(function(){
    $(this).addClass("serviceBoxActive"); 
    $(".serviceBox").not(this).removeClass("serviceBoxActive");
    var servicecatid = $(this).data("servicecat");
    console.log("servicecatid is " + servicecatid);

    $(".serviceRow").each(function() {

        if(servicecatid != 100){
            if ($(this).data("servicecatid") != servicecatid) {
                $(this).hide();
            } else {
                $(this).show();
            }
        }
        else{
            $(this).show();
        }
    });

});

$('#followQty').keyup(function() {
    var charge = $(".followQtyClass").attr("data-servicecharge");
    
    var qty = $(this).val();

    console.log("charge is " + charge);
    console.log("qty is " + qty);

    if(!qty){
      qty = 0;
    }

    if(!charge){
      charge = 0;
    }

    amount = qty * (charge/1000);

    $("#totalAmnt").val(amount);

});

$("#addOrder").submit(function(e){
    e.preventDefault();
    var serviceId = $(".serviceId").val();
    var pageLnk = $("#pageLnk").val();
    var followQty = $("#followQty").val();
    var serviceType = $(".serviceType").val();
    $('#orderModal').modal('hide');


    // Show the loader
    $(".loading-overlay").show();

  $.ajax({
      type: "POST",
      url: "./addOrder.php", 
      data: {
          serviceId: serviceId,
          serviceApiId: serviceType,
          pageLnk: pageLnk,
          followQty: followQty
      },
      success: function(response) {

        $(".loading-overlay").hide();

        console.log(response);
        if(response==1){
            
            $(".addOrderResponseLabel").html("Submitted Order!");
            $(".addOrderResponseTxt").html("Your order has been submitted. You can check your order in Orders page.");
            new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
        }
        else if(response==2){

            $(".addOrderResponseLabel").html("Insufficient Balance!");
            $(".addOrderResponseTxt").html("Sorry! Your account balance is insufficient to complete this order. Please add funds to your account.");
            new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
        }
        else{
            $(".addOrderResponseLabel").html("Error!");
            $(".addOrderResponseTxt").html("Sorry! Your order can't be processed at this time. Please try again later.");
            new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
        }
      }
  });


});

$(".closeOrderResponseModal").click(function(){ 
    window.location.href = "./orders.php";
});