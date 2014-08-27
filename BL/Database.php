<?php
require_once("DB.php"); 

if(isDev()) {
	$GLOBALS['db'] = DB::connect('mysql://foo:bar@mysql/omniscientphp_test');
} else {
	$GLOBALS['db'] = DB::connect('mysql://foo:bar@mysql/omniscientphp');
}

if (PEAR::isError($GLOBALS['db'] )) {
    die($GLOBALS['db']);
}

function isDev()
{
    if($_SERVER['HTTP_HOST'] == "localhost:8080")
    {
        return true;
    }

    return false;
}

class Database {
    static function getResults($query, $data = null)
    {
        $result = $GLOBALS['db']->getALL($query, $data, DB_FETCHMODE_ASSOC);

        if (PEAR::isError($result)) {
            die($result->getMessage() . ', ' . $result->getDebugInfo());
        }

        return $result;
    }

    function getOne($query, $data = null)
    {
        $result = $GLOBALS['db']->getOne($query, $data, DB_FETCHMODE_ASSOC);

        if (PEAR::isError($result)) {
            die($result->getMessage() . ', ' . $result->getDebugInfo());
        }

        return $result;
    }

    function getRow($query, $data = null)
    {
        $result = $GLOBALS['db']->getRow($query, $data, DB_FETCHMODE_ASSOC);

        if (PEAR::isError($result)) {
            die($result->getMessage() . ', ' . $result->getDebugInfo());
        }

        return $result;
    }

    function execute($query, $data) {
        $sth = $GLOBALS['db']->prepare($query);
        $result = $GLOBALS['db']->execute($sth, $data);

        if (PEAR::isError($result)) {
            die($result->getMessage() . ', ' . $result->getDebugInfo());
        }
    }
}

function getIV() {
    return base64_decode("mJ46ITuwOgXzDbPaQ0Eiy8+6YoXtPph9yY7Vn5uZ9HY=");
}

function getKey() {
    return $_SESSION['key'];
}

function encrypt($value) {
    $key =  getKey();

    return encryptBasic($key, $value);
}

function decrypt($value) {
    $key =  getKey();

    return decryptBasic($key, $value);
}

function encryptBasic($key, $value) {
    $iv = getIV();

    return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_CFB, $iv);
}

function decryptBasic($key, $value) {
    $iv = getIV();

    return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_CFB, $iv);
}
?>