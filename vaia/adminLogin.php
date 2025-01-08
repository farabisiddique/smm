<?php
include './db.php'; // Your database connection file

function validateUser($username,$pass,$conn){
  $query = "SELECT * FROM admin WHERE username = ? AND password = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $pass); // 'ss' specifies the variable types => 'string', 'string'
  $stmt->execute();
  $result = $stmt->get_result();
  $userArray = array();
  // Check if the user exists
  if ($result->num_rows >0) {

    while($userRow = $result->fetch_assoc() ){
        array_push($userArray, $userRow);
    }
    $adminid = $userArray[0]['admin_id'];
    $validationResult = true;
  }
  else{
    $adminid = null;
    $validationResult = false;
  }

  return array($adminid,$validationResult);
}


// Retrieve data from AJAX request
$username = $_POST['username'];
$password = $_POST['password']; 



// Assume a user validation function exists and returns a user_id if successful
$validation = validateUser($username, $password,$conn); // Implement this function based on your auth system


if ($validation[1]) {
    session_start();
    $_SESSION['admin_id'] = $validation[0];

    $token = bin2hex(random_bytes(64)); // Generate a secure token
    $expires_at = date('Y-m-d H:i:s', strtotime('+30 days')); // Token expires in 30 days

    // Insert token into the database
    $insertToken = $conn->prepare("INSERT INTO admin_tokens (admin_id, token, expires_at) VALUES (?, ?, ?)");
    $insertToken->bind_param("iss", $validation[0], $token, $expires_at);
    $insertToken->execute();

    // Set a cookie with the token
    setcookie("adminCookie", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
    

    echo json_encode(array("success" => 1));
} 
else {
    echo json_encode(array("success" => 0));
}
?>
