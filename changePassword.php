<?php
// Assuming you have a session started and a user identified


include './db.php';

session_start();
// Assuming you have the user's ID stored in the session
$userId = $_SESSION['user_id'];

$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];

// Fetch the current password for the user
$sql = "SELECT user_pass FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedCurrentPassword = $row['user_pass'];

    // Verify the current password
    // Assuming passwords are stored hashed, use password_verify
    if ($currentPassword == $hashedCurrentPassword) {
        // Hash the new password
        $hashedNewPassword = $newPassword;

        // Update the password in the database
        $updateSql = "UPDATE user SET user_pass = ? WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $hashedNewPassword, $userId);

        if ($updateStmt->execute()) {
            // echo "Password updated successfully.";
            echo 1;
        } else {
            // echo "Error updating password.";
            echo 0;
        }
    } else {
        // echo "Current password is incorrect.";
        echo 2;
    }
} 

$stmt->close();
$conn->close();
?>
