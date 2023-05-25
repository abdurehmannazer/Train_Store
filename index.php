<?php
session_start();
include 'init.php';
?>

<div class="container ">
    <h1 class='text-center'>Home Page</h1><?php
    $stm = $con->prepare("SELECT * FROM items  WHERE approve = 1  ORDER BY id DESC");
    $stm->execute();
    $items = $stm->fetchAll();    
    if ($items == array()) {
        print "No Items";
    } else {
        print '<div class="row">';  
            foreach ($items as $item) { ?>            
            <div class="col-sm-6 col-md-4  col-9 mx-auto my-1">
                <div class="card " >
                    <img src="media/shop.png" style="max-width: 160px"  class="card-img-top mx-auto" alt="...">
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
            <?php } //close loop ?>
</div>
<?php }


include 'includes/templets/footer.php';
?>
