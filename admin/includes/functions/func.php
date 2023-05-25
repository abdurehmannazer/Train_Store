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
function checkDatabase($select, $from, $value)
{
    try {
        global $con;
        $statment = $con->prepare("SELECT $select FROM $from WHERE $select =  ?");
        $statment->execute([$value]);
        $count = $statment->rowCount();
        return $count;

    } catch (err) {
        print "there is wronge some thing";
    }
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