<?php 

include './db.php'; // Your database connection file

if (isset($_COOKIE['adminCookie'])) {
    $token = $_COOKIE['adminCookie'];
    $findToken = $conn->prepare("SELECT admin_id FROM admin_tokens WHERE token = ? AND expires_at > NOW()");
    $findToken->bind_param("s", $token);
    $findToken->execute();
    $result = $findToken->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Log the user in by setting session variables, etc.
        session_start();
        $_SESSION['admin_id'] = $user['admin_id'];
        // Redirect the user to the dashboard or desired page
        header("Location: admin/index.php"); 

    } 
    else {
          // Token not valid or expired
          setcookie("adminCookie", "", time() - 3600, "/"); // Delete the cookie
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaBD Admin Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body class="d-flex justify-content-center align-items-center vh-100">
  
    <div class="row">
        <h1 class="mb-4 text-center">MetaBD Admin Login</h1>
        <form id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary" id="loginBtn">Login</button>
            </div>
        </form>
    </div>
    <!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

        $(document).ready(function() {
            // When the form is submitted
            $('#loginForm').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Collect the form data
                var username = $('#username').val();
                var password = $('#password').val();

                // Send the data using AJAX to your PHP processing script
                $.ajax({
                    type: "POST",
                    url: "./adminLogin.php", // The PHP file you will create for processing login
                    data: {
                        username: username,
                        password: password
                    },
                    success: function(response) {
                      
                      var jsonData = JSON.parse(response);

                        if (jsonData.success == 1) {
                            window.location.href = "./admin/index.php";
                        } else {

                            // $(".loginHelpText").html("Wrong Email or Password. Please try again.");
                        }
                    }
                });
            });
        });


</script>  
</body>
</html>
