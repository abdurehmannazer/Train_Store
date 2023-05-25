<?php
ob_start();
session_start();
$title = '';

if (isset($_SESSION['userSession'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';



    if ($do == 'manage') {

    } elseif ($do == 'add') {

    } elseif ($do == 'insert') {

    } elseif ($do == 'edit') {

    } elseif ($do == 'update') {

    } elseif ($do == "delete"){

    } elseif ($do == "active") {

    } else { 
        header('Location: members.php');
        exit();
    }



include 'includes/templets/footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
