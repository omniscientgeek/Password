<?php
include_once '../BL/Database.php';
include_once 'SecurePage.php';

//test();
//generateSharedKey();
//generateUserKey();
//validate();

function getDbKey($username) {
    $query = "SELECT `Key` FROM `Group` WHERE `Username` = ?";
    $data = array($username);

    $userKey = Database::getOne($query, $data);
    $userKey = base64_decode($userKey);

    return $userKey;
}

function updateKey($username, $value) {
    $value = base64_encode($value);

    $query =  $GLOBALS['db']->prepare("DELETE FROM `Group` WHERE Username = ?");
    $data = array($username);
    $dbKey = $GLOBALS['db']->execute($query, $data);

    if (PEAR::isError($dbKey)) {
        die($dbKey->getMessage() . ', ' . $dbKey->getDebugInfo());
    }

    echo "Insert: $username, $value<br />";

    $query =  $GLOBALS['db']->prepare("INSERT INTO `Group` ( `Username` , `Key` ) VALUES ( ?,  ? )");
    $data = array($username, $value);

    $dbKey = $GLOBALS['db']->execute($query, $data);

    if (PEAR::isError($dbKey)) {
        die($dbKey->getMessage() . ', ' . $dbKey->getDebugInfo());
    }
}

function validate()
{
    $userKey = getDbKey('pettingj');
    $password = $_SESSION['password'];

    $sharedKey = encryptBasic($password, $userKey);

    $hashKey = hash('sha512', $sharedKey, true);
    $dbKey = getDbKey('shared');

    if($hashKey != getDbKey('shared'))
    {
        $dbKey = getUserKey();
        echo "Shared key does not match ";
        return;
    }

    echo "Success!";
}

function getUpgradeKey() {
    $username = 'old';
    $query =  "SELECT `Key` FROM `Group` WHERE Username = ?";
    $data = array($username);

    $key = $GLOBALS['db']->getOne($query, $data);

    if (PEAR::isError($key)) {
        die($key->getMessage() . ', ' . $key->getDebugInfo());
    }
    $username = 'pettingj';

    $key = decryptBasic($username, base64_decode($key));

    $_SESSION['key'] = $key;

    return $key;
}

function generateSharedKey()
{
    $key = getUpgradeKey();

    $hashKey = hash('sha512', $key, true);

    updateKey("shared", $hashKey);
}

function generateUserKey()
{
    $userKey = getUserKey();

    updateKey("pettingj", $userKey);
}

function getUserKey()
{
    $sharedKey = getUpgradeKey();
    $password = $_SESSION['password'];

    echo "1: $sharedKey 2: $password<br />";

    $userKey = decryptBasic($password, $sharedKey);

    return $userKey;
}