<?php
    session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();

    setcookie('id', '', time() - 172800);
    setcookie('key', '', time() - 172800);

    header("location:../");
    exit;
?>