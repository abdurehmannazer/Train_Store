<?php
session_start();

if (isset($_SESSION['admin_session'])) {
    $title = 'Members'; //for title
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';





    //general page in members Manage
    if ($do == 'manage') {
        $h1 = "Manage Members";
        $query = '';
        if (isset($_GET["page"]) && $_GET["page"] == "pending") {
            $query = 'WHERE Regstatus  = 0';
            $h1 = "Pending Members";
        }
        $stmt = $con->prepare("SELECT * FROM users  $query  ORDER BY UserID DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <!-- Create Table  -->
        <h1 class="text-center"><?php print $h1 ?></h1>
        <div class="container">
        <?php 
        if ($rows == array()) { //check if there data pending coms
                MSG("No Member", "danger", "members.php?do=add", 3);
            } else { ?>       
        <div class="table-responsive ">
            <table class="table table-sm table-light table-hover table-bordered border-secondary-subtle text-center m-0">
                <thead class='table-secondary'>
                    <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Avatar</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col" style='min-width: 90px;'>Full Name</th>
                    <th scope="col" style='min-width: 180px;'>Register Date</th>
                    <th scope="col" style='min-width: 90px;'>Control</th>
                    </tr>
                </thead>
                <!-- loop data in Table  -->
                <tbody class=''>
                <?php  foreach($rows as $row) {?>
                <tr>
                        <th><?php print $row["UserID"]; ?></th>
                        <td>
                            <?php
                            $img = "";
                            $row["user_image"] == "" ? $img = '../media/preson.png' : $img ="../" . $row["user_image"];
                            ?>
                            <?php print "<img src='$img' style='width:50px; height: 50px'>" ?>
                        </td>
                        <td><?php print $row["UserName"]; print $row["GroupID"] == 1 ? "<b class='text-dark' title='Admin'>   <i class='fa-solid fa-user-tie float-end'></i> </b>" : ""; ?></td>
                        <td><?php print $row["Email"];?></td>
                        <td><?php print $row["FullName"];?></td>
                        <td> <?php print $row["date"];?> </td>
                        <td style="text-align: start;">
                            <a href="members.php?do=edit&id=<?php print $row['UserID']?>" class="btn  btn-sm  rounded-circle text-success " title='Edit Information'><i class="fa-solid fa-user-pen "></i></a>
                            <a href="members.php?do=delete&id=<?php print $row['UserID']?>" class="btn   btn-sm rounded-circle confirm text-danger " title='Delete Member'><i class="fa-solid fa-trash "></i></a>        
                            <?php if ($row["RegStatus"] == 0) { ?>
                                <a href="members.php?do=active&id=<?php print $row['UserID']?>" class="btn   btn-sm rounded-circle text-primary" title='Active Member'><i class="fa-solid fa-check-double"></i></a>        
                            <?php } ?>
                        </td>
                </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <?php } ?>
        <a href='members.php?do=add' class='btn btn-primary mt-3'> <i class="fa-solid fa-plus"></i> Add New Member </a>
        </div>



<?php
    // add page
        } elseif ($do == 'add') { ?>
        <h1 class="text-center">Add New Member</h1>
                <div class="container">
                    <form action="?do=insert" method="POST" class="form-horizontal" >
                        <!-- user  -->
                        <div class="form-group form-control-lg row pb-2">
                            <label for="" class="col-sm-2  control-label fw-bold">Username</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <!-- pass  -->
                        <div class="form-group form-control-lg row pb-2 ">
                            <label for="" class="col-sm-2  control-label fw-bold">Password</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="password" name="password"  class=" password form-control" required>
                                <i class="fa-solid fa-eye"></i>
                            </div>
                        </div>
                        <!-- email  -->
                        <div class="form-group form-control-lg row pb-2 ">
                            <label for="" class="col-sm-2  control-label fw-bold">Email</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="email" name="email" class="form-control"  required >
                            </div>
                        </div>
                        <!-- fullname  -->
                        <div class="form-group form-control-lg  row pb-2 ">
                            <label for="" class="col-sm-2  control-label fw-bold">Full Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="full" class="form-control"  required >
                            </div>
                        </div>
                        <!-- Type Memer   -->
                        <div class="form-group form-control-lg  row pb-2 ">
                            <label for="" class="col-sm-2  control-label fw-bold">Member Type</label>
                            <div class="col-sm-10 col-md-4 bg-light rounded">
                                <input type="radio" id="html" name="typeMember" value="1" required >
                                <label for="html" >Admin</label><br>
                                <input type="radio" id="css" name="typeMember" value="0" required checked>
                                <label for="css">Users</label><br>
                            </div>
                        </div>
                        <!-- Submit  -->
                        <div class="form-group form-control-lg p-0">
                            <div class="d-grid gap-2 col-md-6">
                                <input type="submit" value="Add Member" name='save' class="btn btn-primary ">
                            </div>
                        </div>
                    </form>
            </div>        
<?php } //close add page





    //insert page
    elseif ($do == 'insert') {
        print "<div class='container'>";
            print "<h1 class='text-center '>Insert a New Member</h1>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //get data from request Post
                $username         = $_POST['username'];
                $hashPassword  = sha1($_POST['password']);
                $email                = $_POST['email'];
                $full                    = $_POST['full'];
                $typeMember    = $_POST['typeMember'];
                //validate inputs from server site
                $errorrs = [];
                if (empty($username) || strlen($username) > 3 || strlen($username) > 20) {$errorrs[] ='Username cant be Empty or less the 3 char or more then 20  char';}
                if (empty($email)) {$errorrs[]                = 'Email cant be Empty';}
                if (empty($full)) {$errorrs[]                    = 'Full Name cant be Empty';}
                if (empty($hashPassword)) {$errorrs[]  = 'Password cant be empty';}
                if ($typeMember == "") {$errorrs[]       = 'Type Member cant be empty';}
                foreach ($errorrs as $err) {print '<div class="alert alert-danger" role="alert">' .$err .'</div>';}
                // send edit to databse after check if there no errors
                if (empty($errorrs)) {
                    if (checkDatabase('UserName', 'users', "$username") >= 1) { // check if username is already insert or not !
                        MSG("oops ! this username is already inserted insert another one","danger" , "members.php?do=add",4); // if user name is already inserted
                    } else { //send data to database if not 
                        if (checkDatabase("Email" , "users" , $email) >= 1) {
                            MSG("oops ! this Email is already inserted insert another one","danger" , "members.php?do=add",4); // if user name is already inserted
                        }  else {
                            $stmt = $con->prepare(  "INSERT INTO `users` ( `UserName`, `passNum`,  `Email`, `FullName`, `GroupID`  , `RegStatus`, `date`)
                                VALUES (:username, :passNum,  :email, :full, :group , 0 , now())");
                            $stmt->execute([
                                'username' => $username,
                                'passNum' => $hashPassword,
                                'email'        => $email,
                                'full'            => $full,
                                'group'       => $typeMember,
                            ]);
                            MSG($stmt->rowCount() . ' Added New Member', "success" ,"members.php" );
                            }
                        }       
                }; //close error
        print "</div>";
        } else {MSG("No Access page" , "danger", "index.php" , 4.5);}//message for wronge access   if servser request is wronge
    }//close insert page


    //edit page
    elseif ($do == 'edit') {
        $userID =isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']): 0;
        //get info from database
        $stmt = $con->prepare('SELECT  *  FROM users WHERE UserID = ? ');
        $stmt->execute([$userID]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        //if there  results from database
        if ($count > 0) { ?>   
            <h1 class="text-center">Member Edit</h1>
                <div class="container">
                <form action="?do=update" method="POST" class="form-horizontal" >
                    <input type="hidden" name="UserID" value="<?php print $row['UserID']; ?>" id="">
                    <!-- user  -->
                    <div class="form-group form-control-lg row pb-2">
                        <label for="" class="col-sm-2  control-label fw-bold">Username</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" class="form-control" required  value="<?php print $row['UserName']; ?>">
                        </div>
                    </div>
                    <!-- pass  -->
                    <div class="form-group form-control-lg row pb-2 ">
                        <label for="" class="col-sm-2  control-label fw-bold">Password</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="hidden" name="oldPassword" value="<?php print $row['passNum']; ?>">
                            <input type="password" name="newPassword"  class="form-control">
                        </div>
                    </div>
                    <!-- email  -->
                    <div class="form-group form-control-lg row pb-2 ">
                        <label for="" class="col-sm-2  control-label fw-bold">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="email" name="email" class="form-control"  required value="<?php print $row['Email']; ?>">
                        </div>
                    </div>
                    <!-- fullname  -->
                    <div class="form-group form-control-lg  row pb-2 ">
                        <label for="" class="col-sm-2  control-label fw-bold">Full Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" class="form-control"  required value="<?php print $row['FullName']; ?>">
                        </div>
                    </div>
                    <!-- Type Memer   -->
                    <div class="form-group form-control-lg  row pb-2 ">
                        <label for="" class="col-sm-2  control-label fw-bold">Member Type</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="radio" id="html" name="typeMember" value="1" >
                            <label for="html">Admin</label><br>
                            <input type="radio" id="css" name="typeMember" value="0" checked>
                            <label for="css">Users</label><br>
                        </div>
                    </div>
                    <!-- Submit  -->
                    <div class="form-group form-control-lg p-0">
                        <div class="d-grid gap-2 col-md-6">
                            <input type="submit" value="Save" name='save' class="btn btn-primary ">
                        </div>
                    </div>
                </form>
            </div>        
            <?php }
        // if no results or wrong from database
        else {//if ($count > 0) if no results from database
            MSG("Wrong Access","danger" ,"members.php" );
        }
        print "</div>";
    } //close edit      
        
        
    //Update page
    elseif ($do == 'update') {
        // elseif ($do == 'edit'
        print "<div class='container'>";
            print "<h1 class='text-center '>Member Update</h1>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $UserID = $_POST['UserID'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $full = $_POST['full'];
                $typeMember = $_POST['typeMember'];
                $pass = empty($_POST['newPassword'])
                    ? $_POST['oldPassword']
                    : sha1($_POST['newPassword']);
                //validate inputs from server site
                $errorrs = [];
                if (empty($username) ||strlen($username) < 3 ||strlen($username) > 20) {$errorrs[] ='Username cant be Empty or less the 3 char or more then 20  char'; }
                if (empty($email)) {$errorrs[] = 'Email cant be Empty';}
                if (empty($full)) {$errorrs[] = 'Full Name cant be Empty';}
                foreach ($errorrs as $err) {print '<div class="alert alert-danger" role="alert">' .$err .'</div>';}
                // send edit to databse after check if there no errors
                if (empty($errorrs)) {
                    $stmt = $con->prepare(
                        'UPDATE `users` SET passNum = ? , `UserName` = ? , `Email` = ?, `FullName` = ? , `GroupID` = ? WHERE `users`.`UserID` = ?   ;'
                    );
                    $stmt->execute([$pass, $username, $email, $full , $typeMember, $UserID]);
                    MSG($stmt->rowCount() . ' Edit is done ', "success", "members.php", 3);
                }
            } else {// if servser request is wronge //message for wronge access
                MSG("No Access page" , "danger" , "members.php", 6);
            };
        print '</div>';
    } //close update



    // delete page 
    elseif ($do == "delete"){
        print "<div class='container'>";
            print "<h1 class='text-center '>Delete Member</h1>";
            // check if there ID in GET & is number
            $userID = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']): 0;
            // delete database
            $stmt = $con->prepare(' DELETE FROM users WHERE `users`.`UserID` = ?');
            $stmt->execute([$userID]);
            $count = $stmt->rowCount();
            if ($count > 0) {MSG($stmt->rowCount() . ' Delete is Done' , "success", "members.php", 4);
            } else{MSG("No ID " , "danger" , "members.php", 3);}
        print "</div>";
    } //close delete



    // active page
    elseif ($do == "active") {
        print "<div class='container'>";
            print "<h1 class='text-center '>Active Member</h1>";
            $id = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;        
            if (checkDatabase('UserID', 'users', $id) > 0) {
                $stmt = $con->prepare('UPDATE `users` SET RegStatus = ? WHERE `users`.`UserID` = ?   ;');
                $stmt->execute([ 1 , $id]);
                MSG($stmt->rowCount() . ' Active is Done' , "success", "index.php", 4);
            }else {
                MSG("No ID " , "danger" , "index.php", 4);
            }
        print '</div>';
    } // close active 





    else { //not found page go back to manage members
        header('Location: members.php');
    } //close not found page
include 'includes/templets/footer.php';
//if no sing in
} else {
    // SESEEION
    header('Location: index.php');
    exit();
} // close if SESSION
