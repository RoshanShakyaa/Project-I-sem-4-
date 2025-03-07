<?php 
session_start();
include '../connect.php';

// Check if the user is NOT logged in
if (!isset($_SESSION['user_id'])) {
    // Check if the user has a valid remember_me cookie
    if (isset($_COOKIE['remember_me']) && isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
        $token = $_COOKIE['remember_me'];
        
        // Fetch the user from the database based on the user_id
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user) {
            // Regenerate the token from the user's email
            $expected_token = hash('sha256', $user['email'] . 'secret_key');
            
            if ($token === $expected_token) {
                // Set the session since the cookie is valid
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
            } else {
                // Invalid token; delete cookies and redirect to login
                setcookie('remember_me', '', time() - 3600, "/");
                setcookie('user_id', '', time() - 3600, "/");
                header("Location: ../auth/login.php");
                exit();
            }
        } else {
            // User does not exist delete cookies and redirect to login
            setcookie('remember_me', '', time() - 3600, "/");
            setcookie('user_id', '', time() - 3600, "/");
            header("Location: ../auth/login.php");
            exit();
        }
    } else {
        // If no session and no valid cookies, redirect to login page
        header("Location: ../auth/login.php");
        exit();
    }
}

// Prevent logged-in users from accessing login/registration pages
if (basename($_SERVER['PHP_SELF']) == 'login.php' || basename($_SERVER['PHP_SELF']) == 'registration.php') {
    header("Location: ../pages/dashboard.php");
    exit();
}
?>
