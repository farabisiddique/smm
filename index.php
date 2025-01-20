<?php 

include './db.php'; // Your database connection file

if (isset($_COOKIE['rememberMe'])) {
    $token = $_COOKIE['rememberMe'];
    $findToken = $conn->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()");
    $findToken->bind_param("s", $token);
    $findToken->execute();
    $result = $findToken->get_result();
    

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Log the user in by setting session variables, etc.
        
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        
        // Redirect the user to the dashboard or desired page
        header("Location: dashboard.php"); 
        
    } 
    else {
          // Token not valid or expired
          setcookie("rememberMe", "", time() - 3600, "/"); // Delete the cookie
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meta BD - Login</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./css/main.css">

</head>

<body>
  <div class="container-fluid p-5 mainContainer">
    <nav class="navbar navbar-expand-lg mb-5 mainNav">
      <div class="container-fluid">
        <a class="navbar-brand webTitle" href="./index.php">Meta <span style="color: #02025A;">BD</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-start z-3" tabindex="-1" id="offcanvasNavbar"
          aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h1 class="offcanvas-title webTitle" id="offcanvasNavbarLabel">
              <a href="./index.php" class="text-decoration-none">Meta <span style="color: #02025A;">BD</span></a>
            </h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body d-lg-flex justify-content-lg-end">
            <ul class="navbar-nav mb-2 mb-lg-0">
              <li class="nav-item me-4 mb-2">
                <a class="nav-link navmenu" href="#">All Services</a>
              </li>
              <li class="nav-item me-4 mb-2">
                <a class="nav-link navmenu" href="#">Terms of Service</a>
              </li>
              <li class="nav-item me-5 mb-2">
                <a class="nav-link navmenu" href="#">FAQ</a>
              </li>
              <li class="nav-item mb-2">
                <a class="btn btn-outline-success text-decoration-none navmenu " href="./signup.php">Sign
                  Up</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>



    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-md-4 ps-0 me-lg-4 bendImgDiv signinImgContainer">
        <img src="./img/member-log-membership-username-password-concept.png" alt="signin image"
          class="bendImgDiv signinImg">
      </div>
      <div class="col-md-5 ms-lg-4 mt-3 pt-3  d-flex justify-content-center align-items-center border">
        <div class="row">
          <h2 class="text-center coloredText">Log In</h2>
          <p class="text-center" style="font-size: 28px;">Don't have an account? <a href="./signup.php"
              class="text-decoration-none coloredText">Sign Up</a></p>
          <form>
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control formInputField" id="email" name="email"
                placeholder="Enter your email">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control formInputField passShowClass" id="password" name="password"
                  placeholder="Enter your password" aria-describedby="basic-addon2 basic-addon4">
                <span class="input-group-text pe-auto eyeBtn" id="basic-addon2" role="button">
                  <i class="bi bi-eye"></i>
                </span>
              </div>
              <div class="form-text text-danger mt-2 ps-2 fw-bold loginHelpText" id="basic-addon4"></div>
            </div>


            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe" checked>
              <label class="form-check-label" for="exampleCheck1">Keep me logged in</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 text-center mb-3 signinupBtn" id="signInBtn"
              name="signInBtn">Sign In</button>
            <a href="#" class="text-decoration-none" style="color: #070E65; font-size: 20px;" id="frgtPass"
              name="frgtPass" data-bs-toggle="modal" data-bs-target="#forgetPassModal">
              <p>Forgot your password?</p>
            </a>
          </form>
        </div>
        
      </div>
    </div>

   

    <!-- Modal -->
    <div class="modal fade" id="forgetPassModal" tabindex="-1" aria-labelledby="forgetPassModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="forgetPassModalLabel">Forgot Password?</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form>
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control formInputField" id="passresetEmail" name="passresetEmail"
                  placeholder="Enter your email">
              </div>
              
              
              
            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary w-100 text-center mb-3 signinupBtn" id="passresetBtn"
            name="passresetBtn">Send Password Reset Link</button>
          </div>
        </div>
      </div>
    </div>
    <img src="./img/maintContSvgCurve.svg" class="maintContSvgCurve">
  </div>
  <div id="circle" class="circle"></div>
  <!-- Include Bootstrap JS and its dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  
  <script src="https://unpkg.com/kinet@2.2.1/dist/kinet.min.js"></script>
  <script src="./js/showHidePass.js"></script>
  <script src="./js/loginAjax.js"></script>
</body>

</html>
