<?php
function lang($phrase)
{
    static $lang = [
        'admin_home' => 'الرئيسية',
        'Categories' => 'الأقسام',
        'Drop' => 'عبدالرحمن',
        'Edit' => 'تعديل الملف',
        'Sett' => 'الاعدادات',
        'Logout' => 'تسجيل الخروج',
    ];
    return $lang[$phrase];
}

?>
