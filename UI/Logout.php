<?php
include_once '../BL/Session.php';

Session::LogOut();

header('Location: index.php');
?>