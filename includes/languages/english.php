<?php

function lang($phrase){
    static $lang = array(
        // HomePage

        // Navbar Links
        'Home'             => 'Home',
        'Categories'       => 'Categories',
        'EditPro'          => 'Edit Profile',
        'Settings'         => 'Settings',
        'Logout'           => 'Logout',
        'Items'            => 'Items',
        'Members'          => 'Members',
        'Statistic'        => 'Statistic',
        'Comments'         => 'Comments',
        'Logs'             => 'Logs'
        
    );
    return $lang[$phrase];
}




?>