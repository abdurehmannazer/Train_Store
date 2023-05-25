<?php
//for manage intire pages

$do = '';

if (isset($_GET['dash'])) {
    $do = $_GET['dash'];
    print 'Hi you are come from Dash';
} elseif ($_GET['index']) {
    $do = $_GET['index'];
    print 'Hi you are come from index';
} else {
    $do = 'No page found';
    print 'not found page';
}

?>
