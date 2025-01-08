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
        $userid = $user['user_id'];
        // Log the user in by setting session variables, etc.
        session_start();
        $_SESSION['user_id'] = $userid;
  
        $userQuery = $conn->prepare("SELECT * FROM user 
                                    WHERE user_id = ? ");
        $userQuery->bind_param("i", $userid);
        $userQuery->execute();
        $userResult = $userQuery->get_result();
  
        if ($userResult->num_rows == 1) {
            $userHere = $userResult->fetch_assoc();
            $username = $userHere['user_name'];
            $balance = $userHere['user_balance'];
  
        }

    } 
    else {
          // Token not valid or expired
          setcookie("rememberMe", "", time() - 3600, "/"); // Delete the cookie
          session_destroy();
          header("Location: index.php"); // Redirect to the login page
    }
}
else{
  header("Location: index.php"); // Redirect to the login page
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meta BD - Orders</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">

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
              <li class="nav-item me-3 mb-2">
                <a class="btn text-light text-decoration-none me-4 navmenu dashmenu"
                  href="./dashboard.php">Dashboard</a>
              </li>
              <li class="nav-item me-3 mb-2">
                <a class="btn text-light text-decoration-none me-4 navmenu dashmenu" href="./orders.php">Orders</a>
              </li>
              <li class="nav-item me-3 mb-2">
                <a class="btn text-light text-decoration-none me-4 navmenu dashmenu" href="./services.php">Services</a>
              </li>
              <li class="nav-item me-3 mb-2">
                <a class="btn text-light text-decoration-none me-4 navmenu dashmenu" href="./addfund.php">Add Fund</a>
              </li>
              <li class="nav-item mb-2">
                <div class="dropdown">
                    <a class="btn text-light text-decoration-none dropdown-toggle navmenu dashmenu" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Your Profile</a>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="./profile.php">
                          <p class="dropdown-item mb-1 ellipsis p-0">
                            <?php echo $username; ?>
                          </p>
                          <p class="text-center mb-0">
                            $<span>
                              <?php echo $balance;  ?>
                            </span>
                          </p>
                        </a>
                      </li>
                      <!-- <li><a class="dropdown-item" href="#">Another action</a></li> -->
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
                    </ul>
                </div>

              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <div class="row ps-3 mb-5">
      <table id="allOrders" class="display">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Order ID</th>
                  <th>Date & Time</th>
                  <th>Link</th>
                  <th>Service</th>
                  <th>Quantity</th>
                  <th>Charge</th>
                  <th>Status</th>
                 


              </tr>
          </thead>
          <tbody>
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  

              </tr>

          </tbody>
      </table>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-overlay" style="display:none;">
      <div class="loader"></div>
    </div>

    <img src="./img/maintContSvgCurve.svg" class="maintContSvgCurve">
    <svg xmlns="http://www.w3.org/2000/svg" width="157" height="81" viewBox="0 0 157 81" fill="none"
      class="mainContSvgEllipse">
      <ellipse cx="4" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="4" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="4" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="4" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="19" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="19" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="19" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="19" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="34" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="34" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="34" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="34" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="49" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="49" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="49" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="49" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="64" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="109" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="64" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="109" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="64" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="109" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="64" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="109" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="79" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="124" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="79" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="124" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="79" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="124" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="79" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="124" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="94" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="139" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="94" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="139" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="94" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="139" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="94" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="139" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="4" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="4" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="154" cy="4.16793" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="154" cy="18.7558" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="19" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="19" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="34" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="34" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="49" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="49" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="64" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="109" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="64" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="109" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="79" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="124" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="79" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="124" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="154" cy="33.3435" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="154" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="154" cy="47.9311" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="154" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="94" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="139" cy="61.477" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="94" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
      <ellipse cx="139" cy="76.0647" rx="4" ry="4.16793" fill="#45AAEE" fill-opacity="0.4" />
    </svg>
  </div>

  <!-- Include Bootstrap JS and its dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
  <script type="text/javascript" charset="utf8" src="./js/datatable/orders.js"></script>
  <script type="text/javascript" charset="utf8" src="./js/orderStatusCheck.js"></script>
</body>

</html>

<!-- figma address:   https://www.figma.com/file/eGXSO6qLuLuPud3fsYbhMW/Meta-bd?node-id=11%3A280&mode=dev -->