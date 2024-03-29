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

        $pmResult = $conn->query("SELECT * FROM payment_methods");

        $paymentMethods = array();
        if ($pmResult->num_rows >0) {
            while( $pmRow = $pmResult->fetch_assoc() ){
                array_push($paymentMethods, $pmRow);
            }
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
  <title>Meta BD - Add Fund</title>
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
                              <?php echo $balance;  ?>
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
      <div class="col-md-7">
        <div class="row p-0 d-flex justify-content-center align-items-center">
          <h3>Add Your Fund </h3>
        </div>
        <div class="row">
          <form class="p-2" id="addFundForm" method="POST">
            <div class="row ps-3 mb-3">
              <div class="col-md-3 p-0 d-flex justify-content-center justify-content-lg-end mt-4">
                <label class="dashFormLabel">Payment Method:</label>
              </div>
              <div class="col-md-5">
                
                <select class="vodiapicker formInputField dashSelect" id="paymntMthd" name="paymntMthd" required>
                      <?php 
                          foreach($paymentMethods as $paymentMethod){
                            echo '<option value="'.$paymentMethod["pm_id"].'" 
                            data-thumbnail="'.$paymentMethod["pm_logo"].'">'.$paymentMethod["pm_name"].'- 0'.$paymentMethod["pm_no"].'</option>';
                          }
                      ?>
                </select>

                <button class="btn-select p-2 formInputField dashSelect" value=""></button>
                <div class="b">
                  <ul id="trayListUl"></ul>
                </div>
              </div>
            </div>

            <div class="row ps-3 mb-3">
              <div class="col-md-3 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
                <label class="dashFormLabel mb-lg-4">Amount in Dollars($):</label>
              </div>
              <div class="col-md-4">
                <input type="number" class="form-control formInputField" id="fndamnt" name="fndamnt" min="1" step="0.01" required>
                <div id="fndamntHelp" class="form-text ms-3">Minimum deposit $1</div>
              </div>
            </div>
            <div class="row ps-3 mb-3">
              <div class="col-md-3 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
                <label class="dashFormLabel">Fee in Taka(BDT):</label>
              </div>
              <div class="col-md-4">
                
                <div class="input-group mb-3">
                  <input type="number" class="form-control formInputField" id="fndfee" name="fndfee" aria-describedby="basic-addon2" disabled>
                  <span class="input-group-text" id="basic-addon2">BDT</span>
                </div>
              </div>

            </div>
            <div class="row ps-3 mb-3">
              <div class="col-md-3 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
                <label class="dashFormLabel">Total Amount to be paid(BDT):</label>
              </div>
              <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="number" class="form-control formInputField" id="fndamntBDT" name="fndamntBDT" aria-describedby="basic-addon3" disabled>
                    <span class="input-group-text" id="basic-addon3">BDT</span>
                </div>
              </div>

            </div>
            <div class="row ps-3 mb-3">
              <div class="col-md-3 p-0 d-flex justify-content-center justify-content-lg-end align-items-center">
                <label class="dashFormLabel">Your Bkash/Nagad Number (From where you sent money):</label>
              </div>
              <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="number" class="form-control formInputField" id="senderNo" name="senderNo" min="01111111111" max="01999999999" required>
                </div>
              </div>

            </div>
            <div class="row ps-3">
              <div class="col-md-3"></div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 signinupBtn" id="addFndBtn"
                  name="addFndBtn">Confirm</button>
              </div>
              <div class="col-md-2"></div>




            </div>
          </form>
        </div>
      </div>
      <div class="col-md-5">
        <div class="row">
          <div class="col-md-5">
            <button class="btn btn-primary w-100 signinupBtn">Payment Instruction</button>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-12 border border-3 paymentInstruction">
            
          </div>
        </div>

      </div>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" width="1440" height="647" viewBox="0 0 1440 647" fill="none"
      class="maintContSvgCurve">
      <path opacity="0.19" fill-rule="evenodd" clip-rule="evenodd"
        d="M1439.98 0.714966C1376.59 20.4099 1294.08 71.0391 1200.36 188.515C1008.88 428.553 917.57 408.999 763.126 375.931C744.001 371.836 723.912 367.534 702.67 363.513C700.067 363.019 697.473 362.518 694.89 362.003L690.886 361.066C685.998 359.884 681.14 358.728 676.317 357.613C671.429 356.43 666.57 355.274 661.747 354.159C656.859 352.976 652.001 351.821 647.177 350.705C642.289 349.523 637.431 348.367 632.607 347.252C627.719 346.069 622.861 344.92 618.038 343.798C613.145 342.615 608.291 341.467 603.468 340.345C598.575 339.162 593.722 338.013 588.898 336.891C584.01 335.708 579.152 334.552 574.328 333.437C444.438 302.022 338.741 291.336 220.53 470.396C163.161 557.298 87.0882 609.634 11.9941 640.028C7.99284 641.647 3.99154 643.198 0.000286671 644.696L4.57155 644.711C7.13631 643.721 9.70596 642.711 12.2708 641.674C87.5288 611.214 163.776 558.75 221.304 471.612C315.851 328.396 402.429 306.969 499.906 320.278C408.069 311.115 325.223 337.342 235.1 473.849C177.731 560.751 101.658 613.088 26.5637 643.481C25.4802 643.923 24.4016 644.344 23.323 644.772L27.6655 644.786C102.655 614.278 178.56 561.88 235.874 475.059C330.42 331.843 416.999 310.416 514.476 323.725C422.639 314.562 339.793 340.789 249.67 477.296C193.59 562.246 119.628 614.165 46.1835 644.84L50.3174 644.854C122.649 613.912 195.185 562.204 250.439 478.506C344.985 335.289 431.563 313.863 529.04 327.172C437.204 318.009 354.358 344.236 264.235 480.743C210.16 562.651 139.468 613.852 68.6417 644.914L72.6016 644.927C142.355 613.646 211.72 562.677 265.009 481.96C359.555 338.75 446.133 317.317 543.61 330.626C451.774 321.463 368.927 347.69 278.804 484.197C226.645 563.205 159.03 613.64 90.7421 644.986L94.5381 644.999C161.793 613.467 228.165 563.29 279.578 485.407C374.125 342.19 460.703 320.763 558.18 334.072C466.343 324.909 383.497 351.137 293.374 487.644C243.051 563.872 178.333 613.501 112.509 645.058L116.161 645.07C180.987 613.375 244.526 564.012 294.143 488.86C388.69 345.644 475.268 324.217 572.745 337.526C480.908 328.363 398.062 354.59 307.939 491.097C259.377 564.654 197.413 613.449 133.984 645.128L137.502 645.14C199.962 613.349 260.827 564.841 308.708 492.307C403.254 349.09 489.837 327.664 587.309 340.973C495.473 331.81 412.627 358.037 322.509 494.544C275.643 565.537 216.288 613.456 155.185 645.197L158.588 645.209C218.758 613.39 277.064 565.771 323.283 495.761C417.829 352.544 504.407 331.118 601.884 344.427C510.048 335.264 427.201 361.491 337.078 497.998C291.845 566.514 234.985 613.538 176.142 645.266L179.436 645.277C237.37 613.499 293.241 566.781 337.852 499.208C432.399 355.991 518.977 334.564 616.454 347.873C524.617 338.71 441.771 364.938 351.653 501.445C308.002 567.565 253.518 613.673 196.881 645.334L200.076 645.345C255.838 613.66 309.373 567.873 352.427 502.661C446.974 359.445 533.557 338.018 631.029 351.327C539.192 342.164 456.346 368.391 366.223 504.898C324.104 568.697 271.902 613.868 217.407 645.401L220.497 645.411C274.143 613.875 325.45 569.038 366.992 506.108C461.538 362.891 548.116 341.465 645.593 354.774C553.757 345.611 470.911 371.838 380.788 508.345C340.152 569.902 290.126 614.11 237.713 645.468L240.724 645.478C292.313 614.144 341.478 570.271 381.557 509.562C476.103 366.345 562.681 344.919 660.158 358.227C568.322 349.065 485.476 375.292 395.352 511.799C356.155 571.169 308.227 614.405 257.846 645.534L260.782 645.543C310.363 614.459 357.471 571.564 396.126 513.009C490.673 369.792 577.251 348.365 674.728 361.674C582.891 352.511 500.045 378.739 409.922 515.246C372.128 572.496 326.213 614.74 277.81 645.599L280.657 645.608C328.285 614.815 373.419 572.918 410.691 516.462C505.238 373.246 591.816 351.819 689.293 365.128C597.456 355.965 514.61 382.192 424.487 518.699C388.056 573.883 344.08 615.123 297.605 645.664L300.392 645.673C346.112 615.217 389.337 574.326 425.261 519.916C519.807 376.699 606.386 355.273 703.862 368.582C612.026 359.419 529.18 385.646 439.057 522.153C403.959 575.318 361.852 615.552 317.256 645.728L319.963 645.737C363.835 615.653 405.22 575.788 439.831 523.363C534.377 380.146 620.955 358.72 718.432 372.028C626.596 362.866 543.75 389.093 453.626 525.6C419.822 576.807 379.52 616.008 336.752 645.792L339.406 645.801C381.463 616.135 421.069 577.29 454.395 526.816C548.942 383.6 635.52 362.173 732.997 375.482C641.161 366.319 558.314 392.546 468.191 529.053C435.656 578.342 397.099 616.504 356.115 645.856L358.704 645.864C398.996 616.638 436.892 578.845 468.96 530.263C563.507 387.046 650.085 365.62 747.562 378.929C655.725 369.766 572.879 395.993 482.756 532.5C451.454 579.912 414.588 617.033 375.348 645.919L377.878 645.927C416.451 617.188 452.681 580.442 483.53 533.717C578.077 390.5 664.655 369.074 762.132 382.383C670.295 373.22 587.449 399.447 497.326 535.954C467.233 581.535 431.997 617.603 394.453 645.981L396.942 645.989C433.835 617.764 468.45 582.071 498.1 537.164C592.646 393.947 679.224 372.52 776.701 385.829C684.865 376.667 602.019 402.894 511.896 539.401C482.992 583.185 449.332 618.192 413.448 646.043L415.877 646.051C451.131 618.374 484.194 583.742 512.669 540.617C607.216 397.401 693.794 375.974 791.271 389.283C699.435 380.12 616.588 406.347 526.465 542.854C498.721 584.882 466.603 618.822 432.334 646.105L434.708 646.113C468.366 619.01 499.913 585.452 527.239 544.064C621.786 400.847 708.364 379.421 805.841 392.73C714.004 383.567 631.158 409.794 541.035 546.301C514.425 586.606 483.793 619.472 451.1 646.167L453.44 646.174C485.537 619.68 515.612 587.19 541.809 547.518C636.355 404.301 722.939 382.875 820.411 396.184C728.574 387.021 645.728 413.248 555.605 549.755C530.11 588.37 500.919 620.155 469.767 646.228L472.067 646.235C502.643 620.37 531.292 588.968 556.379 550.965C659.782 394.335 753.654 383.366 862.671 404.206C867.082 405.266 871.523 406.319 875.994 407.352C880.813 408.514 885.671 409.663 890.564 410.806C895.382 411.968 900.241 413.117 905.134 414.259C909.952 415.422 914.81 416.571 919.703 417.713C924.522 418.875 929.38 420.031 934.273 421.167C939.092 422.329 943.95 423.485 948.843 424.62C953.661 425.782 958.52 426.931 963.413 428.074C968.231 429.236 973.089 430.385 977.982 431.527C997.256 436.177 1017.1 440.666 1037.61 444.551C1059 448.6 1079.01 452.881 1098.06 456.963C1224.39 484.01 1308.57 502.003 1439.62 376.832L1439.62 374.816C1339.88 470.279 1267.34 482.7 1182.74 471.268C1265.29 479.672 1338.24 461.378 1439.63 359.061L1439.64 357.017C1332.25 465.594 1256.84 479.8 1168.17 467.815C1254.63 476.623 1330.56 456.132 1439.65 340.507L1439.65 338.444C1324.39 460.813 1246.23 476.885 1153.6 464.361C1243.86 473.552 1322.64 450.839 1439.67 321.259L1439.67 319.141C1316.31 455.964 1235.52 473.943 1139.03 460.907C1232.99 470.482 1314.51 445.451 1439.69 301.316L1439.69 299.192C1307.99 451.074 1224.7 471.008 1124.47 457.46C1222.03 467.397 1306.18 440.035 1439.71 280.766L1439.71 278.621C1299.47 446.137 1213.78 468.058 1109.9 454.014C1210.97 464.306 1297.64 434.571 1439.73 259.636L1439.73 257.465C1290.74 441.151 1202.77 465.088 1095.33 450.567C1198.51 461.075 1286.68 429.888 1434.15 245.03C1436.02 242.682 1437.89 240.382 1439.75 238.088L1439.75 235.903C1437.66 238.472 1435.56 241.076 1433.46 243.706C1279.4 436.824 1190.18 461.904 1080.76 447.113C1183.94 457.622 1272.11 426.434 1419.58 241.576C1426.37 233.067 1433.1 224.908 1439.77 217.086L1439.77 214.969C1432.87 223.039 1425.91 231.447 1418.89 240.252C1264.83 433.371 1175.61 458.451 1066.19 443.659C1169.37 454.168 1257.54 422.981 1405.01 238.123C1416.79 223.364 1428.38 209.671 1439.78 196.954L1439.79 194.864C1428.16 207.802 1416.33 221.744 1404.32 236.805C1250.26 429.924 1161.04 455.004 1051.62 440.213C1154.8 450.721 1242.97 419.534 1390.44 234.676C1407.27 213.574 1423.74 194.656 1439.8 177.666L1439.8 175.588C1423.52 192.774 1406.82 211.954 1389.75 233.358C1235.7 426.477 1146.47 451.557 1037.05 436.766C1140.24 447.274 1228.41 416.087 1375.88 231.229C1397.86 203.696 1419.21 179.858 1439.83 159.213L1439.84 157.19C1418.99 178.002 1397.41 202.069 1375.2 229.918C1221.14 423.037 1131.92 448.117 1022.5 433.326C1125.68 443.834 1213.86 412.647 1361.32 227.789C1388.51 193.704 1414.75 165.262 1439.85 141.57L1439.85 139.587C1414.54 163.42 1388.06 192.077 1360.63 226.471C1206.57 419.59 1117.35 444.67 1007.93 429.879C1111.11 440.387 1199.29 409.2 1346.75 224.342C1379.25 183.591 1410.4 150.922 1439.87 124.784L1439.87 122.828C1410.19 149.087 1378.81 181.964 1346.06 223.018C1192 416.136 1102.78 441.216 993.36 426.425C1096.54 436.934 1184.72 405.747 1332.18 220.888C1370.1 173.35 1406.17 136.799 1439.88 108.806L1439.88 106.877C1405.97 134.971 1369.66 171.723 1331.49 219.571C1177.44 412.69 1088.21 437.77 978.79 422.978C1081.97 433.487 1170.15 402.3 1317.61 217.441C1361.06 162.975 1402.08 122.92 1439.9 93.645L1439.9 91.7633C1401.87 121.112 1360.61 161.341 1316.92 216.117C1162.87 409.236 1073.64 434.316 964.22 419.525C1067.4 430.033 1155.58 398.846 1303.04 213.988C1352.13 152.445 1398.13 109.297 1439.91 79.3335L1439.91 77.4654C1397.93 107.495 1351.69 150.811 1302.35 212.67C1148.3 405.789 1059.07 430.869 949.651 416.078C1052.83 426.586 1141.01 395.399 1288.47 210.541C1343.35 141.746 1394.36 95.9305 1439.92 65.8246L1439.92 63.9902C1394.16 94.1426 1342.91 140.106 1287.78 209.217C1133.73 402.336 1044.51 427.416 935.086 412.624C1038.27 423.133 1126.44 391.946 1273.91 207.087C1334.71 130.86 1390.78 82.8415 1439.93 53.1588L1439.93 51.3513C1390.57 81.0671 1334.27 129.219 1273.21 205.763C1119.16 398.882 1029.94 423.962 920.516 409.171C1023.7 419.679 1111.87 388.492 1259.34 203.634C1326.24 119.764 1387.4 70.0432 1439.94 41.3225L1439.95 39.5353C1387.21 68.2755 1325.8 118.117 1258.64 202.31C1104.59 395.428 1015.37 420.508 905.946 405.717C1009.13 416.225 1097.3 385.038 1244.77 200.18C1317.96 108.434 1384.28 57.5424 1439.95 30.3225L1439.96 28.5555C1384.08 55.795 1317.52 106.8 1244.07 198.869C1090.02 391.988 1000.8 417.068 891.377 402.277C994.56 412.785 1082.73 381.598 1230.2 196.74C1309.89 96.8341 1381.43 45.3798 1439.96 20.1723L1439.97 18.4256C1381.24 43.6391 1309.45 95.2005 1229.5 195.416C1075.45 388.535 986.227 413.614 876.807 398.823C979.99 409.332 1068.16 378.145 1215.63 193.286C1302.07 84.925 1378.92 33.5555 1439.97 10.8719L1439.97 9.14537C1378.72 31.835 1301.62 83.2981 1214.93 191.962C1060.88 385.081 971.657 410.161 862.237 395.37C965.421 405.878 1053.59 374.698 1201.06 189.833C1294.53 72.666 1376.79 22.1236 1439.98 2.43477L1439.98 0.714966Z"
        fill="#868AC3" fill-opacity="0.6" />
    </svg>
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
  <script src="./js/addFundSettings.js"></script>
  <script src="./js/selectWithImage.js"></script>
  <script src="./js/all.js"></script>
  <script src="./js/addFund.js"></script>
</body>

</html>

<!-- figma address:   https://www.figma.com/file/eGXSO6qLuLuPud3fsYbhMW/Meta-bd?node-id=11%3A280&mode=dev -->