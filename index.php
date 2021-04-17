<?php
require 'function_new.php';

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

    $result = mysqli_query($conn, 
        "SELECT
            pm_user.uid,
            pm_user.nama,
            pm_user.username,
            pm_user.password,
            pm_user.level,
            GROUP_CONCAT(database_accessrole.page_type) as page_type,
            GROUP_CONCAT(database_accessrole.page_id SEPARATOR '|') as page_id,
            GROUP_CONCAT(database_accessrole.page_name SEPARATOR '|') as page_name,
            GROUP_CONCAT(database_accessrole.access_page SEPARATOR '|') as access_page,
            GROUP_CONCAT(database_accessrole.access_add SEPARATOR '|') as access_add,
            GROUP_CONCAT(database_accessrole.access_edit SEPARATOR '|') as access_edit,
            GROUP_CONCAT(database_accessrole.access_log SEPARATOR '|') as access_log,
            GROUP_CONCAT(database_accessrole.access_delete SEPARATOR '|') as access_delete,
            GROUP_CONCAT(database_accessrole.access_download SEPARATOR '|') as access_download,
            GROUP_CONCAT(database_accessrole.access_imagePreview SEPARATOR '|') as access_imagePreview
        FROM 
            pm_user
        LEFT JOIN
            ( SELECT
                database_page.page_type,
                GROUP_CONCAT(database_page.page_id) as page_id,
                GROUP_CONCAT(database_page.page_name) as page_name,
                GROUP_CONCAT(database_accessrole.access_page) as access_page,
                GROUP_CONCAT(database_accessrole.access_add) as access_add,
                GROUP_CONCAT(database_accessrole.access_edit) as access_edit,
                GROUP_CONCAT(database_accessrole.access_delete) as access_delete,
                GROUP_CONCAT(database_accessrole.access_log) as access_log,
                GROUP_CONCAT(database_accessrole.access_download) as access_download,
                GROUP_CONCAT(database_accessrole.access_imagePreview) as access_imagePreview,
                database_accessrole.user_id
            FROM
                database_accessrole
            LEFT JOIN
                (SELECT
                    database_page.page_id,
                    database_page.page_name,
                    database_page.page_type,
                    database_page.page_delete
                FROM
                    database_page
                WHERE
                    database_page.page_delete = 'N'
                ORDER BY
                    database_page.page_name
                ) as database_page
            ON
                database_accessrole.page_id = database_page.page_id
            GROUP BY
            database_page.page_type
            ) as database_accessrole
        ON
            pm_user.uid = database_accessrole.user_id
        WHERE
            (pm_user.username='$username' || pm_user.uid='$username') and
            pm_user.status = 'a'
        GROUP BY
            pm_user.uid
    ");

    //check user
    if (mysqli_num_rows($result) === 1) {
        // check password
        $row = mysqli_fetch_assoc($result);
        if ($password == $row["password"]) {
            // set Session 
            $_SESSION["login"]                  = true;
            $_SESSION["uid"]                    = $row["uid"];
            $_SESSION["username"]               = $row["username"];
            $_SESSION["level"]                  = $row["level"];

            $_SESSION["page_type"]              = $row["page_type"];
            $_SESSION["page_id"]                = $row["page_id"];
            $_SESSION["page_name"]              = $row["page_name"];
            $_SESSION["access_page"]            = $row["access_page"];
            $_SESSION["access_add"]             = $row["access_add"];
            $_SESSION["access_edit"]            = $row["access_edit"];
            $_SESSION["access_log"]             = $row["access_log"];
            $_SESSION["access_delete"]          = $row["access_delete"];
            $_SESSION["access_download"]        = $row["access_download"];
            $_SESSION["access_imagePreview"]    = $row["access_imagePreview"];

            //check remember me
            if (isset($_POST['remember'])) {
                //set cookie
                setcookie('uid', $row['uid'], time() + 86400);
                setcookie('key', hash('sha256', $row['username']), time() + 86400);
            }
            header("location:program_new/");
            exit;
        }
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>YES 5.2</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/login_css.css">
    <link rel="icon" type="image/png" href="images/icons/favicon.png" />
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