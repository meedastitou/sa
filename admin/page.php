<?php
// Categories => [Manage| Edit | Update | Add | Insert | Delete | Stats]

$do = '';
if(isset($_GET['do'])){

    $do = $_GET['do'];

}else {
 
    $do = 'Manage';

}


// if the pagr is main page
if($do == 'Manage'){
    // Manage Page
}elseif ($do == 'Add') {
    // Edit Page
}elseif ($do == 'Insert') {
    # code...
}else{

}