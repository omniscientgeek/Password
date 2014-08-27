<?php
include_once '../Util/SecurePage.php';
include_once '../BL/Account.php';
include_once '../BL/Category.php';

    if(isset($_GET["view"])) {
        header('Content-type: text/plain');
        header('Content-Disposition: inline; filename=Export.csv');
    }
    else
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=Export.csv');
    }

    echo "Category, Server, Enviroment, Url, Username, Password\r\n";

    foreach(Account::getAllAccounts() as $it) {
        $categoryId = $it->category;
        echo Category::getCategoryName($categoryId) . "," . $it->server . "," . $it->enviroment . "," . $it->url . "," . $it->username . "," . $it->password . "\r\n";
    }
    /*
     *
    [category] => 7
    [id] => 116
    [server] => Pingdom
    [enviroment] => Passwords
    [url] => https://pp.pingdom.com/index.php/login
    [username] => justin@omniscientgeek.com
    [password] => YjIwYjhiNTc2
     */
    ?>