<?php
session_start();
require 'function.php';


if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    //ambil username berdasarkan id
    $result = mysqli_query($conn, "SELECT username FROM pm_user where uid = '$id'");

    $row = mysqli_fetch_assoc($result);

    //cek cookie dan username
    if ($key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    }
}


if (isset($_SESSION["login"])) {
    header("location:program/");
    exit;
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];

    $vpass        = htmlentities($_POST["password"], ENT_QUOTES);
    $password     = md5("pmart" . "$vpass");

    $result = mysqli_query($conn, "SELECT username, password, uid, level FROM pm_user where (username='$username' and status='a') or (uid='$username' and status='a')");

    //check user
    if (mysqli_num_rows($result) === 1) {
        // check password
        $row = mysqli_fetch_assoc($result);
        if ($password == $row["password"]) {
            // set Session 
            $_SESSION["login"]         = true;
            $_SESSION["uid"]         = $row["uid"];
            $_SESSION["username"]     = $row["username"];
            $_SESSION["level"]         = $row["level"];

            //check remember me
            if (isset($_POST['remember'])) {
                //set cookie
                setcookie('uid', $row['uid'], time() + 86400);
                setcookie('key', hash('sha256', $row['username']), time() + 86400);
            }

            header("location:program/");
            exit;
        }
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>YES 5.1</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/login_css.css">
    <script src="program/js/64d58efce2.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" method="post" class='sign-in-form'>
                    <img class="logo" src="images/Logo YES.png">
                    <?php if (isset($error)) : ?>
                        <p style='color:red;'><i>Username / Password Salah</i></p>
                    <?php endif; ?>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" id="username" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>
                    </div>
                    <input type="submit" value="login" name="login" class="btn solid">
                </form>
            </div>
        </div>
    </div>
</body>

</html>