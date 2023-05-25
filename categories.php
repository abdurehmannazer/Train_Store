<?php 
session_start();
include 'init.php';
?>



<div class="container ">
    <h1 class='text-center'><?php print $_GET['cat_name'] ;?></h1>


<?php
if (isset($_SESSION['general_user_session']) ) {
    $items = get_items_By_cate($_GET['cat_id']  ); 
} else {
    $items = get_items_By_cate($_GET['cat_id'] , 1 );  
}

if ($items == array()) {
    print "No Items";
} else {
    print '<div class="row">';  
        foreach ($items as $item) { ?>
        
        <div class="col-sm-6 col-md-4  ">
            <div class="card " >
                <img src="media/shop.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"> 
                        <a href='post.php?do=show&item_id=<?php print "$item[id]" ?>'><?php print $item["item_name"]?></a> 
                        <small class="text-danger" title="Not Approve" ><?php $item["approve"] == 0 ? print "**" : "" ?></small>
                    </h5>
                    <p class="card-text"><?php print $item["description"]?></p>
                    <h5  class="card-text text-danger"><?php print $item["price"]?>$</h5>
                    <div class="input-group-sm">Qty: <input type="number" name="" class="w-25  form-control d-inline"  value="1"> </div>
                    <small class='float-end'><?php print $item["date"]?></small>
                    <a href="#" class="btn btn-primary mt-2 btn-sm w-100 ">Add to Card</a>
                </div>
            </div>
        </div>
        <?php } //close loop
    print '</div>';

}

?>










</div>
<?php
include 'includes/templets/footer.php'; 


