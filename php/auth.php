<?php
    include './admin.php';
    //Login Check
    if (isset($_POST['login'])) {
        $login = new auth();
        $login->login($_POST['email'],$_POST['password']);
    }else if (isset($_POST['request'])) {
        $request = new auth();
        $request->acc_request($_POST['fname'],$_POST['sname'],$_POST['email'],$_POST['password'],$_POST['role']);
    }else if (isset($_POST['reset'])) {
        $reset = new auth();
        $reset->password_reset($_POST['password']);
    } 
?>