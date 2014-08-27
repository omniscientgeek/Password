<?php
include_once '../BL/Session.php';
include_once '../BL/Database.php';

if(isDev() == false && $_SERVER["HTTPS"] == false)
{
}

if(Session::IsLoggedIn()) {
    header('Location: Accounts.php?category=3');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/Login.css" />
        <title>Storage</title>
        <script type="text/javascript" src="js/jquery.query-2.1.7.js"></script>
    </head>
    <body>
        <form action="Login.php" method="post">
             <p>Username: <input id="username" type="text" name="username" /></p>
             <p>Password: <input id="password" type="password" name="password" /></p>
             <p><input name="Login" type="submit" value="Login" /></p>
        </form>
    </body>
</html>
