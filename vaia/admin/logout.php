<?php
session_start();

// Assuming you have a database connection set up
include '../db.php';

$user_id = $_SESSION['admin_id'] ?? null; // Get the user ID from the session

if ($user_id) {
    // Delete the user's remember-me token from the database
    $deleteToken = $conn->prepare("DELETE FROM admin_tokens WHERE admin_id = ?");
    $deleteToken->bind_param("i", $user_id);
    $deleteToken->execute();
}

// Clear the $_SESSION array
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Clear remember-me cookie
setcookie("adminCookie", "", time() - 3600, "/");

// Redirect to the login page
header("Location: ../index.php"); // Replace with your login page URL
exit();
?>
