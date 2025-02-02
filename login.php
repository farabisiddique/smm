<?php
include './db.php'; 

function validateUser($email,$pass,$conn){
  $query = "SELECT * FROM user WHERE user_email = ? AND user_pass = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $email, $pass); 
  $stmt->execute();
  $result = $stmt->get_result();
  $userArray = array();

  
  // Check if the user exists
  if ($result->num_rows >0) {

    while($userRow = $result->fetch_assoc() ){
        array_push($userArray, $userRow);
    }
    $userid = $userArray[0]['user_id'];
    $validationResult = true;
  }
  else{
    $userid = null;
    $validationResult = false;
  }

  return array($userid,$validationResult);
}


$email = $_POST['email'];
$password = $_POST['password']; 
$rememberMe = $_POST['rememberMe'];

$validation = validateUser($email, $password,$conn); 



if ($validation[1]) {
    session_start();
    $_SESSION['user_id'] = $validation[0];


    if ($rememberMe) {
        $token = bin2hex(random_bytes(64)); 
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 days')); 

        $insertToken = $conn->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $insertToken->bind_param("iss", $validation[0], $token, $expires_at);
        $insertToken->execute();

        setcookie("rememberMe", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
    }
    // echo 1;
    echo json_encode(array("success" => 1));
} 
else {
    echo json_encode(array("success" => 0));
}
?>
