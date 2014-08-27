<?php
include_once "Database.php";
include_once 'Session.php';

/**
 * Description of Category
 *
 * @author Justin
 */
class User {
    public $name;
    public $id;
    public $password;

    function __construct($username = null, $password = null) {
        if($username == null)
            return;

        $key = Session::generateKey($password);
        $data = array($username, base64_encode($key));

        Database::execute("INSERT INTO `Users` SELECT MAX(ID) + 1, ?, ? FROM Users", $data);
    }

    function save()
    {
        if($this->password != null)
        {
            $key = Session::generateKey($this->password);

            $data = array(base64_encode($key), $this->id);
            Database::execute("Update `Users` set `Key` = ? WHERE ID = ?", $data);
        }
        /** todo: Create save function
         * **/
    }

    static function get($id) {
        $user = new User();

        $query = "SELECT * FROM Users WHERE ID = ?";
        $data = array($id);
        $result = Database::getRow($query, $data);

        return User::getFromRow($result);
    }

    private static function getFromRow($row) {
        $user = new User();

        $user->name = $row["USERNAME"];
        $user->id = $row["ID"];

        return $user;
    }

    function getUsers() {
        $query = "SELECT * FROM Users WHERE ID <> 0";
        $result = Database::getResults($query);
        $users = array();

        foreach ($result as $it) {
           $user = User::getFromRow($it);

           $users[] = $user;
        }

        return $users;
    }

    static function getAuthorizedCategories($userID = null) {
        if($userID == null) {
            $userID = $_SESSION['UserID'];
        }

        $query = "SELECT * FROM CategoryAccess a INNER JOIN Category c ON a.CategoryID = c.ID WHERE a.UserID = ?";
        $data = array($userID);
        
        $result = Database::getResults($query, $data);
        $categories = array();

        foreach ($result as $it) {
           $category = new Category();

           $category->name = decrypt($it["NAME"]);
           $category->id = $it["ID"];

           $categories[] = $category;
        }

        return $categories;
    }

    static function getKey($username) {
        $query = "SELECT  `KEY` FROM `Users` WHERE username =  ?";
        $data = array($username);

        $userKey = Database::getOne($query, $data);
        $userKey = base64_decode($userKey);

        return $userKey;
    }

    static function updateKey($username, $value) {
        $value = base64_encode($value);

        $query =  $GLOBALS['db']->prepare("UPDATE `Users` SET  `KEY` = ? WHERE username =  ?");
        $data = array($value, $username);

        $dbKey = $GLOBALS['db']->execute($query, $data);

        if (PEAR::isError($dbKey)) {
            die($dbKey->getMessage() . ', ' . $dbKey->getDebugInfo());
        }
    }
}
?>