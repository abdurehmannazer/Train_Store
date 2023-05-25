<?php

$session_user = "";
if (isset($_SESSION['general_user_session'])) {
    $session_user = $_SESSION['general_user_session'];
}

include 'admin/con_data.php';
include 'includes/languages/english.php';
include 'includes/functions/func.php';

include 'includes/templets/header.php';



?>
