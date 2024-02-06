$(document).ready(function() {
    $('#signUpBtn').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        console.log(e);
        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            type: "POST",
            url: "./addUser.php",
            data: {
                name: name,
                email: email,
                password: password
            },
            success: function(response) {
                console.log(response);
                if(response == 1) {
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                    closeModalAndRedirect(successModal);
                } 
                else if(response == 2) {
                    $(".loginHelpText").html("Email already exists");
                }
                else if(response == 3) {
                    $(".loginHelpText").html("Password must be at least 6 characters long. Please try again.");
                }
                else if(response == 4) {
                    $(".loginHelpText").html("Password must include at least one number. Please try again.");
                }
                else if(response == 0) {
                    
                    new bootstrap.Modal(document.getElementById('errorModal')).show();
                }
            },
            error: function() {
                new bootstrap.Modal(document.getElementById('errorModal')).show();
            }
        });
    });



    function closeModalAndRedirect(modalInstance) {
        // Redirect after 6 seconds
        var timer = setTimeout(function() {
            window.location.href = 'index.php';
        }, 4000);

      $(modalSelector).on('hidden.bs.modal', function () {
          clearTimeout(redirectTimeout);
          window.location.href = 'index.php';
      });
    }
});
