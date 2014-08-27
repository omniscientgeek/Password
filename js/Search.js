$(document).ready(function() {
	$searchString = $.jqURL.get("search");
	$("#result").load("Content.php?search=" + $searchString);
});

function ViewAccount($id) {

    $data = {
            id: $id,
            op: "accountLocation"
        };
    
    $.ajax({
        type: "POST",
        url: "/password/webservice/AccountInfo.php",
        dataType: "xml",
        data: $data,
        success: function($xml) { ShowAccount($xml, $id); },
        error: function($xml, $textStatus) {
        	alert("Server error: $textStatus");
        }
    });
}

function ShowAccount($xml, $id) {
	$($xml).find('Result').each(function(){
		var $category = $(this).attr('category');
		var $enviroment = $(this).attr('enviroment');

		
		window.location.href = "Accounts.php?category=" + $category + "&enviroment=" + $enviroment + "&id=" + $id;
	});
}