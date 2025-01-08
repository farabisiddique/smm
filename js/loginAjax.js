
$(document).ready(function() {
    // When the form is submitted
    $('#signInBtn').click(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect the form data
        var email = $('#email').val();
        var password = $('#password').val();
        var rememberMe = $('#rememberMe').is(':checked') ? 1 : 0; // Check if 'Keep me logged in' is checked
        
        // Send the data using AJAX to your PHP processing script
        $.ajax({
            type: "POST",
            url: "./login.php", // The PHP file you will create for processing login
            data: {
                email: email,
                password: password,
                rememberMe: rememberMe
            },
            success: function(response) {
                console.log(response);
              var jsonData = JSON.parse(response);
              
              
                if (jsonData.success == 1) {
                    window.location.href = "dashboard.php";
                } else {
                    
                    $(".loginHelpText").html("Wrong Email or Password. Please try again.");
                }
            }
        });
    });
});

