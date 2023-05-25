<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" <?php print 'layuots/css/bootstrap.min.css"';?>">
    <link rel="stylesheet" href=" <?php print 'layuots/css/all.min.css"';?>">
    <link rel="stylesheet" href=" <?php print 'layuots/css/jquery-ui.css"';?>">
    <link rel="stylesheet" href=" <?php print 'layuots/css/jquery.selectBoxIt.css"';?>">
    <link rel="stylesheet" href=" <?php print 'layuots/css/index.css';?>">
    <link rel="stylesheet" href=" <?php print 'layuots/css/front.css';?>">

    <title><?php printTitle(); ?></title>
</head>
<body>
    <div class="upper-bar">
    </div>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <button class="navbar-toggler text-white  border-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""><i class="fa-solid fa-bars"></i></span>
        </button>
        <a class="nav-link " aria-current="page" href="http://localhost/online_store_elzero">Store</a>
        <div class="collapse navbar-collapse d-lg-flex justify-content-end " id="navbarSupportedContent"  >
            <ul class="navbar-nav  mb-2 mb-lg-0 " >
                <?php 
                foreach(get_cete_arr() as $cat) {
                    print
                        "<li class='nav-item'>
                            <a class='nav-link' href='categories.php?cat_id=$cat[id]&cat_name=$cat[name]'>$cat[name]</a>
                        </li>                    
                        ";
                }
                ?>
                <li class="nav-item text-Success  ">
                    <a class="nav-link " href="http://localhost/online_store_elzero/admin">Admin Page</a>
                </li>      
                
                <?php 
                if (isset($_SESSION['general_user_session'])) {; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php print $session_user; ?></a>            
                        <ul class="dropdown-menu ">
                            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href=""><label for="BUTTON_MODEL">New Item</label></a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>    
                    <?php 
                    } else {
                    print  "<li class='nav-item text-Success  '>";
                            print     "<a class='nav-link ' href='http://localhost/online_store_elzero/login.php'>Login / Signup </a>";
                    print  "</li>  " ;
                }
                ?>
            </ul>         
    </div>
</div>
</nav>



<!-- model  add item-->
<div class="login_model">
    <button type="button" id="BUTTON_MODEL" class="btn btn-outline-primary btn-sm  my-2 px-4 d-none" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="">+ Add New Post</button>
    <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header ">
                    <h2 class="modal-title fs-5 text-light " id="exampleModalLabel">+ Add New Item</h2>
                    <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-dark">
                    <form class='bg-dark' action="post.php?do=insert" method="POST">
                        <input type="hidden">
                        <!-- Member ID  -->
                        <input type="hidden" name='member_id' value='<?php print $user_id;?>'>
                        <!-- Item Name -->
                        <div class="mb-3">
                            <label for="item_name" class="col-form-label">Item Name </label>
                            <input type="text" class="form-control" id="item_name" name='item_name' >
                        </div>
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="item_description" class="col-form-label">Description</label>
                            <input type="text" class="form-control" id="item_description" name='item_description' >
                        </div>
                        <!-- Price -->
                        <div class="mb-3">
                            <label for="item_Price" class="col-form-label">Price</label>
                            <input type="number" class="form-control" id="item_Price" name='item_Price'>
                        </div>
                        <!-- Made in ? -->
                        <div class="mb-3">
                            <label for="made_in" class="col-form-label">Made in ?</label>
                            <input type="text" class="form-control" id="made_in" name='made_in'>
                        </div>
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="item_status" class="col-form-label">Item Status</label>
                            <select class="form-select" aria-label="Default select example" id="item_status" name='item_status'>
                                <option selected value="0">Choise Status</option>
                                <option value="1">New</option>
                                <option value="2">Used</option>
                                <option value="3">Old</option>
                            </select>
                        </div>
                        <!-- Category -->
                        <div class="mb-3">
                            <label for="item_cate" class="col-form-label">Choise Category</label>
                            <select class="form-select" aria-label="Default select example" id="item_cate" name='item_cate'>
                                <option selected value="0">Choise Category</option>
                                    <?php 
                                    $stm2 = $con->prepare("SELECT * FROM `categories`");
                                    $stm2->execute();
                                    $cats = $stm2->fetchAll();
                                    foreach($cats as $cat) {
                                        print "<option value='$cat[id]'>$cat[name]</option>";};?>
                            </select>
                        </div>
                        <div class="mb-3 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="Add items" name='save' class="btn btn-primary ">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end model  -->