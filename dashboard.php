<?php 

include './db.php';
include './functions.php';


function beautifulVarDump($var, $indent = 0) {
  $indentation = str_repeat('&nbsp;', $indent * 4);
  $type = gettype($var);

  echo $indentation . '<span style="color: blue;">' . $type . '</span>';

  if ($type == 'array' || $type == 'object') {
      echo ' (<span style="color: green;">' . count((array)$var) . '</span>)<br>';
      foreach ((array)$var as $key => $value) {
          echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . $key . '</strong>: ';
          beautifulVarDump($value, $indent + 1);
      }
  } else {
      echo ' <span style="color: red;">' . htmlspecialchars(print_r($var, true)) . '</span><br>';
  }
}

function getAllServicesFromAPI() {
    $api = new Api();
    $api_services = $api->services();

    $keyword_map = [
        'Facebook' => 1,
        'TikTok' => 2,
        'YouTube' => 3,
        'Instagram' => 4,
    ];

    // Use array_map to iterate through services and add 'cat_id'
    $api_services = array_map(function($service) use ($keyword_map) {
        $service->cat_id = 0; // Default cat_id if no keyword matches

        // Check for keywords in the category (case-insensitive)
        foreach ($keyword_map as $keyword => $cat_id) {
            if (stripos($service->category, $keyword) !== false) {
                $service->cat_id = $cat_id;
                break; // Stop checking once a match is found
            }
        }
        return $service;
    }, $api_services);

    return $api_services;
}

// beautifulVarDump(getAllServicesFromAPI());
// die();

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
            $finalServices = getAllServicesFromAPI();

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
  <title>Meta BD - Dashboard</title>
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
              <li class="nav-item me-3 mb-2">
                <a class="btn text-light text-decoration-none me-4 navmenu dashmenu"
                  href="./dashboard.php">Dashboard</a>
              </li>
              <li class="nav-item me-3 mb-2">
                <a class="btn text-light text-decoration-none me-3 navmenu dashmenu" href="./orders.php">Orders</a>
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
                              <?php echo $balance; ?>
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

    <div class="col-md-1 me-4 d-flex justify-content-center align-items-center">
        <h3>Service: </h3>
      </div>
      <div class="col-md-9">
        <div class="row serviceContainer">
          <img src="./img/serviceVector.svg" class="serviceVector">
          <div class="col-md-2 serviceBoxContainer p-0 d-flex justify-content-end align-items-center">
            <div class="serviceBox" data-servicecat="1">
            <img src="./img/service-container-bg.svg" class="serviceBoxVector" width="74" height="48">
              <div class="service">
                <img src="./img/fblogo.png" alt="" class="serviceLogo">
                <p class="serviceName m-0 mt-3">Facebook</p>
              </div>
            </div>
          </div>
          <div class="col-md-2 serviceBoxContainer p-0 d-flex justify-content-end align-items-center">
            <div class="serviceBox" data-servicecat="2">
              <img src="./img/service-container-bg.svg" class="serviceBoxVector" width="74" height="48">
              <div class="service">
                <img src="./img/tiktoklogo.png" alt="" class="serviceLogo">
                <p class="serviceName m-0 mt-3">Tiktok</p>
              </div>
            </div>
          </div>
          <div class="col-md-2 serviceBoxContainer p-0 d-flex justify-content-end align-items-center">
            <div class="serviceBox" data-servicecat="3">
            <img src="./img/service-container-bg.svg" class="serviceBoxVector" width="74" height="48">
              <div class="service">
                <img src="./img/ytlogo.png" alt="" class="serviceLogo">
                <p class="serviceName m-0 mt-3">YouTube</p>
              </div>
            </div>
          </div>
          <div class="col-md-2 serviceBoxContainer p-0 d-flex justify-content-end align-items-center">
            <div class="serviceBox" data-servicecat="4">
            <img src="./img/service-container-bg.svg" class="serviceBoxVector" width="74" height="48">
              <div class="service">
                <img src="./img/iglogo.png" alt="" class="serviceLogo">
                <p class="serviceName m-0 mt-3">Instagram</p>
              </div>
            </div>
          </div>
          <div class="col-md-2 serviceBoxContainer p-0 d-flex justify-content-end align-items-center">
            <div class="serviceBox" data-servicecat="0">
            <img src="./img/service-container-bg.svg" class="serviceBoxVector" width="74" height="48">
              <div class="service">
                <img src="./img/offerlogo.png" alt="" class="serviceLogo">
                <p class="serviceName m-0 mt-3">Others</p>
              </div>
            </div>
          </div>
          <div class="col-md-2 serviceBoxContainer p-0 d-flex justify-content-end align-items-center">
            <div class="serviceBox serviceBoxActive" data-servicecat="100">
            <img src="./img/service-container-bg.svg" class="serviceBoxVector" width="74" height="48">
              <div class="service">
                <img src="./img/all.png" alt="" class="serviceLogo">
                <p class="serviceName m-0 mt-3">All</p>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
    <div class="row ps-3 mb-5">
      <form class="p-2" id="addOrder">
        <div class="row ps-3 mb-3">
          <div class="col-md-2 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
            <label class="dashFormLabel">Category:</label>
          </div>
          <div class="col-md-6">
            <select class="form-select formInputField dashSelect" id="orderSubCtgry" name="orderSubCtgry">
                    <!-- <option value="">Select a Category</option> -->
                    <?php 
                        $categories = [];
                        foreach ($finalServices as $aService) {
                            if (!in_array($aService->category, $categories)) {
                                $categories[] = $aService->category;
                                echo '<option value="'.$aService->cat_id.'">'.$aService->category.'</option>';
                            }
                        }
                    ?>
                    
            </select>
          </div>
        </div>
        <div class="row ps-3 mb-3">
          <div class="col-md-2 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
            <label class="dashFormLabel">Service:</label>
          </div>
          <div class="col-md-6">
            <select class="form-select formInputField dashSelect" id="serviceType" name="serviceType" required>
              <option value="">Select a Service</option>
              <?php 
                  foreach($finalServices as $aService){
                      $service_min = $aService->min;
                      $service_max = $aService->max;
                      $service_charge = $aService->rate + (($aService->rate*10)/100);
                    
                      echo '<option 
                              class="serviceNameOption"
                              value="'.$aService->service.'"
                              data-serviceapiid="'.$aService->service.'"
                              data-servicecatid="'.$aService->cat_id.'"
                              data-serivicesubcatid="'.$aService->cat_id.'"
                              data-servicecharge="' . $service_charge . '"
                              data-servicemin="' . $service_min . '"
                              data-servicemax="' . $service_max . '"
                            >'.$aService->name.'</option>';
                  }
              ?>
              
            </select>
            
          </div>
        </div>
        <div class="row ps-3 mb-3">
          <div class="col-md-2 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
            <label class="dashFormLabel">Link:</label>
          </div>
          <div class="col-md-6">
            <input type="url" class="form-control formInputField" id="pageLnk" name="pageLnk" placeholder="Link" required>
          </div>
        </div>
        <div class="row ps-3 mb-3">
          <div class="col-md-2 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
            <label class="dashFormLabel">Quantity:
              <span id="minQty" class="form-text"></span>
              <span id="maxQty" class="form-text"></span>
            </label>
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control formInputField" id="followQty" name="followQty" required>
            
          </div>
            
          <div class="col-md-2 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
            <label class="dashFormLabel">Total Amount:</label>
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control formInputField" id="totalAmnt" name="totalAmnt" disabled>
          </div>
        </div>
        <div class="row ps-3">
          <div class="col-md-3"></div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 signinupBtn" id="submtOrdr" name="submtOrdr">Submit
              Order</button>
          </div>
          <div class="col-md-2"></div>

        </div>

      </form>
    </div>

    <div class="modal fade addOrderResponseModal" id="addOrderResponseModal" tabindex="-1" aria-labelledby="addOrderResponseLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title addOrderResponseLabel" id="addOrderResponseLabel"></h5>
            <button type="button" class="btn-close closeOrderResponseModal" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body addOrderResponseTxt">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary closeOrderResponseModal" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
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
  <script src="./js/dashboard.js"></script>
</body>

</html>

<!-- figma address:   https://www.figma.com/file/eGXSO6qLuLuPud3fsYbhMW/Meta-bd?node-id=11%3A280&mode=dev -->