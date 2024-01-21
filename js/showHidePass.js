$(".eyeBtn").click(function(){
    if($('#password').attr("type")=="password"){
        $(".eyeBtn").empty();
        $(".eyeBtn").append('<i class="bi bi-eye-slash"></i>');
        $('#password').attr("type","text");
    }
    else{
        $(".eyeBtn").empty();
        $(".eyeBtn").append('<i class="bi bi-eye"></i>');
        $('#password').attr("type","password");
    }
});