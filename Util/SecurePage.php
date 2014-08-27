<?php
include_once '../BL/Session.php';

if(Session::IsLoggedIn() == false) {
    header('Location: index.php');
}
?>