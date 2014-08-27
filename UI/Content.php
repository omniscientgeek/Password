<?php
include_once '../Util/SecurePage.php';
include_once '../BL/Database.php';
include_once '../BL/Account.php';
include_once '../BL/Category.php';
include_once '../Util/SearchUtil.php';
include_once '../Util/SecurePage.php';
include_once '../BL/Database.php';
include_once '../BL/Account.php';
include_once '../BL/Category.php';
include_once '../Util/SearchUtil.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Password Storage</title>
    </head>
    <body>
<?php

function outputColumn($name, $text, $inputType = "Default") {
    echo "\n<td class='$name'><div class='select'>$text</div></td>";
}

function outputHiddenColumn($name, $text, $inputType = "Default") {
    echo "\n<td class='$name' style='display:none'><div class='select' style='display:none'>$text</div></td>";
}
?>
        <div>
<input id="enviroment" type="hidden" value="<?php echo($_GET["enviroment"]) ?>" />
</div>
<table cellpadding="3" cellspacing="0">
    <tr class="ui-dialog-titlebar ui-widget-header ui-corner-all" id="head">
            <td class="server">Server</td>
            <td class="url">Url</td>
            <td class="username">Username</td>
            <td class="edit"></td>
        </tr>
    <?php
    	if(isset($_GET["search"])) {
    		$result = Search($_GET["search"]);
    		$editValue = "View";
    		$editOnClick = "ViewAccount";
    	}
    	else
    	{
    		$result = Account::getAccounts($_GET["category"], $_GET["enviroment"]);
    		$editValue = "Edit";
    		$editOnClick = "EditAccount";
    	}

        if(isset($_REQUEST['id']))
        {
            $id = $_REQUEST['id'];
        }
        else
        {
            $id = "";
        }

        foreach ($result as $account) {
            if($id == $account->id)
            {
                echo "\n<tr class='selected' id='Account$account->id'>";
            }
            else
            {
                echo "\n<tr id='Account$account->id'>";
            }
            
            outputColumn("server", $account->server);
            outputColumn("url", "<a href=\"$account->url\">$account->url</a>  ");
            outputColumn("username", $account->username);
            outputHiddenColumn("password", $account->password);
            echo "
                <td>
                    <div class='edit button'>
                        <input name='Edit' type='button' class='edit ui-state-default ui-corner-all' value='$editValue' onclick='$editOnClick($account->id)' />
                    </div>
                </td>
            </tr>";
        }
    ?>
        <tr id="newRow">
            <td>
                <!-- <input id="Server" /> -->
            </td>
            <td>
                <!-- <input id="Url" /> -->
            </td>
            <td>
                <!--<input id="UserName"/>-->
            </td>
            <td>
                <input name="Add" type="submit" class="ui-state-default ui-corner-all" onclick="ShowNewAccount('<?php echo($_GET["enviroment"]); ?>')" value="Add" />
            </td>
        </tr>
</table>
    </body>
</html>
