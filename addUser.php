<?php
include './db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    
    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        echo "Please fill all the required fields.";
    }
    elseif (strlen($password) < 6) {
        // echo "Password must be at least 6 characters long.";
        echo 3; 
    } elseif (!preg_match('/[0-9]/', $password)) {
        // echo "Password must include at least one number.";
        echo 4;
    } 
    else {

       // Check if the email already exists
      $existingSql = "SELECT user_id FROM user WHERE user_email = ?";
      $existingStmt = mysqli_prepare($conn, $existingSql);

      if ($existingStmt) {
      mysqli_stmt_bind_param($existingStmt, "s", $email);
      mysqli_stmt_execute($existingStmt);
      mysqli_stmt_store_result($existingStmt);

      if (mysqli_stmt_num_rows($existingStmt) > 0) {
        
          echo 2;
        
      } 
      else {

      
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_password = $password;
        // Insert query
        $addUserSql = "INSERT INTO user (user_name, user_email, user_pass) VALUES (?, ?, ?)";
        
        // Prepare statement
        $addUserStmt = mysqli_prepare($conn, $addUserSql);
            if ($addUserStmt) {
                // Bind parameters
                mysqli_stmt_bind_param($addUserStmt, "sss", $name, $email, $hashed_password);
                
                // Execute query
                if(mysqli_stmt_execute($addUserStmt)) {
                    // echo "Registration successful.";
                    echo 1;
                } 
                else {
                    // echo "Error during registration. " . mysqli_error($conn);
                    echo 0;
                }
                
                
            } 
            else {
                // echo "Error preparing the statement. " . mysqli_error($conn);
                echo 0;
            }
      }
    }
    
    
}

}
?>
