<?php
set_include_path("." . PATH_SEPARATOR . ($UserDir = dirname($_SERVER['DOCUMENT_ROOT'])) . "/pear/php" . PATH_SEPARATOR . get_include_path());
include_once '../BL/Database.php';
include_once 'User.php';

session_start();

class Session {
    static function IsLoggedIn()
    {
        if(isset($_SESSION['username']) == false || $_SESSION['username'] == null) {
            return false;
        }

        return true;
    }

    static function LogOut() {
	unset($_SESSION['username']);
	unset($_SESSION['password']);
	unset($_SESSION['UserID']);
        unset($_SESSION['key']);
    }

    static function LogIn() {
        $username = stripslashes($_REQUEST['username']);
        $password = $_REQUEST['password'];

        $data = array($username);
        $result = Database::getResults("SELECT * FROM `Users` WHERE username = ?", $data);
        $num = count($result);

        if($num == 0)
        {
            echo "Invalid username <br />";

            return false;
        }

        $key = Session::getKey($username, $password);

        if($key == null) {
            echo "Invalid password <br />";
            return false;
        }

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['UserID'] = $result[0]["ID"];
        $_SESSION['key'] = $key;

        return true;
    }

    static function getKey($username, $password) {
        $userKey = User::getKey($username);

        $sharedKey = encryptBasic($password, $userKey);
        
        $hashKey = hash('sha512', $sharedKey, true);

        if($hashKey != User::getKey('shared'))
        {
            return null;
        }

        return $sharedKey;
    }

    static function generateKey($password) {
        $sharedKey = $_SESSION['key'];

        return decryptBasic($password, $sharedKey);
    }
}
?>
