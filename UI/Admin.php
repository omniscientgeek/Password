<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Password Admin</title>
        <link type="text/css" href="css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/admin.css" rel="stylesheet" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.query-2.1.7.js"></script>
        <script type="text/javascript" src="js/Util.js"></script>
        <script type="text/javascript" src="js/Admin.js"></script>
    </head>
    <body>
        <?php
include_once '../Util/SecurePage.php';
include_once '../BL/Database.php';
include_once '../BL/Account.php';
include_once '../BL/Category.php';
include_once '../BL/User.php';

if(isset($_REQUEST["Add"]))
{
    $category = $_POST["category"];

    Category::create($category);
}

if(isset($_REQUEST["AddUser"]))
{
    new User($_REQUEST['username'], $_REQUEST['password']);
}
        ?>

        <div class="Categories">
            <b>Categories</b>
            <?php
                $result = Category::getCategories();
                foreach ($result as $it) {
                    ?><div><?php echo($it->name) ?>&nbsp;<input type="Button" value="Access List" onclick="EditAccessList(<?php echo($it->id) ?>)" /></div><?php
                }
                ?>
        </div>
        <form action="#" method="post">
            <input name="category" type="text" /><input name="Add" value="Add" type="submit">
        </form>
        <div id="users">
            <?php
                $result = User::getUsers();
                foreach ($result as $it) {
                    ?><div><?php echo($it->name) ?><input id="a<?php echo($it->id) ?>" type="Button" value="Edit" onclick="EditUser(<?php echo($it->id) ?>)"/></div><?php
                }
                ?>
        </div>
        <form action="#" method="post">
            Username: <input name="username" type="text" /><br />
            Password: <input name="password" type="text" /><br />
            <input name="AddUser" value="Add User" type="submit">
        </form>
        <div id="editUser" title="Edit user">
            <input id="userId" type="hidden" />
            <div class="category">
                <?php
                    $result = Category::getCategories();
                    foreach ($result as $it) {
                        ?><li><?php echo($it->name) ?>&nbsp;<input id="<?php echo($it->id) ?>" type="checkbox" value="Access List" /></li><?php
                    }
                    ?>
            </div>
            <div class="passwordReset">
                <div class="Text">Password</div>
                <div class="Field">
                    <input id="passwordOringal" type="password" /><br />
                    <input id="passwordDuplicate" type="password" />
                    <input id="inputSubmit" class="ui-state-default ui-corner-all" type="button" value="Reset" onclick="ResetPassword()"/>
                </div>
            </div>
        </div>
        <div id="accessList" title="Category Access List">
        	<input id="categoryId" type="hidden" />
			Users
	        <ol id="Users">
            <?php
                $result = User::getUsers();
                foreach ($result as $it) {
                    ?><li><input id="<?php echo($it->id) ?>" type="checkbox" /><?php echo($it->name) ?></li><?php
                }
                ?>
			</ol>
        </div>
    </body>
</html>
