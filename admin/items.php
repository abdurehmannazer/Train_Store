<?php
ob_start();
session_start();
$title = 'Items';

if (isset($_SESSION['admin_session'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    //manage 
    if ($do == 'manage') { 
        //use join to call the Member and Category from other tables
        $stmt = $con->prepare(
        " SELECT items.* , users.UserName , categories.name AS cate_name FROM items 
        INNER JOIN users ON users.UserID = items.member_id 
        INNER JOIN categories ON categories.id = items.cat_id;");
        $stmt->execute();
        $rows = $stmt->fetchAll();        
        print "<h1 class='text-center'>Manage Items</h1>";
        print "<div class='container'>";
        if ($rows == array()) { //check if there data pending coms
                MSG("No Items to Active", "danger", "items.php?do=add", 3);
            } else { ?>       
        <div class="table-responsive ">
            <table class="table table-sm table-light table-hover table-bordered border-secondary-subtle text-center m-0">
                <thead class='table-secondary'>
                    <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Member</th>
                    <th>Control</th>
                    </tr>
                </thead>
                <tbody class=''>
                <?php  foreach($rows as $row) {?>
                <tr>
                        <th scope="row"><?php print $row["id"]; ?></th>
                        <td><?php print $row["item_name"]; ?></td>
                        <td><?php print $row["description"]; ?></td>
                        <td><?php print $row["price"]  . " $" ;?></td>
                        <td> <?php print $row["date"];?> </td>
                        <td> <?php print $row["cate_name"];?> </td>
                        <td> <?php print $row["UserName"];?> </td>
                        <td style="text-align: start;">
                            <a href="items.php?do=edit&id=<?php print $row['id']?>" class="btn  btn-sm  rounded-circle text-success " title='Edit Information'><i class="fa-solid fa-user-pen "></i></a>
                            <a href="items.php?do=delete&id=<?php print $row['id']?>" class="btn   btn-sm rounded-circle confirm text-danger " title='Delete Member'><i class="fa-solid fa-trash "></i></a>        
                            <?php if ($row["approve"] == 0) { ?>
                            <a href="items.php?do=approve&id=<?php print $row['id']?>" class="btn   btn-sm rounded-circle text-primary" title='Approve Item'><i class="fa-solid fa-check-double"></i></a>        
                            <?php } ?>
                        </td>
                </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <?php } ?>
        <a href='items.php?do=add' class='btn btn-primary mt-3 btn-sm'> <i class="fa-solid fa-plus"></i> Add New Item </a>
        </div>




<?php 
    } elseif ($do == 'add') { ?>
        <div class="container">
        <h1 class="text-center">Add New Item</h1>
            <form action="?do=insert" method="POST" class="form-horizontal" >
                <!-- name  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Name</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="item_name" class="form-control" required >
                    </div>
                </div>
                <!-- Description  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Description</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="description" class="form-control" required>
                    </div>
                </div>
                <!-- price  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Price</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="number" name="price" class="form-control" >
                    </div>
                </div>
                <!-- made  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Made in ?</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="made_in" class="form-control" >
                    </div>
                </div>
                <!-- Status  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Status</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name='status'>
                            <option selected value="0">Choise Status</option>
                            <option value="1">New</option>
                            <option value="2">Used</option>
                            <option value="3">Old</option>
                        </select>
                    </div>
                </div>
                <!-- users  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Member</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name='member'>
                            <option selected value="0">Choise Member</option>
                            <?php 
                            try {
                                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user) {
                                    print "<option value='$user[UserID]'>$user[UserName]</option>";
                                };
                            } catch(PDOException $e) {echo "Connection failed: " . $e->getMessage();}?>
                        </select>
                    </div>
                </div>
                <!-- cate  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Category</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name='cate'>
                            <option selected value="0">Choise Category</option>
                            <?php 
                            $stm2 = $con->prepare("SELECT * FROM `categories`");
                            $stm2->execute();
                            $cats = $stm2->fetchAll();
                            foreach($cats as $cat) {
                                print "<option value='$cat[id]'>$cat[name]</option>";};?>
                        </select>
                    </div>
                </div>
                <!-- Submit  -->
                <div class="form-group form-control-sm p-0">
                    <div class="d-grid gap-2 col-2">
                        <input type="" value="Add items" name='save' class="btn btn-primary ">
                    </div>
                </div>
            </form>
        </div><?php  // end add




    } elseif ($do == 'insert') {
        print "<div class='container'>";
        print "<h1 class='text-center '>Insert Item</h1>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemName = $_POST['item_name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $made_in = $_POST['made_in'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cate = $_POST['cate'];
            $errorrs = [];
            if (empty($itemName)) {$errorrs[] = 'Item Name cant be Empty';}
            if (empty($description)) {$errorrs[] = 'Full Name cant be Empty';}
            if (empty($price)) {$errorrs[] = 'Price cant be empty';}
            if (empty($made_in)) {$errorrs[] = 'Made in cant be empty';}
            if ($status == 0) {$errorrs[] = 'Status cant be empty';}
            if ($member == 0) {$errorrs[] = 'member cant be empty';}
            if ($cate == 0) {$errorrs[] = 'cate cant be empty';}
            foreach ($errorrs as $err) {print '<div class="alert alert-danger" role="alert">' .$err .'</div>';}
            if (empty($errorrs)) {
                if (checkDatabase('item_name ', 'items', "$itemName") >= 1) {
                    // check name
                    MSG('oops ! this Item Name is already inserted ,  insert another one','danger','items.php?do=add'); // if user name is already inserted
                } else {
                    $stmt = $con->prepare(
                        "INSERT INTO `items` ( `item_name`, `description`, `price`,  `date`, `made_in`, `status`, `cat_id`, `member_id`) 
                        VALUES (:zname, :zdes, :zprice , now() , :zmad , :zstatus, :zcate , :zmember )");
                    $stmt->execute([
                        'zname' => $itemName,
                        'zdes' => $description,
                        'zprice' => $price,
                        'zmad' => $made_in,
                        'zstatus' => $status,
                        'zcate' => $cate,
                        'zmember' => $member
                    ]);
                    MSG($stmt->rowCount() . 'insert a new cate is Done','success','items.php');
                }
            } //close err
        } else {
            MSG('No Access page', 'danger', 'index.php', 4.5); //close post
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



    } elseif ($do == 'approve') {
        print "<h1 class='text-center '>Approve Item</h1>";
        print "<div class='container'>";
        $id = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;        
        if (checkDatabase('id', 'items', $id) > 0) {
            $stmt = $con->prepare('UPDATE `items` SET approve = ? WHERE `items`.`id` = ?   ;');
            $stmt->execute([ 1 , $id]);
            MSG($stmt->rowCount() . ' Active is Done' , "success", "items.php", 4);
        }else {
            MSG("No ID " , "danger" , "items.php", 4);
        }
        print '</div>';



    } else {
        header('Location: index.php');
        exit();
    }
    include 'includes/templets/footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
