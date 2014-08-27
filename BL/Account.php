<?php
include_once "Database.php";

class Account {
    public $category;
    public $id;
    public $server;
    public $enviroment;
    public $url;
    public $username;
    public $password;
    
    function __construct() {	
    
    }
    
    private static function loadRow($it) {
    	$account = new Account();
        $account->category = decrypt($it['CATEGORY']);
        $account->server = decrypt($it['SERVER']);
        $account->url = decrypt($it['Url']);
        $account->username = decrypt($it['USERNAME']);
        $account->password = decrypt($it['PASSWORD']);
        $account->enviroment = decrypt($it['ENVIROMENT']);
        $account->id = $it['ID'];

        return $account;
    }

    static function create($category, $server, $enviroment, $url, $username, $password) {
        $category = encrypt($category);
        $server = encrypt($server);
        $enviroment = encrypt($enviroment);
        $url = encrypt($url);
        $username = encrypt($username);
        $password = encrypt($password);

        $data = array(Account::getNextAccountId(), $category, $server, $enviroment, $url, $username, $password);

        Database::execute("INSERT INTO Data VALUES (?, ?, ?, ?, ?, ?, ?)", $data);
    }
    
    function getAllAccounts() {	
        $query = "SELECT * FROM Data";
        $results = Database::getResults($query);
        $accounts = array();
	    
        foreach ($results as $it) {
        	$x = Account::loadRow($it);        	
        	$accounts[] = $x;
        }
        
        return $accounts;
    }
	
    function get($id) {
        $query = "SELECT * FROM Data WHERE ID = ?";

        $data = array($id);

        $results = Database::getResults($query, $data);

        foreach ($results as $it) {
                return Account::loadRow($it);
        }
    }

    function getAccounts($category = "", $enviroment = null) {
        $category = encrypt($category);
        $enviroment = encrypt($enviroment);

        $query = "SELECT * FROM Data WHERE Category = ? AND Enviroment = ?";
        $data = array($category, $enviroment);
        $results = Database::getResults($query, $data);

        $accounts = array();

        foreach ($results as $it) {
            $x = Account::loadRow($it);
            $accounts[] = $x;
        }

        return $accounts;
    }

    static function getEnviroments($category) {
        $category = encrypt($category);
        $query = "SELECT DISTINCT Enviroment FROM Data WHERE Category = ?";
        $data = array($category);

        return Database::getResults($query, $data);
    }

    function save() {
        $category = encrypt($this->category);
        $server = encrypt($this->server);
        $enviroment = encrypt($this->enviroment);
        $url = encrypt($this->url);
        $username = encrypt($this->username);
        $password = encrypt($this->password);
        $data = array($server, $enviroment, $url, $username, $password, $this->id);
        $query = "UPDATE Data SET SERVER = ?, Enviroment = ?, Url = ?, Username = ?, Password = ? WHERE ID = ?";

        Database::execute($query, $data);
    }

    function delete($id) {
        $sth = $GLOBALS['db']->prepare("DELETE FROM Data WHERE ID = ?");
        $data = array($id);
        $result = $GLOBALS['db']->execute($sth, $data);
        
        if (PEAR::isError($result)) {
            die($result->getMessage() . ', ' . $result->getDebugInfo());
        }
    }

    private static function getNextAccountId() {
        $query = "SELECT MAX(ID) From Data";
        $result = $GLOBALS['db']->getOne($query);

        if (PEAR::isError($result)) {
            die($result->getMessage());
        }

        return $result + 1;
    }
}
?>
