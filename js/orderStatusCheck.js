


$('.orderStatusCheck').on('load', function() {
  console.log('Image has loaded!');
});

function orderStatus($d){
    $(".loading-overlay").show();
  
    var orderId = $($d).data("orderid");

    $.ajax({
        type: "POST",
        url: "./checkOrderStatus.php",
        data: {
            orderId: orderId
        },
        success: function(response) {
            // $($d).parent().empty();
            $($d).parent().html(response);
            console.log();
            
        },
        error: function() {
           
        }
    });
  
    $(".loading-overlay").hide();
  


};