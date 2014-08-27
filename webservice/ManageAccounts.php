<?php

include_once '../Util/SecurePage.php';
include_once '../BL/Database.php';
include_once '../BL/Account.php';
include_once '../BL/Category.php';
include_once "../BL/User.php";

include_once '../BL/Account.php';
include_once '../BL/Category.php';
include_once '../BL/User.php';

ProcessResult();
ReturnSuccess();

function ProcessResult() {
    $op = $_REQUEST['op'];

    switch($op) {
        case "move":
            Move();
            break;
        case "add":
            Add();
            break;
        case "update":
            Update();
            break;
        case "delete":
            Delete();
            break;
        case "updateAccessList";
            UpdateAccessList();
            break;
        case "loc":
            GetLocation();
            break;
        case "updateUser":
            UpdateUser();
            break;
        case "resetPassword":
            ResetPassword();
            break;
    }
}

function Move() {
    $id = $_REQUEST['id'];
    $newId = $_REQUEST['category'];

    $newId = encrypt($newId);
    $data = array($newId, $id);

    Database::execute("Update Data SET Category = ? WHERE ID = ?", $data);
}

function Add() {
    $category = $_REQUEST['category'];
    $server = $_REQUEST['server'];
    $enviroment = $_REQUEST['enviroment'];
    $url = $_REQUEST['url'];
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    Account::create($category, $server, $enviroment, $url, $username, $password);
}

function Update() {
    $account = new Account();
    $account->category = $_REQUEST['category'];
    $account->server = $_REQUEST['server'];
    $account->enviroment = $_REQUEST['enviroment'];
    $account->url = $_REQUEST['url'];
    $account->username = $_REQUEST['username'];
    $account->password = $_REQUEST['password'];
    $account->id = $_REQUEST['id'];

    $account->save();
}

function UpdateAccessList() {
    $categoryID = $_REQUEST['category'];
    
    foreach($_REQUEST as $userID => $permission) {
    	if(!is_numeric($userID))
    		continue;
    		
    	if($permission == "true")
    	{
    		Category::GiveUserAccess($categoryID, $userID);
    	}
    	else
    	{
    		Category::RemoveUserAccess($categoryID, $userID);
    	}
    }
}

function UpdateUser() {
    $userID = $_REQUEST['userId'];

    foreach($_REQUEST as $categoryID => $permission) {
    	if(!is_numeric($userID))
    		continue;

    	if($permission == "true")
    	{
    		Category::GiveUserAccess($categoryID, $userID);
    	}
    	else
    	{
    		Category::RemoveUserAccess($categoryID, $userID);
    	}
    }
}

function ResetPassword() {
    if(!isset($_REQUEST['userId'])) {
        echo "UserID is missing";
        return;
    }

    if(!isset($_REQUEST['password'])) {
        echo "Password is missing";
        return;
    }
    $userId = $_REQUEST['userId'];
    $password = $_REQUEST['password'];

    $user = User::get($userId);

    $user->password = $password;
    $user->save();
}

function Delete() {
    $id = $_REQUEST['id'];

    Account::delete($id);
}

function ReturnSuccess() {
    header('Content-Type: application/xml; charset=ISO-8859-1');
    $doc = new DOMDocument('1.0');

    $root = $doc->createElement('Result');
    $root->setAttribute('result', 'true');
    $root = $doc->appendChild($root);
    
    echo $doc->saveXML();
}
?> 
