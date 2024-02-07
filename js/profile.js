
$(document).ready(function() {
    $('#chngPassForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var currentPassword = $('#currentPassword').val();
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmPassword').val();

        // Check if the new password is at least 6 characters long and contains a number
        if (newPassword.length < 6 || !/\d/.test(newPassword)) {
            $("#confirmPasswordHelp").html('The new password must be at least 6 characters long and include a number.');
            return false;
        }

        // Check if the new password and confirm password match
        if (newPassword !== confirmPassword) {
            $("#confirmPasswordHelp").html('The new password and confirm password do not match.');
            return false;
        }

        // Proceed with AJAX if validations pass
        $.ajax({
            type: "POST",
            url: "./changePassword.php", // Your PHP script to change the password
            data: {
                currentPassword: currentPassword,
                newPassword: newPassword
            },
            success: function(response) {
                // Handle response here
                if(response==1){
                    $(".changePassResponseLabel").html("Password Changed!");
                    $(".changePassResponseTxt").html("Your password has been changed successfully.");
                    new bootstrap.Modal(document.getElementById('changePassResponseModal')).show();
                }
                else if(response==2){
  
                    $(".changePassResponseLabel").html("Incorrect Password!");
                    $(".changePassResponseTxt").html("Sorry! Your current password is incorrect.");
                    new bootstrap.Modal(document.getElementById('changePassResponseModal')).show();
                }
                else{
                    $(".changePassResponseLabel").html("Error!");
                    $(".changePassResponseTxt").html("Sorry! Your password can't be changed at this time. Please try again later.");
                    new bootstrap.Modal(document.getElementById('changePassResponseModal')).show();
                }
            }
        });
    });

    $(".closeChngPassResponseModal").click(function(){ 
        location.reload();
    });
});
