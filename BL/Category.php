<?php
include_once "Database.php";

/**
 * Description of Category
 *
 * @author Justin
 */
class Category {
    public $name;
    public $id;
    static $categoryName = array();

    function __construct() {
    }

    static function create($name) {
        $category = new Category();
        
        $name = encrypt($name);
        
        $data = array(Category::getNextCategoryId(), $name);

        Database::execute("INSERT INTO Category VALUES (?, ?)", $data);
    }

    static function getCategoryName($categoryId) {
        if(isset(Category::$categoryName[$categoryId]))
        {
            return Category::$categoryName[$categoryId];
        }

        $query = "SELECT NAME FROM Category WHERE ID = ?";
        $data = array($categoryId);
        $result = Database::getOne($query, $data);

        $result = decrypt($result);

        Category::$categoryName[$categoryId] = $result;

        return $result;
    }

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    function getCategories() {
        $query = "SELECT * FROM Category";
        $result = Database::getResults($query);
        $categories = array();

        foreach ($result as $it) {
           $category = new Category();

           $category->name = decrypt($it["NAME"]);
           $category->id = $it["ID"];

           $categories[] = $category;
        }

        return $categories;
    }
    
    static function getCategoriesAccess($categoryId) {
    	$query = "SELECT u.ID AS UserID, a.CategoryID FROM Users u LEFT JOIN CategoryAccess a ON u.ID = a.UserID  AND a.CategoryID = ?";
    	$data = array($categoryId);
        $result = Database::getResults($query, $data);
        $users = array();

        foreach ($result as $it) {
        	$user = array();
        	
        	$user["UserID"] = $it["UserID"];
        	
        	if($it["CategoryID"] != null)
        	{
        		$user["Authorized"] = true;
        	}
        	else
        	{
        		$user["Authorized"] = false;
        	}
        	
           	$users[] = $user;
        }

        return $users;
    }

	static function GiveUserAccess($categoryID, $userID) {
		$query = "SELECT * FROM CategoryAccess WHERE CategoryID = ? AND UserID = ?";
		$data = array($categoryID, $userID);
		
		$result = Database::getResults($query, $data);
		
		if(count($result) > 0)
		{
			//Already authorized
			return;
		}
		
		$query = "INSERT INTO CategoryAccess SELECT ?, ?";
		$data = array($categoryID, $userID);
		
		Database::execute($query, $data);
	}
	
	static function RemoveUserAccess($categoryID, $userID) {
		$query = "DELETE FROM CategoryAccess WHERE CategoryID = ? AND UserID = ?";
		$data = array($categoryID, $userID);
		
		Database::execute($query, $data);
	}

    static function getNextCategoryId() {
        $query = "SELECT MAX(ID) From Category";
        $result = $GLOBALS['db']->getOne($query);

        if (PEAR::isError($result)) {
            die($result->getMessage());
        }

        return $result + 1;
    }
}
?>
