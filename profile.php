<?php
session_start();
$title = 'profile';
include 'init.php';

if (isset($_SESSION['general_user_session'])) {
    $stm = $con->prepare("SELECT * FROM users WHERE UserName = ?");
    $stm->execute(array($session_user));
    $data = $stm->fetch();
    $_SESSION['general_user_id_session'] = $data['UserID']; 
    
    ?>
    <!-- profile  -->
    <section style="background-color: #eee;">
        <div class="container py-4">
            <h1 class='text-center'><?php  print "Wellcome " . $data['UserName'] ?> </h1>

            <!-- row 1  -->
            <div class="row mx-auto">
                <!-- image -->
                <div class="col-md-4">
                    <div class="card mb-4" style='min-height: 350px'>
                        <div class="card-body text-center">
                                <img src='<?php  $data["user_image"] == "" ? print "media/preson.png"  : print "$data[user_image]" ; ?>' alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                <h5 class="my-3"><?php print $data['UserName']?></h5>
                                <p class="text-muted mb-1"></p>
                                <p class="text-muted mb-4"></p>
                                <div class="d-flex justify-content-center  mt-5">
                                    <button type="button" class="btn btn-primary">Follow</button>
                                    <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                                </div>
                        </div>
                    </div>
                </div>
                <!-- info -->       
                <div class="col-md-8 h-100 ">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4" ><p class="mb-0"> <i class="fa-solid fa-user-tie fa-sm" style='position: relative; top: 0'></i>    Full Name</p></div>
                                <div class="col-sm-8"><p class="text-muted mb-0"><?php print $data['UserName'];?></p></div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-4"><p class="mb-0"><i class="fa-solid fa-envelope fa-sm" style='position: relative; top: 0'></i>    Email</p></div>
                                <div class="col-sm-8"><p class="text-muted mb-0"><?php print $data['Email']?></p></div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-4"><p class="mb-0"><i class="fa-solid fa-calendar-check fa-sm" style='position: relative; top: 0'></i>    Register Data</p></div>
                                <div class="col-sm-8"><p class="text-muted mb-0"><?php print $data['date']?></p></div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-4"><p class="mb-0"><i class="fa-solid fa-mobile-retro fa-sm" style='position: relative; top: 0'></i>    Mobile</p></div>
                                <div class="col-sm-8"><p class="text-muted mb-0">(098) 765-4321</p></div>
                            </div><hr>
                            <div class="row">
                                <div class="col-sm-4"><p class="mb-0"><i class="fa-solid fa-map-location fa-sm" style='position: relative; top: 0'></i>    Address</p></div>
                                <div class="col-sm-8"><p class="text-muted mb-0">Riyadh, Saudi Arabia</p></div>
                            </div><hr>
                            <div class="d-flex justify-content-center py-3 ">
                                <div class="mx-4"><a href="https://mdbootstrap.com"><i class="fab fa-twitter fa-lg" style="color: #55acee;"></i></a></div>
                                <div class="mx-4"><a href="https://mdbootstrap.com"> <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i></a></div>
                                <div class="mx-4"><a href="https://mdbootstrap.com"><i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i></a></div>
                                <div class="mx-4"> <a href="https://mdbootstrap.com"><i class="fas fa-globe fa-lg text-warning"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
            <!-- row 2  -->
            <div class="row mx-auto">
                <!--  items  -->
                <div class="col-md-6">
                    <h1 class='text-center'>Items</h1>
                    <ol class="list-group ">
                    <?php 
                        $stm = $con->prepare("SELECT * FROM items WHERE member_id = ?");
                        $stm->execute(array($data['UserID']));
                        $items = $stm->fetchAll();
                        if ($items == array()) {
                        print "<div class='alert alert-danger' role='alert'>There is No Items</div>";
                        } else {
                            include "model.php";
                            foreach($items as $item) {
                            print "<a href='post.php?do=show&item_id=$item[id]'>";
                                print  "<li class='list-group-item d-flex justify-content-between align-items-start'>";
                                    print  "<div class='ms-2 me-auto'>";
                                        print  "<div class='fw-bold'>$item[item_name] <span class='text-danger'>$item[price] $</span> </div>";
                                        print  "<small>$item[description]</small>                                ";
                                    print  "</div>";
                                    print  "<span class='badge bg-primary rounded-pill'><i class='fa-solid fa-heart'></i> 14</span>";                           
                                print  "</li>";
                            print "</a>";
                            }
                        }?>    
                        </ol>
                        <!-- model  add item-->                        
                        <div class="login_model">
                            <button type="button" class="btn btn-outline-primary btn-sm  my-2 px-4" 
                                data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="+">Add Item</button>
                            <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header ">
                                            <h2 class="modal-title fs-5 text-light " id="exampleModalLabel">+ Add New Item ---------- </h2>
                                            <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body bg-dark">
                                            <form class='bg-dark' action="post.php?do=insert" method="POST">
                                                <input type="hidden">
                                                <!-- Member ID  -->
                                                <input type="hidden" name='member_id' value='<?php print $data['UserID']?>'>
                                                <!-- Item Name -->
                                                <div class="mb-3">
                                                    <label for="item_name" class="col-form-label">Item Name </label>
                                                    <input type="text" class="form-control" id="item_name" name='item_name' required>
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
                        </div>           
                        <!-- Comments -->
                        <div class="col-md-6">
                            <h1 class='text-center'>Comments</h1>
                            <ol class="list-group ">
                            <?php 
                                $stm = $con->prepare("SELECT * FROM comments WHERE user_id = ?");
                                $stm->execute(array($data['UserID']));
                                $comments = $stm->fetchAll();
                                if ($comments == array()) {
                                print "<div class='alert alert-danger' role='alert'>There is No Comments</div>";
                                } else {
                                    foreach ($comments as $comm) {
                                        print "<li class='list-group-item d-flex justify-content-between align-items-start'>";
                                            print "<div class='ms-2 me-auto'>";
                                                print "<div class='fw-bold'>" . getName('item_name', 'items', "id" , $comm["item_id"] )  . "</div>";
                                                print "<small>$comm[comment]</small>" ;
                                            print "</div>";
                                            print "<span class='badge bg-primary rounded-pill'><i class='fa-solid fa-heart'></i> 16</span>";
                                        print "</li>";
                                    }
                                }?>                  
                                </ol>
                        </div>


                </div>
            </div>
    </section>



<?php 
} else {
    print "Wrong accese";
    // header("Location: index.php");
}//close session
include 'includes/templets/footer.php';

