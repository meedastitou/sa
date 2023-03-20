<?php
include 'admin/connect.php'; // Conect With Database




$sessionUser ='';
if(isset($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
}

        // Routes

$tpl        = 'includes/templates/';       // Template Directory
$lang       = 'includes/languages/';       // Language Directory
$func       = 'includes/fonctions/';       // Functions Directory
$imgs       = 'includes/images/';          // Images Directory   
$imgs_admin = 'admin/uploads/images/';     // Images Admin Directory
$css        = 'layout/css/';               // Css Directory
$js         = 'layout/js/';                // Js Directory


        // Include The Important Files
include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'haeder.php'; 



?>