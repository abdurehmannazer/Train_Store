<?php ?>



<!-- model  add item-->                       
<div class="login_model">
<button type="button" id="model2" class="d-none"  data-bs-toggle="modal" data-bs-target="#exampleModal2" data-bs-whatever=""></button>
<div class="modal fade " id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header ">
                <h2 class="modal-title fs-5 text-light " id="exampleModalLabel">Show Item <?php print $_SESSION['item_id']?></h2>
                <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-dark">
                <form class='bg-dark' action="post.php?do=insert" method="POST">
                    <input type="hidden">
                    <!-- Member ID  -->
                    <input type="hidden" name='member_id' value='<?php print $data['UserID']?>'>
                    <!-- Item Name -->
                    <div class="mb-3">
                        <label for="item_name" class="col-form-label">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name='item_name' required value='8888888888'>
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
