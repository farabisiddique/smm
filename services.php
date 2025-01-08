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
      'Tiktok' => 2,
      'Youtube' => 3,
      'Instagram' => 4,
  ];

  // Use array_map to iterate through services and add 'cat_id'
  $api_services = array_map(function($service) use ($keyword_map) {
      $service->cat_id = 0; // Default cat_id if no keyword matches

      // Check for keywords in the category
      foreach ($keyword_map as $keyword => $cat_id) {
          if (strpos($service->category, $keyword) !== false) {
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



function get_services_by_ids($ids,$newProperties) {
    $api = new Api();
    $api_services = $api->services(); 
    $matched_services = []; 

    foreach($api_services as $service) {
        $service_id = $service->service;
        if(($key = array_search($service_id, $ids)) !== false) {
            foreach($newProperties as $properties) {
                if($properties['service_api_id'] == $service_id) {
                    foreach($properties as $property => $value) {
                        $service->$property = $value; 
                    }
                    break; 
                }
            }
            $matched_services[] = $service; 
        }
    }
    return $matched_services; 
}

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

        $servicesResult = $conn->query("SELECT * FROM services 
                                    JOIN service_category ON services.service_cat_id = service_category.service_category_id 
                                    JOIN service_subcategory ON services.service_subcat_id = service_subcategory.service_subcategory_id");


        $allServices = array();
        $allServiceIds = array();
        if ($servicesResult->num_rows >0) {
            while( $servicesRow = $servicesResult->fetch_assoc() ){
                array_push($allServices, $servicesRow);
                array_push($allServiceIds, $servicesRow['service_api_id']);
            }
        }

      // $finalServices = get_services_by_ids($allServiceIds,$allServices);
      $finalServices = getAllServicesFromAPI();
      
      
      

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
  <title>Meta BD - Services</title>
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
                              <?php echo $balance; ?>
                            </span>
                          </p>
                        </a>
                      </li>
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
        <table class="table table-striped  serviceTable">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Service</th>
              <th scope="col">Rate per 1000</th>
              <th scope="col">Min/Max</th>
              <!-- <th scope="col">Description</th> -->
              <th scope="col">Order</th>
            </tr>
          </thead>
          <tbody>
            <?php 
                foreach($finalServices as $aService){
                    $service_id = $aService->service;
                    $service_cat_id = $aService->cat_id;
                    $service_name = $aService->name;
                    $service_min = $aService->min;
                    $service_max = $aService->max;
                    // $service_charge = $aService->rate + (($aService->rate*$aService->service_rate_percentage)/100);
                    $service_charge = $aService->rate + (($aService->rate*5)/100);
                    $service_charge = number_format($service_charge, 2);

                    
                  
                    echo '<tr class="serviceRow" data-servicecatid='.$service_cat_id.'>';
                      echo "<td>".$service_id."</td>";
                      echo "<td>".$service_name."</td>";
                      echo "<td>$".$service_charge."</td>";
                      echo "<td>".$service_min."-".$service_max."</td>";
                      // echo  '<td>
                      //         <button type="button" 
                      //                 class="btn btn-primary seeServiceDetails" 
                      //                 data-bs-toggle="modal" 
                      //                 data-bs-target="#serviceDetailsModal"
                      //                 data-servicename="'.$aService->service_name.'"
                      //                 data-servicecharge="' . $service_charge . '"
                      //                 data-servicemin="' . $service_min . '"
                      //                 data-servicemax="' . $service_max . '"
                      //         >
                      //           See Details
                      //         </button>
                      //       </td>';
                      echo  '<td>
                                  <button type="button" 
                                          class="btn btn-primary placeOrder" 
                                          data-bs-toggle="modal" 
                                          data-bs-target="#orderModal"
                                          data-serviceid="'.$service_id.'"
                                          data-servicetype="'.$service_id.'"
                                          data-servicename="'.$service_name.'"
                                          data-servicecharge="' . $service_charge . '"
                                          data-servicemin="' . $service_min . '"
                                          data-servicemax="' . $service_max . '"
                                  >
                                    Order Now
                                  </button>
                             </td>';
                    echo "</tr>";
                }
            
            ?>
          </tbody>
        </table>
    </div>

   
    

    <!-- Modal -->
    <div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-labelledby="serviceDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 serviceDetailsModalLabel" id="serviceDetailsModalLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade orderModal" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 orderModalLabel" id="orderModalLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="p-2" id="addOrder">
              <div class="row ps-3 mb-3">
                <div class="col-md-1 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
                  <label class="dashFormLabel">Link:</label>
                </div>
                <div class="col-md-9">
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
                <div class="col-md-4">
                  <input type="number" class="form-control formInputField followQtyClass" id="followQty" name="followQty" required>

                </div>

                <div class="col-md-2 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
                  <label class="dashFormLabel">Total Amount:</label>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control formInputField" id="totalAmnt" name="totalAmnt" disabled>
                </div>
              </div>
              <div class="row ps-3">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <input type="hidden" class="serviceType">
                  <input type="hidden" class="serviceId">
                  <button type="submit" class="btn btn-primary w-100 signinupBtn" id="submtOrdr" name="submtOrdr">Submit
                    Order</button>
                </div>
                <div class="col-md-2"></div>

              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
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
    <img src="./img/mainContSvgEllipse.svg" class="mainContSvgEllipse">
    
  </div>

  <!-- Include Bootstrap JS and its dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="./js/services.js"></script>

</body>

</html>

<!-- figma address:   https://www.figma.com/file/eGXSO6qLuLuPud3fsYbhMW/Meta-bd?node-id=11%3A280&mode=dev -->