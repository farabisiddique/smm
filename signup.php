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
  <title>Meta BD - Signup</title>
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
                <a class="btn btn-outline-success text-decoration-none navmenu " href="./index.php">Log In</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-md-4 ps-0 me-lg-4 bendImgDiv signinImgContainer">
        <img src="./img/sign-up-form-button-graphic-concept.png" alt="signin image" class="bendImgDiv signinImg">
      </div>
      <div class="col-md-5 ms-lg-4 mt-3 pt-3 d-flex justify-content-center align-items-center border">
        <div class="row">
          <h2 class="text-center coloredText">Create An Account</h2>
          <p class="text-center" style="font-size: 28px;">Already have an account? <a href="./index.php"
              class="text-decoration-none coloredText">Log In</a></p>
          <form>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control formInputField" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control formInputField" id="email" name="email" placeholder="Enter your email" required> 
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control formInputField passShowClass" id="password" name="password"
                  placeholder="Enter your password" required>
                <span class="input-group-text pe-auto eyeBtn" id="basic-addon2" role="button">
                  <i class="bi bi-eye"></i>
                </span>
              </div>
              <div class="form-text text-danger mt-2 ps-2 fw-bold loginHelpText" id="basic-addon4"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100 text-center mb-3 signinupBtn" id="signUpBtn" name="signUpBtn">Sign Up</button>
          </form>
        </div>

      </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade signUpModal" id="successModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Signup Successful</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Your account has been successfully created. Login Now
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Login</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade signUpModal" id="errorModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Signup Failed</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            There was a problem creating your account. Please try again.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>



    <img src="./img/maintContSvgCurve.svg" class="maintContSvgCurve">
  </div>

  <!-- Include Bootstrap JS and its dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="./js/showHidePass.js"></script>
  <script src="./js/signupAjax.js"></script>
</body>

</html>