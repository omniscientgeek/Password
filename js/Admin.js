$(document).ready(function() {
    $("#accessList").dialog(
        {autoOpen: false,
         modal: true,
         width: 400,
         buttons: {
             "Update": function() {
                UpdateCategoryAccessList();
                },
             "Cancel": function() {;}
             }
    });
    
    $("#editUser").dialog(
        {autoOpen: false,
         modal: true,
         width: 400,
         buttons: {
             "Update": function() { 
                 UpdateUser();
             },
             "Cancel": function() {;}
             }
    });
});

function ResetPassword() {
    $userId = $("#editUser").find("#userId").val();
    $passwordOrignal = $("#passwordOringal").val();
    $duplicatePassword = $("#passwordDuplicate").val();

    if($passwordOrignal != $duplicatePassword)
    {
        window.alert("Passwords do not match");
        return;
    }

    if($passwordOrignal.length == 0)
    {
        window.alert("Password is blank");
        return;
    }


    $data = {
        op: "resetPassword",
        userId: $userId,
        password: $passwordOrignal
    }

    CallManageAccounts($data);
}

function EditUser($userId) {
    $data = {
            id: $userId,
            op: "userAccess"
        };

    $("#editUser #userId").val($userId);

    $.ajax({
        type: "POST",
        url: "/password/webservice/AccountInfo.php",
        dataType: "xml",
        data: $data,
        success: function($xml) {UpdateEditUser($xml);},
        error: function($xml, $textStatus) {
        	alert("Server error: $textStatus");
        }
    });
}

function UpdateEditUser($xml) {
    $($xml).find('User').each(function(){
        $id = $(this).attr('id');
        $selected = $(this).attr('selected');

        if($selected == "true")
            $selected = true;
        else
            $selected = false;

        $checkBox = $("#editUser").find("input#" + $id);
        $checkBox.attr('checked', $selected);
    });

    $("#editUser").dialog('open');
}

function UpdateUser() {
    $userId = $("#editUser").find("#userId").val();

    $data = {
        op: "updateUser",
        userId: $userId
    }

    $("#editUser").find("li input").each(function() {
        $checked = $(this).is(':checked');
        $id = $(this).attr('id');
        $chcked = $checked;

        $data[$id] = $checked;
    });

    CallManageAccounts($data);
}

function UpdateAccessList($categoryId) {
    $data = {
            id: $categoryId,
            op: "categoryAccess"
        };
	
    $("#accessList #categoryId").val($categoryId);
    
    $.ajax({
        type: "POST",
        url: "/password/webservice/AccountInfo.php",
        dataType: "xml",
        data: $data,
        success: function($xml) {UpdatePopupAccessList($xml);},
        error: function($xml, $textStatus) {
        	alert("Server error: $textStatus");
        }
    });
}

function UpdatePopupAccessList($xml) {
	$($xml).find('User').each(function(){
		$id = $(this).attr('id');
		$selected = $(this).attr('selected');
		
		if($selected == "true")
			$selected = true;
		else
			$selected = false;
                    
		$checkBox = $("#accessList").find("input#" + $id);
		$checkBox.attr('checked', $selected);
	});
	
    $("#accessList").dialog('open');
}

function UpdateCategoryAccessList() {
    $categoryId = $("#accessList #categoryId").val();
    
    $data = {
            op: "updateAccessList",
            category: $categoryId
    }
    
	$("#accessList li input").each(function() {
		$checked = $(this).is(':checked');
		$id = $(this).attr('id');		
		$chcked = $checked;

	    $data[$id] = $checked;
	});
    
    CallManageAccounts($data);
}

function EditAccessList($i) {
	UpdateAccessList($i);
}