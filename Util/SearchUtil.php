<?php
include_once '../BL/Account.php';

function search($searchString) {
	$searchString = strtolower($searchString);
	
	if($searchString == null) {
		$searchString = "";
	}
	
	$matchAccount = array();
	
	foreach(Account::getAllAccounts() as $it) {
		if(isAccountMatch($it, $searchString))
		{
			$matchAccount[] = $it;
		}
	}
	
	return $matchAccount;
}

function isAccountMatch($account, $searchString) {
	if(isAccountColumnMatch($account->url, $searchString))
		return true;
		
	if(isAccountColumnMatch($account->server, $searchString))
		return true;
		
	if(isAccountColumnMatch($account->category, $searchString))
		return true;
		
	if(isAccountColumnMatch($account->id, $searchString))
		return true;
		
	if(isAccountColumnMatch($account->username, $searchString))
		return true;
		
	if(isAccountColumnMatch($account->password, $searchString))
		return true;
	
	return false;
}

function isAccountColumnMatch($columnValue, $searchString) {
	$columnValue = strtolower($columnValue);
	$i = strpos($columnValue, $searchString);
	
	if($i !== false) {
		return true;
	}
	
	return false;
}