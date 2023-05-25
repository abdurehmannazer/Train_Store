<?php

// title name in header
function printTitle()
{
    global $title;
    isset($title) ? print $title : print 'Defult Title';
}
// errorMSG
//  Messagies [err, success , info ]And back to home page from Members Page
function MSG($err, $type = 'danger', $url, $sec = 3)
{
    print "<div class='alert alert-$type'>$err</div>";
    print "<div class='alert alert-info'>You well redirect in $sec secends</div>";
    header("refresh: $sec; url=$url");
    exit();
}
// Check Database
function checkDatabase($select, $from, $value) {
        global $con;
        $statment = $con->prepare("SELECT $select FROM $from WHERE $select =  ?");
        $statment->execute([$value]);
        $count = $statment->rowCount();
        return $count;
}


//check count of data
function countData($column, $table)
{
    global $con;
    $stm2 = $con->prepare("SELECT COUNT($column) FROM $table");
    $stm2->execute();
    return $stm2->fetchColumn();
}

//get latest database
function latestData($select, $table, $ORDER, $limit = 5)
{
    global $con;
    $stmData = $con->prepare(
        "SELECT $select FROM $table ORDER BY $ORDER DESC LIMIT $limit "
    );
    $stmData->execute();
    $rows = $stmData->fetchAll();
    return $rows;
}

//get name 
function getName($column, $table, $Name_id , $id)
{
    global $con;
    $stm2 = $con->prepare("SELECT $column FROM $table WHERE $Name_id = $id");
    $stm2->execute();
    return $stm2->fetch()["$column"];
}   










//--------------------- functions for General User 

// get categories from database 
function get_cete_arr() {
    global $con;
    $get_cat = $con->prepare("SELECT * FROM categories ");
    $get_cat->execute();
    $cats = $get_cat->fetchAll();
    return $cats;
}

// get items by Category 
function get_items_By_cate($cat_id , $approve = null ) {
    global $con;
    $ap = "";
    if (!$approve == null) {
        $ap = 'AND approve = 1';
    }

    $stm = $con->prepare("SELECT * FROM items WHERE cat_id = ? $ap   ORDER BY id  DESC");
    $stm->execute(array($cat_id));
    $items = $stm->fetchAll();
    return $items;

}

//check user Status
function check_status($user) {
    global $con;
    $stm = $con->prepare("SELECT UserName , RegStatus FROM users WHERE UserName = ? AND  RegStatus = ?");
    $stm->execute(array($user, 1));

    $count = $stm->rowCount();
    return $count;
}
