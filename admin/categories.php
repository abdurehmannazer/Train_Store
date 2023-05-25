<?php
ob_start();
session_start();
$title = 'Categories';

if (isset($_SESSION['admin_session'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; 


    if ($do == 'manage') {  
        print " <div class='container '>";   
        print " <h1 class='text-center'>Category</h1>";
        $sort = "ASC";
        isset($_GET["sort"]) && $_GET["sort"] == "DESC"  ? $sort = $_GET["sort"] : "";
        isset($_GET["sort"]) && $_GET["sort"] == "ASC"  ? $sort = $_GET["sort"] : "";
        $stmCat = $con->prepare("SELECT * FROM categories WHERE parent_cate = 0  ORDER BY ordering  $sort ");
        $stmCat->execute();
        $cats = $stmCat->fetchAll(); 
        
        
        if ($cats == array()) { //check if there data pending coms
            MSG("No Categories", "danger", "categories.php?do=add");
        } else { ?>   
        <div class="container latest category">
            <div class="row">
                <div class="">
                    <div class="card " >
                        <div class="card-header  "> 
                            <b class='fs-sm-6'>Manage Categories</b> 
                            <div class="sort  d-inline w-50 text-end" > Order By: 
                                <a href="categories.php?sort=ASC" class='btn btn-sm p-1 btn-primary opacity-100 d-inline  <?php $sort == "ASC" ? print "active" : "" ?>'><i class="fa-solid fa-arrow-down m-1"></i></a>
                                <a href="categories.php?sort=DESC" class='btn  btn-sm p-1 btn-primary opacity-100 d-inline <?php $sort == "DESC" ? print "active" : "" ?>'><i class="fa-solid fa-arrow-up m-1"></i></a>
                            </div>                         
                        </div>
                        <ul class="list-group list-group-flush"> <?php foreach ($cats as $cat) { ?>                                                     
                            <li class="list-group-item catlist "><?php
                                print "<h4 class='m-0 catName' role='button' >" . $cat["name"] .  "</h4>";
                                print "<div class='full-view d-none'>";
                                    print "<small>" . $cat["description"] . "</small> <br>";
                                    $cat["visibility"] == 1 ? print "<i class='fa-regular fa-eye-slash  text-white  mt-2 p-1 text-center rounded' }' title='Hidden'></i>" : "";
                                    $cat["allow_comments"] == 1 ? print "<i class='fa-solid fa-comment-slash  text-white  mt-2 p-1 text-center rounded mx-1'title='No Allow Comment'></i>" : "";
                                    $cat["allow_ads"] == 1 ? print "<i class='fa-brands fa-adversal  text-white  mt-2 p-1 text-center rounded mx-1'  title='No Ads'></i>" : "";
                                    print '<div class="butt">';
                                        print "<a href='categories.php?do=edit&catID=$cat[id]'><i class='fa-solid fa-pen-to-square' title='Edit'></i></a>";
                                        print "<a href='categories.php?do=delete&catID=$cat[id]'><i class='fa-solid fa-trash-can' title='Delete' ></i></a>";
                                    print '</div>';
                                            $stmCat = $con->prepare("SELECT * FROM categories WHERE  parent_cate  = ? ");
                                            $stmCat->execute(array($cat["id"]));
                                            $chil_cates = $stmCat->fetchAll();
                                            if (!empty($chil_cates)) {
                                                print "<h6 class='ms-3 my-0'>Child Category:</h6>";
                                                print "<ul class='list-group list-group-flush ms-3'>";
                                                    foreach($chil_cates as $chil) {
                                                        print "<li> - $chil[name]</li>";
                                                    }                                                
                                                print "</ul>";
                                            }                                       
                                    print "</div>";
                            } ?>
                            </li>  
                    </div>                
                </div>
        </div>
    <?php print "<a href='categories.php?do=add' class='btn btn-primary mt-5'>Add New Category </a>";
        }
    // add 
    } elseif ($do == 'add') { ?> 
        <div class="container">
        <h1 class="text-center">Add New Category</h1>
            <form action="?do=insert" method="POST" class="form-horizontal" >
                <!-- name  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Name</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <!-- categoty  -->
                <div class="form-group form-control-lg row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Description</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="description"  class=" password form-control" required>
                    </div>
                </div>
                <!-- order  -->
                <div class="form-group form-control-lg row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Ordering</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="ordering" class="form-control"  required >
                    </div>
                </div>
                <!-- Visible  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Visible</label>
                    <div class="col-sm-9 col-md-4">
                        <div>
                            <input type="radio"  name="visibility" id="yes" value='0' checked>
                            <label for="yes" >Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="visibility" id="no" value='1' >
                            <label for="no">No</label>
                        </div>
                    </div>
                </div>                
                <!-- commen  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Allow Comment</label>
                    <div class="col-sm-9 col-md-4">
                        <div>
                            <input type="radio" name="allow_comment" id="com-yes" value='0' checked>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="allow_comment" id="com-no" value='1' >
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>                
                <!-- ads  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Allow Ads</label>
                    <div class="col-sm-9 col-md-4">
                        <div>
                            <input type="radio" name="allow_ads" id="ads-yes" value='0' checked>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="allow_ads" id="ads-no" value='1' >
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>                
                <!-- ads  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Parent Category ?</label>
                    <div class="col-sm-9 col-md-4">
                        <select class="form-select" aria-label="Default select example" name="parent_cate">
                            <option value="0">None</option>
                            <?php 
                                $get_cat = $con->prepare("SELECT * FROM categories  WHERE parent_cate = 0 ");
                                $get_cat->execute();
                                $cats = $get_cat->fetchAll();
                                print_r($cats['id']);
                                foreach ($cats as $cat) {
                                    print "<option value='$cat[id]''>$cat[name]</option>";
                                }
                            ?> 

                        </select>
                    </div>
                </div>                
                <!-- Submit  -->
                <div class="form-group form-control-lg p-0">
                    <div class="d-grid gap-2 col-2">
                        <input type="submit" value="Add Category" name='save' class="btn btn-primary ">
                    </div>
                </div>
            </form>

        </div><?php 
        // end add 
    







    //Insert
    } elseif ($do == 'insert') {
        print "<div class='container'>";
        print "<h1 class='text-center '>Insert Caregory</h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // get data 
            $name                 = $_POST['name'];
            $description        = $_POST['description'];
            $ordering            = $_POST['ordering'];
            $visibility             = $_POST['visibility'];
            $allow_comment = $_POST['allow_comment'];
            $allow_ads           = $_POST['allow_ads'];
            $parent_cate           = $_POST['parent_cate'];
            if (checkDatabase('name', 'categories', "$name") >= 1) { // check name
                MSG("oops ! this Caregory is already inserted ,  insert another one","danger" , "categories.php?do=add"); // if user name is already inserted
            } else {
                $stmt = $con->prepare(  
                    "INSERT INTO `categories` ( `name`, `description`,  `ordering`, `visibility`, `allow_comments`  , `allow_ads` ,`parent_cate`)
                    VALUES (:cate, :descrp,  :order, :visibility , :comments , :ads , :parent)");
                $stmt->execute([
                        'cate'             => $name,
                        'descrp'         => $description,
                        'order'           => $ordering,
                        'visibility'       => $visibility,
                        'comments'   => $allow_comment,
                        'ads'              => $allow_ads,
                        'parent' => $parent_cate
                    ]);
                    MSG( $stmt->rowCount()  . "insert a new cate is Done", "success", "categories.php", );
                }       
        } else {MSG("No Access page" , "danger", "categories.php" , 4.5);}//message for wronge access   if servser request is wronge
    //end insert 




    } elseif ($do == 'edit') {
    
    print "<div class='container'>";
    print "<h1 class='text-center'>Category Edit</h1>";
    // check
    $catID = isset($_GET['catID']) && is_numeric($_GET['catID'])? intval($_GET['catID']): 0;
    //get datae
    $stmt = $con->prepare('SELECT  *  FROM categories WHERE id = ? ');
    $stmt->execute([$catID]);
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) { ?>   
            <form action="?do=update" method="POST" class="form-horizontal" >
                <input type="hidden" name="CatID" value="<?php print $row['id']; ?>" id="">
                <!-- cat name  -->
                <div class="form-group form-control-lg row pb-2">
                    <label for="" class="col-sm-3  control-label fw-bold">Category Name</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="caName" class="form-control" required  value="<?php print $row['name']; ?>">
                    </div>
                </div>
                <!-- description  -->
                <div class="form-group form-control-lg row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Description</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="description" class="form-control"   value="<?php print $row['description']; ?>">
                    </div>
                </div>
                <!-- ordering  -->
                <div class="form-group form-control-lg row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Ordering</label>
                    <div class="col-sm-9 col-md-4">
                        <input type="text" name="ordering" class="form-control"   value="<?php print $row['ordering']; ?>">
                    </div>
                </div>
                <!-- Visible  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Visible</label>
                    <div class="col-sm-9 col-md-4">
                        <div>
                            <input type="radio"  name="visibility" id="yes" value='0' <?php $row['visibility'] == 0 ? print "checked" : "" ?>>
                            <label for="yes" >Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="visibility" id="no" value='1' <?php $row['visibility'] == 1 ? print "checked" : "" ?> >
                            <label for="no">No</label>
                        </div>
                    </div>
                </div>                
                <!-- commen  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Allow Comment</label>
                    <div class="col-sm-9 col-md-4">
                        <div>
                            <input type="radio" name="allow_comment" id="com-yes" value='0' <?php $row['allow_comments'] == 0 ? print "checked" : "" ?>>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="allow_comment" id="com-no" value='1' <?php $row['allow_comments'] == 1 ? print "checked" : "" ?> >
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>                
                <!-- ads  -->
                <div class="form-group form-control-lg  row pb-2 ">
                    <label for="" class="col-sm-3  control-label fw-bold">Allow Ads</label>
                    <div class="col-sm-9 col-md-4">
                        <div>
                            <input type="radio" name="allow_ads" id="ads-yes" value='0' <?php $row['allow_ads'] == 0 ? print "checked" : "" ?>>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="allow_ads" id="ads-no" value='1' <?php $row['allow_ads'] == 1 ? print "checked" : "" ?> >
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>                
                <!-- Submit  -->
                <div class="form-group form-control-lg p-0">
                    <div class="d-grid gap-2 col-2">
                        <input type="submit" value="Save" name='save' class="btn btn-primary ">
                    </div>
                </div>
            </form>
        </div>        
        <?php }
    // if no results or wrong from database
    else {//if ($count > 0) if no results from database
        MSG("Wrong Access","danger" ,"categories.php" );
    }
    print "</div>";
    





    } elseif ($do == 'update') {
        print "<h1 class='text-center '>Category Update</h1>";
        print "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $CatID = $_POST['CatID'];
            $caName = $_POST['caName'];
            $description = $_POST['description'];
            $ordering = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $allow_comment = $_POST['allow_comment'];
            $allow_ads = $_POST['allow_ads'];
            if (empty($caName)) {
                MSG("Name of Categoru Cant be Empty" , "danger" , "categories.php?do=edit&catID=$CatID");
            } else {
                $stmt = $con->prepare(
                    'UPDATE `categories` SET `name`  = ? , `description` = ? , `ordering` = ?, `visibility` = ? , `allow_comments` = ?  , `allow_ads` = ? WHERE `id` = ?   ;');
                $stmt->execute([$caName, $description, $ordering, $visibility , $allow_comment, $allow_ads ,$CatID ]);
                MSG($stmt->rowCount() . ' Edit is done ', "success", "categories.php");
            }
        } else {// if servser request is wronge //message for wronge access
            MSG("No Access page" , "danger" , "categories.php", 3);
        };
        print '</div>';


    } elseif ($do == "delete"){
        print "<div class='container'>";
            print "<h1 class='text-center '>Delete Category</h1>";
            $catID = isset($_GET['catID']) && is_numeric($_GET['catID'])? intval($_GET['catID']): 0;
            $stmt = $con->prepare(' DELETE FROM categories WHERE `id` = ?');
            $stmt->execute([$catID]);
            $count = $stmt->rowCount();
            if ($count > 0) {MSG($stmt->rowCount() . ' Delete is Done' , "success", "categories.php");
            } else{MSG("No ID " , "danger" , "categories.php", 3);}
        print "</div>";
        
        

    }  else { 
        header('Location: members.php');
        exit();
    }



include 'includes/templets/footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
