$(".serviceBox").click(function() {
    $(this).addClass("serviceBoxActive");
    $(".serviceBox").not(this).removeClass("serviceBoxActive");
});