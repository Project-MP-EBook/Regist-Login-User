<?php 
session_start();
include("koneksi.php");

$message = ""; // Variabel untuk menyimpan pesan error
$showPasswordModal = false;

if(isset($_GET['email'])){
    $email = $_GET['email'];
    $query = mysqli_query($konek, "SELECT * FROM users WHERE email='$email' AND role='user'") or die(mysqli_error($konek));
    $cek = mysqli_num_rows($query);

    if($cek > 0){
        while($data = mysqli_fetch_array($query)){
            $_SESSION['user_id'] = $data['user_id'];
            $question = $data['question'];
            $_SESSION['answer'] = $data['answer'];
        }
    } else {
        // Jika email tidak ditemukan, kirim pesan lewat GET
        $message = "Email tidak ditemukan. Silakan coba lagi.";
    }
}

// Proses pengecekan jawaban pertanyaan keamanan
if (isset($_GET['security_answer'])) {
    $security_answer = $_GET['security_answer'];

    // Verifikasi jawaban
    if ($security_answer === $_SESSION['answer']) {
        $showPasswordModal = true;
    } else {
        $message = "Jawaban salah. Silakan coba lagi.";
    }
}

if (isset($_GET['new_password']) && isset($_GET['confirm_password'])) {
    $new_password = $_GET['new_password'];
    $confirm_password = $_GET['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // Periksa jika password konfirmasi sama
    if ($new_password === $confirm_password) {
        // Enkripsi password baru
        //$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password di database
        $query = "UPDATE users SET password='$new_password' WHERE user_id='$user_id'";
        $result = mysqli_query($konek, $query);

        // if ($result) {
        //     // Redirect kembali ke halaman utama dengan pesan sukses
        //     header("Location: index.php?password_reset=success");
        //     exit();
        // } else {
        //     echo "Gagal memperbarui password. Silakan coba lagi.";
        // }
    } else {
        echo "Password tidak cocok. Silakan coba lagi.";
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
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <style>
        body {
            margin: 0;
            height: 100vh;
            padding: 0;
            display: flex;
            font-family: "Poppins", sans-serif;
            color: #828282;
        }

        img.left {
            height: 100vh;
            width: 50%;
            object-fit: cover;
        }

        h2 {
            font-weight: bolder;
            margin-bottom: 20px;
            text-align: center;
            color: #525252;
        }

        .right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        input::placeholder {
            color: #E0E0E0;
        }

        a {
            text-decoration: none;
            color: #1E5B86;
        }

        p {
            color: red;
        }
        .btn {
            margin-top: 20px;
            width: 100%;
            background-color: #1E5B86;
            color: white;
        }

        .verification-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 10px 0;
        }

        .verification-inputs input {
            width: 400px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
        }
    </style>
    <title>Forgot Password</title>
</head>

<body>
    <img class="left" src="side-bg.png" alt="side background">
    <div class="right">
        <div class="form">
            <h2>Forgot Password</h2>
            <h6>Enter your email for the verification process</h6>
            
            <!-- Display message if email not found -->
            <?php if (!empty($message)): ?>
        <p>
            <?= $message ?>
        </p>
    <?php endif; ?>

    <form action="#" method="GET">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="email@gmail.com" required name="email">
        </div>
        <button type="submit" class="btn">
            Continue
        </button>
    </form>

    <!-- Modal untuk Pertanyaan Keamanan -->
    <?php if(isset($question) && !$showPasswordModal): ?>
    <div class="modal fade show" id="securityQuestionModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="securityQuestionModalLabel" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="securityQuestionModalLabel">Verification</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h6><?= $question; ?></h6>
                    <form method="GET">
                        <div class="verification-inputs">
                            <input type="text" name="security_answer" class="form-control" required placeholder="Your answer">
                        </div>
                        <button type="submit" class="btn btn-verify">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal untuk Pengaturan Ulang Password -->
    <?php if ($showPasswordModal): ?>
    <div class="modal fade show" id="resetPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="resetPasswordModalLabel">New Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h6>Set the new password for your account so you can login and access all features.</h6>
                    <form method="GET">
                        <div class="mb-3">
                            <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['new_password'])): ?>
    <!-- Modal Sukses Reset Password -->
    <div class="modal fade show" id="passwordResetSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="passwordResetSuccessModalLabel" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="verif.png" alt="Success Icon">
                    <h3>Verification</h3>
                    <h6>Your password has been reset successfully</h6>
                    <a href="login.php" class="btn btn-primary">Continue</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

        </div>
    </div>
</body>

</html>
