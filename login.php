<?php 
     session_start();
     include("koneksi.php");
     if(isset($_POST['email'])){
         $email = $_POST['email'];
         $password = $_POST['password'];
         $query = mysqli_query($konek,"select * from users where email='$email' and password='$password' and role='user'")or die (mysqli_error($konek));
         $cek = mysqli_num_rows($query);
         if($cek > 0){
         while($data=mysqli_fetch_array($query)){
             $_SESSION['email'] = $data['email'];
             $_SESSION['user_id'] = $data['user_id'];
         }
         $_SESSION['status'] = "login";
         header("location:index.php");
         }else{
         header("location:login.php?pesan=gagal");
         }
     }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            height: 100vh;
            padding: 0;
            display: flex;
            font-family: "Poppins", sans-serif;
            color: #828282;
        }
        img {
            height: 100vh; 
            width: 50%; 
            object-fit: cover; 
        }
        h2{
            font-weight:bolder;
            margin-bottom: 40px;
            color: #525252;
        }
        .right {
            width: 50%;
            display: flex;
            align-items: center;  /* Center vertically */
            justify-content: center; /* Center horizontally */
            flex-direction: column;
        }
        .remember-me{
            display: flex;
            justify-content: space-between;
        }
        .form{
            padding-bottom: 100px
        }
        input::placeholder {
            color: #E0E0E0;
        }
        a{
            text-decoration: none;
            color: #1E5B86;
        }
        .btn{
            margin-top: 20px;
            width: 100%;
            background-color: #1E5B86;
            color: white;
        }
        .pesan{
            color: red;
        }
    </style>
    <title>Login</title>
</head>
<body>
    <img src="side-bg.png" alt="side background">
    <div class="right">
        <div class="form">
            <h2>Login to Your Account</h2>
            <form action="login.php" method="POST">
                <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="email@gmail.com" name="email" required>
                </div>
                <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="write your password here" name="password" required>
                </div> 
                <?php 
                    if(isset($_GET['pesan'])){ ?>
                    <div class="pesan"><p>Login gagal, email atau password salah!</p></div>
                <?php
                    }
                ?>
                <div class="remember-me">
                    <div>
                        <input type="checkbox">
                        <label for="remember">Remember Me</label>
                    </div>
                    <a href="forgot-pw.php">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
        <p>Not Registered Yet? <a href="register.php">Create an account</a></p>
    </div>
</body>
</html>