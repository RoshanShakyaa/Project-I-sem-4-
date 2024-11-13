<?php 
    include "../connect.php";
    include "./verify.php";


    $error_msg = "";
    $log_error = "";

    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember_me = isset($_POST['remember_me']);

        if(empty($email) OR empty($password)){
            $error_msg = "All fields are required";
        }else{
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn,$sql);
            $user = mysqli_fetch_array($result, mode: MYSQLI_ASSOC);
            if($user && password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                if($remember_me){

                    setcookie('user_id', $user['user_id'],time()+(365*24*60*60),'/');
                    $token = hash('sha256', $user['email'] . 'secret_key');
                    setcookie('remember_me',$token,time()+(365*24*60*60),'/');
                }
               
                header("Location: ../pages/dashboard.php");
                exit();
               
            }else{
                $log_error = "Email or password don't match";
            }
        }

       
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="form-container">
             <form action="login.php" method="post">
                <h1 class="text-center">Log In</h1>
                <img src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg" alt="">
                
                <div class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" placeholder="Password">
                    <div class="error-msg"><?php echo isset($log_error) ? $log_error: '' ?></div>
                </div>
                <div class="d-flex align-items-center gap-1">

                    <input type="checkbox" name="remember_me"  id="remember_me"> <label for="remember_me">remember me</label>
                </div>
                <div class="error-msg"><?php echo isset($error_msg) ? $error_msg : '' ?></div>
                    <input type="submit" class="btn sign-up-btn " name="login" value="Log In">

                    <small class="text-center">Don't have an account? <a href="./registration.php">Sign Up</a></small>
            </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>