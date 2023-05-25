<?php

// الاتصال الى قاعدة البيانات عن طريق
// PDO
// وهذه الطريقة افضل واشهر لأنها تدعم انواع كثيرة من قواعد البيانات
// وتدعم مميزات كثيرة منها اظهار الاخطار على شكل رسائل
$dsn = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass = '';
$option = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
];
try {
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    print '<script>console.log("Connected to Database is ok")</script>';
} catch (PDOException $e) {
    print '<script>console.log("NO Connected Database Something is Wrong")</script>';
}

// الاتصال الى قاعدة البيانات عن طريق
// Mysqli
// $con = mysqli_connect('localhost', 'root', '', 'shop');
// // Check connection
// if ($con) {
//     echo 'Connected successfully';
// } else {
//     print 'Database connection failed: ';
// }

?>
