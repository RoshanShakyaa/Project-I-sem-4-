<?php 
    include "../connect.php";
    include "verify.php";
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $error_msg = "";

        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_error= "";
        $email_error= "";


                                                    
        if(empty($username) OR empty($email) OR empty($password) ){
            $error_msg = "All fields are required";
        }else{
            if(strlen($password)<8){
                $password_error = "Password must be atleast 8 characters long";  
            }else{
                $stmt = "SELECT * FROM users WHERE email= '$email'";
                $emails = mysqli_query($conn, $stmt);
                $rowCount= mysqli_num_rows($emails);
                if($rowCount>0){
                    $email_error = "email or username already taken";
                } 
                
            }
           
        }
        if(empty($password_error) && empty($email_error) && empty($error_msg)){
                $sql= "insert into `users` (username,email,password) values ('$username','$email','$password_hash')";
                $result= mysqli_query($conn,$sql);
                if($result){
                    $user_id = mysqli_insert_id($conn);

                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;

                    setcookie('user_id', $user_id, time() + (365*24*60*60), '/');
                    $token = hash('sha256', $email . 'secret_key');
                    setcookie('remember_me', $token, time() + (365*24*60*60), '/');
                    header("Location: ../pages/dashboard.php");
                    exit();
                }else{
                    die( $conn->connect_error);
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
            <form action="registration.php" method="post" >
                <h1 class="text-center">Sign Up</h1>
                <img src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg" alt="">
                <div class="form-group">
                    <input class="form-control" type="text" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Email">
                    <div class="error-msg"><?php echo isset($email_error)? $email_error: '' ?></div>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" placeholder="Password">
                    <div class="error-msg"><?php echo isset($password_error) ? $password_error: '' ?></div>
                </div>
                <div class="error-msg"><?php echo isset($error_msg) ? $error_msg : '' ?></div>
                    <input type="submit" class="btn sign-up-btn " name="submit" value="Sign Up">

                    <small class="text-center">Already have an account? <a href="./login.php">Log in</a></small>
            </form>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
    </html>