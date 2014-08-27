function CallManageAccounts($data) {
    $.ajax({
        type: "GET",
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