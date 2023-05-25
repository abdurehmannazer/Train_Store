<?php
ob_start();
session_start();
$title = "Login / Signup";
include 'init.php'; 
if (isset($_SESSION['general_user_session'])) {
    header('Location: index.php');
} 
// Validate 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // Login form php
    if (isset($_POST['login'])) {
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];
        $hashed_pass = sha1($password);
        // database 
        $stm = $con->prepare("SELECT UserName , passNum FROM users WHERE UserName = ? AND passNum = ?");
        $stm->execute(array($user_name, $hashed_pass));
        $count = $stm->rowCount();
        $status_msg = "";
        if ($count > 0 ) {        
            $_SESSION['general_user_session'] = $user_name;
            header('Location: index.php');
            exit();
        } else {
            $status_msg =  "User Name or Password not Valid. ";
        }





    } else {  // Signup form php
        $error_arr = array();
        if (isset($_POST['user_name'])) {
            $user_name = $_POST['user_name'];
            $filter_user = filter_var($user_name, FILTER_SANITIZE_STRING);
            if (checkDatabase("UserName", "users", "$filter_user") > 0) {
                $error_arr[] = "This Name Already Exist";
            } else {strlen($filter_user) < 3  ? $error_arr[] = "Name Must Be Bigger Then 2 Character" : "";}
        }
        if (isset($_POST['password'])) {
            $password = $_POST["password"];
            $sha_pass = sha1($password);
            empty($password) ? $error_arr[] = "Password Cant Be Empty" : "";
            strlen($password) < 3 && !empty($password) ? $error_arr[] = "Password  Less Then 3 Character !" : "";
        }
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $filter_email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($filter_email , FILTER_VALIDATE_EMAIL)) {
                checkDatabase("Email", "users", "$filter_email") > 0 ? $error_arr[] = "This Email Already Exist" : "" ;
            } else {$error_arr[] = "Email Not Valid";}
        }
        $target_file = "uploads/$filter_user" . rand(0 , 1000) . ".png";
        if (!move_uploaded_file($_FILES['user_image']['tmp_name'], $target_file)) {
            $error_arr[] = "Sorry, there was an error uploading your file";
        }
        // After Validate 
        if (empty($error_arr)) {
            $_SESSION['general_user_session'] = $filter_user;
            $insert = $con->prepare("INSERT INTO users (UserName , passNum , Email , `date` , `user_image`)
                VALUE (:zUserName , :zpassNum , :zEmail , now() , :zuser_image )");
            $insert->execute([
                "zUserName" =>  $filter_user,
                "zpassNum"  => $sha_pass, 
                "zEmail" => $filter_email,
                "zuser_image" => $target_file
            ]);
            $count = $insert->rowCount();                 
            if ($count > 0 ){
                header("Location: profile.php?done=success");
                exit();
            }
        }
    }
}
?>




<!-- Forms  -->
<div class="container logPage">
    <h1 class='text-center'>
        <span class='Login <?php isset($_POST['signup']) ?  "" : print "active"; ?>' data-form="login_form">Login  </span> | 
        <span class='Signup <?php isset($_POST['signup']) ? print "active" : ""; ?>'  data-form="singup_form">Signup</span>
    </h1>
    <!-- Login HTML -->
    <form class="login_form" <?php isset($_POST['signup'])  ? print "style='display: none;'" : ''?> action="<?php print $_SERVER['PHP_SELF'];?>" method="POST">
        <small class='text-danger'><?php isset($status_msg) ? print $status_msg : "";?></small>
        <div class="mb-3">
            <label for="username" class="form-label"><i class="fa-solid fa-user fa-xs"></i> Username   </label>
            <input type="text" class="form-control" id="username" required name="user_name">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"> <i class="fa-solid fa-lock fa-xs"></i> Password</label>
            <input type="password" class="form-control" id="password" required name='password'>
        </div>
        <a href=""><i class="fa-solid fa-circle-info"></i> Forgit Password?</a>
        <button type="submit" name='login' class="btn btn-primary mt-3">Login</button>
    </form>
    <!-- Singup HTML -->
    <form class="singup_form" 
        <?php isset($_POST['signup'])  ? print "style='display: block;'" : ''?> 
        action="<?php print ($_SERVER['PHP_SELF']) ;?>"     
        method="POST"
        enctype="multipart/form-data"     >
        <div>
            <?php   
                    if (!empty($error_arr)) {
                        foreach($error_arr as $err) {
                            print "<small class='text-danger'> - $err</small> <br>";
                        } 
                    } ;
                    isset($count) && $count > 0 ? MSG("Rigester is Done !" , "success" , "profile.php" , 6) : ""
            ?>            
        </div>
        <div class="mb-3">
            <label for="username" class="form-label"><i class="fa-solid fa-user fa-xs"></i> Username  </label>
            <input type="text" class="form-control" id="username"  
                name="user_name"  
                value="<?php print $_POST["user_name"] ?? "" ;?>"
                pattern=".{3 , }" title='Username Must Be Bigger Then 3 Chars' 
                required
                >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"><i class="fa-solid fa-lock fa-xs"></i> Password</label>
            <input type="password" class="form-control" id="password" 
                name='password' 
                minlength="3"
                required
            >
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"> <i class="fa-solid fa-envelope fa-xs"></i> Email</label>
            <input type="email" class="form-control" id="email" 
                name="email" 
                value="<?php print $_POST["email"] ?? "" ;?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"> <i class="fa-sharp fa-solid fa-face-smile fa-sm"></i> Select Your image:</label>
            <input type="file" name="user_image">
        </div>
        <button type="submit" class="btn btn-success " name='signup'>Singup</button>
    </form>
</div>

<?php


?>


<?php include 'includes/templets/footer.php';

ob_end_flush();