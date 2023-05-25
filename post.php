<?php
ob_start();
session_start();
$title = 'Post';

if (isset($_SESSION['general_user_session'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';


    //manage 
    if ($do == 'manage') {
        print "Mange"; 



    } elseif ($do == 'insert') {
        print "<div class='container'>";
        print "<h1 class='text-center '>Insert Item</h1>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemName =  filter_var($_POST['item_name'] , FILTER_SANITIZE_STRING);
            $description = filter_var( $_POST['item_description'] , FILTER_SANITIZE_STRING);
            $price = filter_var( $_POST['item_Price'] , FILTER_SANITIZE_NUMBER_INT);
            $made_in =  filter_var($_POST['made_in'] , FILTER_SANITIZE_STRING);
            $status = filter_var( $_POST['item_status'] , FILTER_SANITIZE_NUMBER_INT);
            $cate =  filter_var($_POST['item_cate'] , FILTER_SANITIZE_NUMBER_INT);
            $member = filter_var( $_POST['member_id'] , FILTER_SANITIZE_NUMBER_INT);
            $errorrs = [];
            if (empty($itemName)) {$errorrs[] = 'Item Name cant be Empty';}
            if (empty($description)) {$errorrs[] = 'Description cant be Empty';}
            if (empty($price)) {$errorrs[] = 'Price cant be empty';}
            if (empty($made_in)) {$errorrs[] = 'Made in cant be empty';}
            if ($status == 0) {$errorrs[] = 'Status cant be empty';}
            if ($cate == 0) {$errorrs[] = 'cate cant be empty';}
            foreach ($errorrs as $err) {
                print '<div class="alert alert-danger" role="alert">' .$err .'</div>';
                header("refresh: 3; url=profile.php");            
            }
            if (empty($errorrs)) {
                if (checkDatabase('item_name ', 'items', "$itemName") >= 1) {
                    // check name
                    MSG('oops ! this Item Name is already inserted ,  insert another one','danger','profile.php'); // if user name is already inserted
                } else {
                    $stmt = $con->prepare(
                        "INSERT 
                        INTO `items` ( `item_name`, `description`, `price`,  `date`, `made_in`,  `status`,  `approve`, `cat_id`, `member_id`) 
                        VALUES (:zitem_name, :Zdescription, :zprice,  now() , :zmade_in,  :zstatus ,  0, :zcat_id, :zmember_id);");
                    $stmt->execute([
                        'zitem_name' => $itemName,
                        'Zdescription' => $description,
                        'zprice' => $price,
                        'zmade_in' => $made_in,
                        'zstatus' => $status,
                        'zcat_id' => $cate,
                        'zmember_id' => $member]);
                    MSG($stmt->rowCount() . ' insert a new Item is Done','success','profile.php');
                }
            } //close err
        } else {
            MSG('No Access page', 'danger', 'profile.php', 4.5); //close post
        } //end insert
        
    } elseif ($do == 'edit') {            
    print "<div class='container'>";
    print "<h1 class='text-center'>Item Edit</h1>";
    $itemID = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']): 0;
    $stmt = $con->prepare('SELECT  *  FROM items WHERE id = ? ');
    $stmt->execute([$itemID]);
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) { ?>   
            <form action="?do=update" method="POST" class="form-horizontal" >
                <input type="hidden" name="itemID" value="<?php print $row['id']?>" id="">
                <!-- cat name  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Item name</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="itemName" class="form-control" required  value="<?php print $row['item_name']?>">
                    </div>
                </div>               
                <!-- Description  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Description</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="itemDescription" class="form-control" required value="<?php print $row['description']?>">
                    </div>
                </div>
                <!-- price  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Price</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="number" name="ItemPrice" class="form-control" value="<?php print $row['price']?>">
                    </div>
                </div>
                <!-- made  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Made in ?</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="made_in" class="form-control"  value="<?php print $row['made_in']?>">
                    </div>
                </div>
                <!-- Status  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Status</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name='status' >
                            <option value="0">Choise Status</option>
                            <option value="1" <?php $row['status'] == '1' ? print "selected" : ""?>>New</option>
                            <option value="2" <?php $row['status'] == '2' ? print "selected" : ""?>>Used</option>
                            <option value="3" <?php $row['status'] == '3' ? print "selected" : ""?>>Old</option>
                        </select>
                    </div>
                </div>  
                <!-- users  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Member</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name='member'>
                            <?php 
                            try {
                                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user) { ?>
                                <option value='<?php  print $user['UserID'];?>'  <?php $row['member_id'] ==  $user['UserID'] ? print 'selected':  ""  ?>>
                                    <?php  print $user['UserName'];?>
                                </option>
                                <?php };
                            } catch(PDOException $e) {echo "Connection failed: " . $e->getMessage();}?>
                        </select>
                    </div>
                </div>                 
                <!-- cate  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Category</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name='cate'>
                            <?php 
                            $stm2 = $con->prepare("SELECT * FROM `categories`");
                            $stm2->execute();
                            $cats = $stm2->fetchAll();
                            foreach ($cats as $cat) { ?>
                                <option value='<?php  print $cat['id'];?>'  <?php $row['cat_id'] ==  $cat['id'] ? print 'selected':  ""  ?>>
                                    <?php  print $cat['name'];?>
                                </option>
                                <?php }?>
                        </select>
                    </div>
                </div>
                <!-- Submit  -->
                <div class="form-group form-control-lg p-0">
                    <div class="d-grid gap-2 col-2">
                        <input type="submit" value="Update" name='save' class="btn btn-primary ">
                    </div>
                </div>            
            </form>
            <?php 
            // INCLUDE COMMENTS IN ITEM 
            $h1 = "Manage  ( $row[item_name] ) Comments";
            $stmt = $con->prepare("SELECT * FROM comments WHERE item_id = $row[id]");
            $stmt->execute();
            $comments = $stmt->fetchAll();
            ?>
            <!-- Create Table  -->
            <h1 class="text-center"><?php print $h1 ?></h1>
            <div class="container">
            <?php 
            if ($comments == array()) { //check if there data pending coms
                    MSG("No Commets", "danger", "comments.php", 3);
                } else { ?>       
            <div class="table-responsive ">
                <table class="table table-sm table-light table-hover table-bordered border-secondary-subtle text-center m-0">
                    <thead class='table-secondary'>
                        <tr>
                        <th>comment</th>
                        <th>Item</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Control</th>
                        </tr>
                    </thead>
                    <tbody class=''>
                    <?php  foreach($comments as $comm) {?>
                    <tr>
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





        </div>        
        <?php }
    // if no results or wrong from database
    else {//if ($count > 0) if no results from database
        MSG("Wrong Access","danger" ,"categories.php" );
    }
    print "</div>";
    












    } elseif ($do == 'update') {
        print "<div class='container'>";
            print "<h1 class='text-center '>Item Update</h1>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $itemID                 = $_POST['itemID'];
                    $itemName           = $_POST['itemName'];
                    $itemDescription  = $_POST['itemDescription'];
                    $ItemPrice            = $_POST['ItemPrice'];
                    $made_in             = $_POST['made_in'];
                    $status                 = $_POST['status'];
                    $member             = $_POST['member'];
                    $cate                    = $_POST['cate'];
                    //validate
                    $errorrs = [];
                        empty($itemName)          ?  $errorrs[] = 'Item Name cant be Empty'  : "" ;
                        empty($itemDescription) ?  $errorrs[] = 'Full Name cant be Empty'    : "" ;
                        empty($ItemPrice)           ?  $errorrs[] = 'Price cant be empty'             : "" ;
                        empty($made_in)             ?  $errorrs[] = 'Made in cant be empty'       : "" ;
                        $status == 0                    ?  $errorrs[] = 'Status cant be empty'           : "" ;
                        $cate == 0                       ?  $errorrs[] = 'cate cant be empty'              : "" ;
                    foreach ($errorrs as $err) {print '<div class="alert alert-danger" role="alert">' .$err .'</div>';}
                    if (empty($errorrs)) {
                        $stmt = $con->prepare(
                            'UPDATE `items` SET `item_name` = ? , `description` = ? , `price` = ?, `cat_id`  = ? , `status` = ? , `member_id` = ? ,`cat_id` = ? 
                            WHERE `items`.`id` = ?   ;');
                        $stmt->execute([$itemName, $itemDescription, $ItemPrice, $made_in , $status, $member ,  $cate , $itemID ]);
                        MSG($stmt->rowCount() . ' Edit is done ', "success", "items.php", 3);
                    }
                } else {// if servser request is wronge //message for wronge access
                    MSG("No Access page" , "danger" , "items.php", 6);
                };
        print '</div>';







    } elseif ($do == 'delete') {
        print "<div class='container'>";
        print "<h1 class='text-center '>Delete Item</h1>";
        $ItemID = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']): 0;
        $stmt = $con->prepare(' DELETE FROM items WHERE `items`.`id` = ?');
        $stmt->execute([$ItemID]);
        $count = $stmt->rowCount();
        if ($count > 0) {MSG($stmt->rowCount() . ' Delete is Done' , "success", "items.php", 4);
        } else{MSG("No ID " , "danger" , "items.php", 3);}
        print "</div>";



    } elseif ($do == 'show') {
    print "<div class='container py-1'>";
        print "<h1 class='text-center'>Item Detail</h1>";
        $itemID = isset($_GET['item_id']) && is_numeric($_GET['item_id'])? intval($_GET['item_id']): 0;
        $stmt = $con->prepare('SELECT  *  FROM items WHERE id = ? ');
        $stmt->execute([$itemID]);
        $rows = $stmt->fetch();
        $count = $stmt->rowCount();        
        if ($count > 0 )  {                  
            print '<div class="row">';?>             
                <!-- Item  -->
                <div class="col-md-6 col-sm-8 col-10 mx-auto ">
                    <!-- card Item  -->
                    <div class="card" >
                        <img src="media/shop.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="txt d-flex  ">
                                <h5 class="card-title m-0 flex-grow-1">  <?php print $rows['item_name'];?>  </h5>
                                <a class="bg-success rounded text-light p-1 mx-1 fs-6"
                                    href="categories.php?cat_id=<?php print $rows['cat_id'] . '&cat_name=' . getName("name" , "categories" , "id" , "$rows[cat_id]") ;?>" >
                                    <?php print getName("name" , "categories" , "id" , "$rows[cat_id]")  ?> 
                                </a>
                                <div class="bg-primary rounded text-light p-1  mx-1"> 
                                    <?php
                                        if ($rows['status'] == 1) {print "New";}
                                        if ($rows['status'] == 2) {print "Uesd";} 
                                        if ($rows['status'] == 3) {print "Old";} 
                                    ?>
                                </div>
                            </div>                            
                            <p class="card-text"><?php print $rows['description'];?></p>
                            <h5  class="card-text text-danger"> <?php print $rows['price'];?> $</h5>
                            <div class="input-group-sm">Qty: <input type="number" name="" class="w-25  form-control d-inline"  value="1"> </div>
                            <small>Dreate Date: <span><?php print $rows['date'];?></span></small>
                            <a href="#" class="btn btn-primary mt-2 btn-sm w-100 ">Add to Card</a>
                        </div>
                    </div>                 
                    <!-- add commet --> 
                    <form action="post.php?do=addComm" method="POST" class="comments  my-2 text-dark bg-white" style="max-width: 1000px ">
                        <input type="hidden" value="<?php print $rows['id'];?>" name="item_id">
                        <div class="mb-3">
                            <label for="textarea" class="form-label">Add Comment</label>
                            <textarea class="form-control" id="textarea" rows="3" name="new_comm"></textarea>
                        </div>
                        <input  type="submit" value="Send" class="btn btn-outline-primary btn-sm mt-1">
                    </form>
                </div>
                <!-- comments  -->
                <div class="col-md-6  col-10 mx-auto mt-1">
                    <ul class="list-group">
                        <h4 class="text-center">Comments </h4>
                        <?php 
                            $stm2 = $con->prepare("SELECT * FROM comments WHERE item_id = ?");
                            $stm2->execute([$itemID]);
                            $comms = $stm2->fetchAll();
                            foreach ($comms as $comm) {
                                print '<li class="list-group-item">';
                                print "<div><i class='fa-regular fa-circle-user fa-sm'></i> " . getName('UserName', 'users' , 'UserID' , "$comm[user_id]") . "</div>";
                                    print "<h6 class='my-2'>$comm[comment]</h6>";
                                    print "<small>  <i class='fa-solid fa-calendar-days fa-sm'></i> $comm[comm_date]</small>";
                                print '</li>';
                            }  ?>
                    </ul>
                </div><?php                       
            print '</div>'; // close row
            } else {
                MSG("No Item","danger" ,"profile.php" );
            }









    print '</div>'; // close Container 

    } elseif ($do == 'addComm') {    
        print "<div class='container'>";
        print "<h1 class='text-center '>Insert Comment</h1>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_comm =  filter_var($_POST['new_comm'] , FILTER_SANITIZE_STRING);
            if (empty($new_comm)) {
                print '<div class="alert alert-danger" role="alert">Comment  cant be Empty</div>';
                header("refresh: 3; url=profile.php");
            } else {
                $item_id = $_POST['item_id'];
                $user_id = $_SESSION['general_user_id_session'];
                $stmt = $con->prepare("INSERT INTO `comments` ( `comment`, `comm_date`, `item_id`,  `user_id`) 
                    VALUES (:zcomment , now() , :zitem_id , :zuser_id)");
                $stmt->execute([
                    'zcomment' => $new_comm,
                    'zitem_id' => $item_id,
                    'zuser_id' => $user_id                    
                    ]);
                MSG($stmt->rowCount() . ' insert a new Item is Done','success',"post.php?do=show&item_id=$item_id");
            }
        } else {
            MSG("No Access page', 'danger', 'profile.php", 4.5); //close post
            } //end addComm

    
    } else {
        header('Location: profile.php');
        exit();
    }
    include 'includes/templets/footer.php';
} else {
    print("You must me sign in First ");
    print "<a href='index.php'> Home </a>";
    exit();
}
ob_end_flush();












