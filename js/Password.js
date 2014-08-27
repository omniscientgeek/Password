$(document).ready(function() {
	$id = $.jqURL.get("id");
        EditAccount($id)
	//$("#result").load("Content.php?search=" + $searchString);
});

function AddAccount() {
    $selectedTab = $(".ui-tabs-selected a").attr('href');

    $data = {
        op: "add",
        category: $.jqURL.get('category'),
        server: $("#addDialog .server").val(),
        enviroment: $(".ui-tabs-selected a").html(),
        url: $("#addDialog .url").val(),
        username: $("#addDialog .username").val(),
        password: $("#addDialog .password").val()
    };

    CallManageAccounts($data);
}
 
function ShowNewAccount($enviroment) {
	$selected = $("#tabs").tabs( "option", "selected" );
	
    $("#addDialog .server").val("");
    $("#addDialog .enviroment").val($enviroment);
    $("#addDialog .url").val("");
    $("#addDialog .username").val("");
    $("#addDialog .password").val("");
 
    $("#addDialog").dialog('open');
}

function UpdateAccount($i) {
    $data = {
        op: "update",
        id: $i,
        category: $.jqURL.get('category'),
        server: $("#editDialog .server").val(),
        enviroment: $("#editDialog .enviroment").val(),
        url: $("#editDialog .url").val(),
        username: $("#editDialog .username").val(),
        password: $("#editDialog .password").val()
    };

    CallManageAccounts($data);
}

function Delete($id) {
    $data = {
        op: "delete",
        id: $id
    };

    CallManageAccounts($data);
}

function ManageAccount($data) {
    $.ajax({
        type: "POST",
        url: "/password/webservice/ManageAccounts.php",
        dataType: "xml",
        data: $data,
        success: function() {
            location.reload();
        },
        error: function($xml, $textStatus) {
        	alert("Server error: $textStatus");
        }
    });
}

function AddField(form, document, id, value) {
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", id);
    hiddenField.setAttribute("value", value);

    form.appendChild(hiddenField);
}

function AddField(form, document, id, value) {
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", id);
    hiddenField.setAttribute("value", value);

    form.appendChild(hiddenField);
}

function AddSubmitField(form, document, id, value) {
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "submit");
    hiddenField.setAttribute("value", value);
    hiddenField.setAttribute("name", id);

    form.appendChild(hiddenField);
}

function EditAccount($id) {
    $row = $("#Account" + $id);

    $("#editDialog .server").val($row.find(".server div").html());
    $("#editDialog .enviroment").val($(".ui-tabs-selected a").html());
    $("#editDialog .url").val($row.find(".url a").attr("href"));
    $("#editDialog .username").val($row.find(".username div").html());
    $("#editDialog .password").val($row.find(".password div").html());

    $("#editDialog .AccountId").val($id);
    $("#editDialog").dialog('open');
}

function MoveAccountDialog($id) {
    $id = $("#editDialog .AccountId").val();
    $("#editDialog").dialog('close');
    
    $(".AccountId").val($id);
    $("#dialog").dialog('open');
}

function MoveAccount() {
    $id = $(".AccountId").val();
    $category = $("#dialog .CategoryList").val();
    
    ManageAccount({op: "move", id: $id, category: $category});
}

function CancelAccountEdit($i) {
    $row = $("#" + $i);
    $row.find(".update").hide();
    $row.find(".select").show();
    $row.find("#update").hide();
    //Hide all the edit buttons
    $("div.edit").show();
    $("#newRow").show();
}

function DeleteAccount($i) {
    $row = $("#" + $i);
    
    if(!confirm("Warning the record will be deleted!")){
        return;
    }

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "#");

    AddField(form, document, "Delete", "Delete");
    AddField(form, document, "id", $i);

    document.body.appendChild(form);
    form.submit();
}

function ChangeCategory() {
    $select = $(".CategoryList");

    var form = document.createElement("form");
    form.setAttribute("method", "get");

    AddField(form, document, "category", $select.val());

    document.body.appendChild(form);
    form.submit();
}