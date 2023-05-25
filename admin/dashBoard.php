<?php
session_start();
$title = 'Dash Board';
if (isset($_SESSION['admin_session'])) { //sesseion start
    include 'init.php';   ?>
    <div class='container home-stats text-center text-white d-block'> 
        <h1>Dashboard</h1>
        <div class="row"  >
            <div class="col-md-3">
                <div class="stat rounded p-3 d-flex justify-content-around align-items-center h-100" style="background-color: #12CBC4;">
                    <i class='fa fa-users m-1' style='font-size: 40px'></i>
                    <div class="p-2 m-1">
                        <span class='fw-bold' style='font-size: 16px'>Total Members</span>  
                        <span class='fw-normal'> <a href="members.php"> <?php print  countData("UserID", "users");?></span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat rounded p-2 d-flex justify-content-around align-items-center h-100" style="background-color: #FFC312;">
                    <i class='fa-solid fa-user-check m-1' style='font-size: 40px'></i>                    
                <div class="p-2 m-1">
                    <span  class='fw-bold' style='font-size: 16px'>Pending Members</span>
                    <span class='fw-normal'> <a href="members.php?page=pending"><?php print  checkDatabase('RegStatus' , 'users' , 0);?></a></span>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat rounded p-2 d-flex justify-content-around align-items-center h-100" style="background-color: #B53471;">
                    <i class='fa fa-tag m-1' style='font-size: 40px'></i>                 
                    <div class="p-2 m-1">
                        <span class='fw-bold' style='font-size: 16px'>Total Items</span>
                        <span class='fw-normal'><a href="items.php"><?php print  countData("id", "items");?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat rounded p-2 d-flex justify-content-around align-items-center h-100" style="background-color: #40407a;">
                    <i class='fa-solid fa-comments m-1' style='font-size: 35px'></i>               
                    <div class="p-2 m-1">
                        <span class='fw-bold' style='font-size: 16px'>Total Comments</span>    
                        <span class='fw-normal'><a href="comments.php"><?php print  countData("comm_id", "comments");?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest d-block">
        <div class="row">
            <div class="col-sm-6">
                <div class="card " >
                    <div class="card-header fw-bold">
                        <i class='fa fa-users'></i> Latest 5 Registers Users
                        <span class='float-end iconToggle '><i class="fa-solid fa-plus"></i></span>
                    </div>
                    <ul class="list-group list-group-flush"> <?php foreach (latestData("*", "users", "UserID", 6) as $user) { ?>                                                     
                        <li class="list-group-item "><?php print "  ".  $user["UserName"]. " ";print $user["GroupID"] == 1 ?  "<b class='text-dark' title='Admin'><i class='fa-solid fa-user-tie '></i></b>" : "";?> 
                            <a href="members.php?do=edit&id=<?php print $user['UserID']?>" class="btn  btn-sm  rounded-circle text-success float-end " title='Edit Information'><i class="fa-solid fa-user-pen "></i></a>
                            <a href="members.php?do=delete&id=<?php print $user['UserID']?>" class="btn   btn-sm rounded-circle confirm text-danger float-end" title='Delete Member'><i class="fa-solid fa-trash "></i></a>        
                            <?php if ($user["RegStatus"] == 0) { ?><a href="members.php?do=active&id=<?php print $user['UserID']?>" class="btn   btn-sm rounded-circle text-primary float-end" title='Active Member'><i class="fa-solid fa-check-double"></i></a><?php } ?>
                        </li> <?php }?> 
                    </ul>
                </div>                
            </div>
            <div class="col-sm-6">
                <div class="card " >
                    <div class="card-header fw-bold">
                        <i class='fa fa-tag'></i> Letest 5 Items
                        <span class='float-end iconToggle '><i class="fa-solid fa-plus"></i></span>
                    </div>
                    <ul class="list-group list-group-flush"> <?php foreach (latestData("*", "items", "id", 5) as $item) { ?>                                                     
                        <li class="list-group-item "><?php print $item['item_name'] . "</b>" ;?> 
                            <a href="items.php?do=edit&id=<?php print $item['id']?>" class="btn  btn-sm  rounded-circle text-success float-end " title='Edit Information'><i class="fa-solid fa-user-pen "></i></a>
                            <a href="items.php?do=delete&id=<?php print $item['id']?>" class="btn   btn-sm rounded-circle confirm text-danger float-end" title='Delete Member'><i class="fa-solid fa-trash "></i></a>        
                            <?php if ($item["approve"] == 0) { ?>
                                <a href="items.php?do=approve&id=<?php print $item['id']?>" class="btn   btn-sm rounded-circle text-primary float-end" title='Active Member'><i class="fa-solid fa-check-double"></i></a>        
                            <?php } ?>
                        </li> <?php }?> 
                    </ul>
                </div>                
            </div>
        </div>
    </div>
<?php include 'includes/templets/footer.php';
} else {
    //if no data in seseeion
    header('Location: index.php');
    exit();
}
?>

