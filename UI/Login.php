<?php
include_once '../BL/Session.php';

if(Session::LogIn()) {
    header('Location: Accounts.php?category=3');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <title>Password Storage</title>
    </head>
    <body>
        <form action="Login.php" method="post">
             <p>Username: <input id="username" type="text" name="username" /></p>
             <p>Password: <input id="password" type="password" name="password" /></p>
             <p><input name="Login" type="submit" value="Login" /></p>
        </form>
    </body>
</html>
