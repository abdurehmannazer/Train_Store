<?php
session_start();

if (isset($_SESSION['admin_session'])) {
    $title = 'Comments'; //for title
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';





    //general page in members Manage
    if ($do == 'manage') {
        $h1 = "Manage Comments";
        // if come from pending click
        $query = '';
        if (isset($_GET["page"]) && $_GET["page"] == "pending") {
            $query = 'WHERE Regstatus  = 0';
            $h1 = "Pending Members";
        }
        $stmt = $con->prepare("SELECT * FROM comments  $query");
        $stmt->execute();
        $comments = $stmt->fetchAll();
        ?>
    <!-- Create Table  -->
    <h1 class="text-center"><?php print $h1 ?></h1>
    <div class="container">
    <?php 
    if ($comments == array()) { //check if there data pending coms
            MSG("No Commets", "danger", "dashBoard.php", 3);
        } else { ?>       
    <div class="table-responsive ">
        <table class="table table-sm table-light table-hover table-bordered border-secondary-subtle text-center m-0">
            <thead class='table-secondary'>
                <tr>
                <th>#ID</th>
                <th>comment</th>
                <th>Date</th>
                <th>Item</th>
                <th>User</th>
                <th>Control</th>
                </tr>
            </thead>
            <tbody class=''>
            <?php  foreach($comments as $comm) {?>
            <tr>
                    <th><?php print $comm["comm_id"]; ?></th>
                    <td><?php print $comm["comment"];?></td>
                    <td><?php print(getName('item_name', 'items', "id" , $comm["item_id"] ));?></td>
                    <td> <?php print(getName('UserName', 'users', "UserID" , $comm["user_id"] ));?> </td>
                    <td><?php print $comm["comm_date"];?></td>
                    <td style="text-align: start;">
                        <a href="comments.php?do=edit&id=<?php print $comm['comm_id']?>" class="btn  btn-sm  rounded-circle text-success " title='Edit Information'><i class="fa-solid fa-user-pen "></i></a>
                        <a href="comments.php?do=delete&id=<?php print $comm['comm_id']?>" class="btn   btn-sm rounded-circle confirm text-danger " title='Delete Member'><i class="fa-solid fa-trash "></i></a>        
                        <?php if ($comm["status"] == 0) { ?>
                            <a href="comments.php?do=active&id=<?php print $comm['comm_id']?>" class="btn   btn-sm rounded-circle text-primary" title='Active Member'><i class="fa-solid fa-check-double"></i></a>        
                        <?php } ?>
                    </td>
            </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    </div>


















<?php

    //edit page
    } elseif ($do == 'edit') {
        print "<div class='container'>";
        $comm_id =isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']): 0;
        //get info from database
        $stmt = $con->prepare('SELECT  *  FROM comments WHERE comm_id = ? ');
        $stmt->execute([$comm_id]);
        $comments = $stmt->fetch();
        $count = $stmt->rowCount();
        //if there  results from database
        if ($count > 0) { ?>   
            <h1 class="text-center">Edit Comment</h1>
                <div class="container">
                <form action="?do=update" method="POST" class="form-horizontal" >
                    <input type="hidden" name="comm_id" value="<?php print $comments['comm_id']; ?>" id="">
                    <!-- comment  -->
                    <div class="form-group form-control-lg row pb-2">
                        <label for="" class="col-sm-2  control-label fw-bold">Comment</label>
                        <div class="col-sm-10 col-md-4">
                            <!-- <textarea type="text" name="comment" class="form-control" required  value=""> -->
                            <textarea name="comment" id="" cols="30" rows="10" class="form-control" required><?php print $comments['comment']; ?></textarea>
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
            MSG("Wrong Access","danger" ,"comments.php" );
        }
        print "</div>";


        
    }  elseif ($do == 'update') {
        print "<div class='container'>";
        print "<h1 class='text-center '>Comment Update</h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comm_id = $_POST['comm_id'];
            $comment = $_POST['comment'];
            if (empty($comment)) {
                print '<div class="alert alert-danger" role="alert">Comment Name cant be Empty</div>';
            } else {
                $stmt = $con->prepare('UPDATE `comments` SET comment = ?  WHERE `comments`.`comm_id` = ?   ;');
                $stmt->execute([$comment , $comm_id]);
                MSG($stmt->rowCount() . ' Edit is done ', "success", "comments.php", 3);
            }            
        } else {// if servser request is wronge //message for wronge access
            MSG("No Access page" , "danger" , "comments.php", 6);
        };
        print '</div>';





    }  elseif ($do == "delete"){
        print "<div class='container'>";
        print "<h1 class='text-center '>Delete Member</h1>";
        $comm_id = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']): 0;
        $stmt = $con->prepare(' DELETE FROM comments WHERE `comments`.`comm_id` = ?');
        $stmt->execute([$comm_id]);
        $count = $stmt->rowCount();
        if ($count > 0) {MSG($stmt->rowCount() . ' Delete is Done' , "success", "comments.php", 4);
        } else{MSG("No ID " , "danger" , "comments.php", 3);}
        print "</div>";
    } //close delete
    elseif ($do == "active") {
        print "<h1 class='text-center '>Active Comment</h1>";
        print "<div class='container'>";
        $comm_id = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;        
        if (checkDatabase('comm_id', 'comments', $comm_id) > 0) {
            $stmt = $con->prepare('UPDATE `comments` SET `status` = ? WHERE `comments`.`comm_id` = ?   ;');
            $stmt->execute([ 1 , $comm_id]);
            MSG($stmt->rowCount() . ' Active is Done' , "success", "comments.php", 4);
        }else {
            MSG("No ID " , "danger" , "comments.php", 4);
        }
        print '</div>';



    } else { //not found page go back to manage members
        header('Location: members.php');
    } //close not found page
    include 'includes/templets/footer.php';
//if no sing in
} else {
    // SESEEION
    header('Location: index.php');
    exit();
} // close if SESSION
