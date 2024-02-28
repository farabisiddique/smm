$(document).ready(function(){

    $("#addFundForm").submit(function(e){

        e.preventDefault();
        var paymntMthd = $("#paymntMthd").val();
        var fndamntBDT = $("#fndamntBDT").val();
        var senderNo = $("#senderNo").val();

        $(".loading-overlay").show();
  
        $.ajax({
            type: "POST",
            url: "./txnProcess.php", 
            data: {
                  paymntMthd: paymntMthd,
                  fndamntBDT: fndamntBDT,
                  senderNo: senderNo
            },
            success: function(response) {
  
              $(".loading-overlay").hide();
  
  
              // if(response==1){
              //     $(".addOrderResponseLabel").html("Submitted Order!");
              //     $(".addOrderResponseTxt").html("Your order has been submitted. You can check your order in Orders page.");
              //     new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
              // }
              // else if(response==2){
  
              //     $(".addOrderResponseLabel").html("Insufficient Balance!");
              //     $(".addOrderResponseTxt").html("Sorry! Your account balance is insufficient to complete this order. Please add funds to your account.");
              //     new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
              // }
              // else{
              //     $(".addOrderResponseLabel").html("Error!");
              //     $(".addOrderResponseTxt").html("Sorry! Your order can't be processed at this time. Please try again later.");
              //     new bootstrap.Modal(document.getElementById('addOrderResponseModal')).show();
              // }
            }
        });


      
    });


  
});