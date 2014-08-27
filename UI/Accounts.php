<?php
include_once '../Util/SecurePage.php';
include_once '../BL/Database.php';
include_once '../BL/Account.php';
include_once '../BL/Category.php';
include_once "../BL/User.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/Accounts.css" />
        <title>Password Storage</title>
        <link type="text/css" href="css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <script src="//www.google.com/jsapi?key=ABQIAAAAdJ8GTH40gCpWOzTY3rLXmBRkOs-kMLfmQl995Rnu7Lo5cVwhERSG3wCSSTy1DlpA1b-S-WCMUXra0A" type="text/javascript"></script>
        <script type="text/javascript">
            google.load("jquery", "1.4.2");
            google.load("jqueryui", "1.8.2");
        </script>
        <script type="text/javascript" src="js/jquery.jqURL.js"></script>
        <script type="text/javascript" src="js/Util.js"></script>
        <script type="text/javascript" src="js/Password.js"></script>
    </head>
    <body>
<?php
function outputColumn($name, $text, $inputType = "Default") {
    echo "<td><div id='$name' class='select'>$text</div></td>";
}
?>
        <div id="container">
            <div id="top">
                <div class="userName"><div class="Text">User: <?php echo($_SESSION['username'])?></div>
                    <div class="form"><form action="Logout.php"><div><input type="submit" value="Logout" /></div></form></div></div>
                <div class="right">
                <div id="categories">Categories: <select class='CategoryList' onchange='ChangeCategory()'>
<?php      foreach(User::getAuthorizedCategories() as $it) {
            $name = $it->name;
            $id = $it->id;

            if($_GET["category"] == $id) {
                $categories = $name;
                echo "<option selected='selected' value='$id'>$name</option>";
            }
            else {
                echo "<option value='$id'>$name</option>";
            }
        }?></select>
                </div>
                <div id="searchForm"><form action="Search.php"><div><input type="text" name="search" /><input type="submit" value="Search" /></div></form></div>
                </div>
            </div>
            <div id="tabs">
                    <ul>
<?php
$category = $_GET['category'];
$result = Account::getEnviroments($category);

if(isset($_GET['enviroment']))
{
	$selectedEnviroment = $_GET['enviroment'];
}
else
{
	$selectedEnviroment = null;
}

$selectedTab = 0;
$i = 0;

foreach ($result as $it) {
    $enviroment = $it['Enviroment'];

    if(isset($_REQUEST['id']))
    {
        $id = "&id=" + $_REQUEST['id'];
    }
    else
    {
        $id = "";
    }
    
    $enviroment = decrypt($enviroment);

    echo "<li><a href='Content.php?category=$category&amp;enviroment=$enviroment&amp;id=$id' >$enviroment</a></li>";
    
    if($selectedEnviroment == $enviroment) {
    	$selectedTab = $i;
    }
    
    $i++;
 } ?>
                    </ul>
                    <input id="SelectedTab" type="hidden" value="<?php echo $selectedTab; ?>" />
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                $selectedTab = $("#SelectedTab").val();
				$("#tabs").tabs({ selected: $selectedTab });
                $("#dialog").dialog({autoOpen: false, modal: true});
                $("#editDialog").dialog(
                    {autoOpen: false,
                     modal: true,
                     width: 400,
                     buttons: {
                         "Delete": function() {Delete($("#editDialog .AccountId").val());},
                         "Move": function() { MoveAccountDialog(); },
                         "Update": function() {UpdateAccount($("#editDialog .AccountId").val());}}
                 });
	        	$("#addDialog").dialog(
					{autoOpen: false,
						modal: true,
						width: 400,
						buttons: {
							"Cancel": function() { 
								$(this).dialog('close'); },
							"Add": function() { AddAccount();}}
					});
        	});
        </script>
        <div id="dialog" title="Move Account Info">
            <input type="hidden" class="AccountId" value="Test" />
            What is the new category? 
                <select class='CategoryList' onchange='MoveAccount()'>
<?php      foreach(User::getAuthorizedCategories() as $it) {
            $name = $it->name;
            $id = $it->id;

            if($_GET["category"] == $id) {
                $categories = $name;
                echo "<option selected='selected' value='$id'>$name</option>";
            }
            else {
                echo "<option value='$id'>$name</option>";
            }
        }?></select>
        </div>
        <div id="editDialog" class="userDialog" title="Account Info">
            <input type="hidden" class="AccountId" value="Test" />
            <table>
                <tr>
                    <td><p>Server:</p></td>
                    <td><input class="server" type="text"/></td>
                </tr>
                <tr>
                    <td>Enviroment: </td>
                    <td><input class="enviroment" type="text" /></td>
                </tr>
                <tr>
                    <td>Url: </td>
                    <td><input class="url" type="text" /></td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td><input class="username" type="text" /></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input class="password" type="text" /></td>
                </tr>
            </table>
        </div>
        <div id="addDialog" class="userDialog" title="New Account Info">
            <table>
                <tr>
                    <td><p>Server:</p></td>
                    <td><input class="server" type="text"/></td>
                </tr>
                <tr>
                    <td>Enviroment: </td>
                    <td><input class="enviroment" type="text" /></td>
                </tr>
                <tr>
                    <td>Url: </td>
                    <td><input class="url" type="text" /></td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td><input class="username" type="text" /></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input class="password" type="text" /></td>
                </tr>
            </table>
        </div>
    </body>
</html>
