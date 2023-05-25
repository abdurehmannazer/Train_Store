<?php
session_start();
// $noNav = '';
$title = 'Admin Login';

if (isset($_SESSION['admin_session'])) {
    header('Location: dashBoard.php');
}
include 'init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = $_POST['user'];
    $password = $_POST['password'];
    $hashpass = sha1($password);
    try {
        $stmt = $con->prepare('SELECT  UserID ,UserName, passNum  FROM users WHERE UserName = ? AND passNum = ? AND GroupID = 1 ' );
        $stmt->execute([$userName, $hashpass]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $_SESSION['admin_session'] = $userName;
            $_SESSION['admin_id_session'] = $row['UserID'];
            header('Location: dashBoard.php ');
            exit();
        } else {
            print 'username or password is wrong';
        }
    } catch (PDOException $e) {
        print 'ERROR' . $e;
    }
}
?>

<div class="container">
    <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST" class='login'>
        <h4>Admin Login</h4>
        <input class='form-control' type="text" name="user" placeholder="Username" autocomplete="off">
        <input class='form-control' type="password" name="password" placeholder="Password" autocomplete="new-password">
        <input class='btn btn-primary btn-block' type="submit" value="Login">
    </form>  
</div>











<?php include 'includes/templets/footer.php'; ?>
