<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <title>Password Storage</title>
        <script src="Password.js"></script>
        <script src="jquery-1.3.1.min.js"></script>
        <link type="text/css" href="css/start/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
    </head>
    <body>
<?php
include_once '../Util/SecurePage.php';
include_once '../BL/Database.php';

if(isset($_POST['text']))
{
	$txt = $_POST['text'];
	
	if(isset($_POST['Decrypt'])) {
	    echo(decrypt($txt));
	}
	else if(isset($_POST['Encrypt'])) {
	    echo(encrypt($txt));
	}
}
?>
        <form action="#" method="post">
            <input name="text"/>
            <input name="Encrypt" type="submit" value="Encrypt" />
            <input name="Decrypt" type="submit" value="Decrypt" />
        </form>
    </body>
</html>