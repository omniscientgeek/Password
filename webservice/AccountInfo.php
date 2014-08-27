<?php

include_once '../BL/Database.php';
include_once '../Util/SecurePage.php';
include_once '../BL/Account.php';
include_once '../BL/User.php';
include_once '../BL/Category.php';

ProcessResult();

function ProcessResult() {
    $op = $_REQUEST['op'];

    switch($op) {
        case "categoryAccess":
            CategoryAccess();
            break;
        case "accountLocation":
            AccountLocation();
            break;
        case "userAccess":
            UserAccess();
            break;
    }
}

function AccountLocation() {
    $accountId = $_REQUEST['id'];
    
    header('Content-Type: application/xml; charset=ISO-8859-1');
    $doc = new DOMDocument('1.0');
    
    $account = Account::get($accountId);

    $root = $doc->createElement('Result');
    $root->setAttribute('category', $account->category);
    $root->setAttribute('enviroment', $account->enviroment);
    $root = $doc->appendChild($root);
    
    echo $doc->saveXML();
}

function CategoryAccess() {
    $categoryId = $_REQUEST['id'];
    
    header('Content-Type: application/xml; charset=ISO-8859-1');
    $doc = new DOMDocument('1.0');

    $root = $doc->createElement('Result');
    $root->setAttribute('result', 'true');
    $root = $doc->appendChild($root);

    foreach (Category::getCategoriesAccess($categoryId) as $it) {
		$node = $doc->createElement("User");
		$node->setAttribute("id", $it["UserID"]);
		
		if($it["Authorized"] == false) {
			$node->setAttribute("selected", "false");
		}
		else
		{
			$node->setAttribute("selected", "true");
		}
		
		$root->appendChild($node);
    }
    
    echo $doc->saveXML();
}

function UserAccess() {
    $userId = $_REQUEST['id'];

    header('Content-Type: application/xml; charset=ISO-8859-1');
    $doc = new DOMDocument('1.0');

    $root = $doc->createElement('Result');
    $root->setAttribute('result', 'true');
    $root = $doc->appendChild($root);

    foreach (User::getAuthorizedCategories($userId) as $it) {
        $arr[$it->id] = 1;
    }

    foreach(Category::getCategories() as $it)
    {
        $node = $doc->createElement("User");
        $node->setAttribute("id", $it->id);

        if(isset($arr[$it->id])) {
            $node->setAttribute("selected", "true");
        }
        else
        {
            $node->setAttribute("selected", "false");
        }

        $root->appendChild($node);
    }

    echo $doc->saveXML();
}
?> 