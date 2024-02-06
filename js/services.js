$(".seeServiceDetails").click(function () {
      // console.log();
      var serviceName = $(this).data("servicename");
      var serviceRate = $(this).data("servicerate");
      var serviceMin = $(this).data("servicemin");
      var serviceMax = $(this).data("servicemax");

      $(".serviceDetailsModalLabel").html(serviceName);
  });

$(".serviceBox").click(function(){
    $(this).addClass("serviceBoxActive");
    $(".serviceBox").not(this).removeClass("serviceBoxActive");
    var servicecatid = $(this).data("servicecat");

    $(".serviceRow").each(function() {
        if ($(this).data("servicecatid") != servicecatid) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });

});