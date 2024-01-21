<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta BD Login</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="./css/main.css">
    
</head>
<body>
    <div class="container-fluid p-5 mainContainer">
        <div class="row mb-5">
            <div class="col-md-4">
                <h1 class="webTitle">
                  <a href="./index.php" class="text-decoration-none">Meta <span style="color: #02025A;">BD</span></a>  
                </h1>
            </div>
            <div class="col-md-8 d-flex justify-content-end align-items-center">
                <a href="#" class="text-decoration-none me-4 navmenu">All Services</a>
                <a href="#" class="text-decoration-none me-4 navmenu">Terms of Service</a>
                <a href="#" class="text-decoration-none me-5 navmenu">FAQ</a>
                <a href="./signup.php" class="btn btn-outline-success text-decoration-none me-3 navmenu">Sign Up</a>
            </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-4 ps-0 me-4 bendImgDiv signinImgContainer"> 
                <img src="./img/member-log-membership-username-password-concept.png" alt="signin image" class="bendImgDiv signinImg"> 
            </div>
            <div class="col-md-5 ms-4 d-flex justify-content-center align-items-center border"> 
                <div class="row">
                    <h2 class="text-center coloredText">Log In</h2>
                    <p class="text-center" style="font-size: 28px;">Don't have an account? <a href="./signup.php" class="text-decoration-none coloredText">Sign Up</a></p>
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control formInputField" id="email" placeholder="Enter your email" >
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control formInputField passShowClass" id="password" placeholder="Enter your password">
                                <span class="input-group-text pe-auto eyeBtn" id="basic-addon2" role="button">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>


                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" checked>
                            <label class="form-check-label" for="exampleCheck1">Keep me logged in</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 text-center mb-3 signinupBtn">Sign In</button>
                        <a href="#" class="text-decoration-none" style="color: #070E65; font-size: 20px;"><p>Forgot your password?</p></a>
                    </form>
                </div>
                
            </div>
        </div>
        
    </div>

    <!-- Include Bootstrap JS and its dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="./js/showHidePass.js"></script>
</body>
</html>