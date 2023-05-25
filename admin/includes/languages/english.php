<?php
function lang($phrase)
{
    static $lang = [
        //na
        'admin_home' => 'Home',
        'Categories' => 'Categories',
        'Items' => 'Items',
        'Members' => 'Members',
        'Statics' => 'Statics',
        'Logs' => 'Logs',
        'Drop' => 'Abdurehman',
        'Edit' => 'Edit Profile',
        'Sett' => 'Setting',
        'Logout' => 'Logout',
        'comments' => 'comments',
    ];
    return $lang[$phrase];
}

?>
