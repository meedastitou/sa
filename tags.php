<?php     ob_start();
session_start();
  include 'init.php';?>
   
<div class="container">
    <?php 
    if(isset($_GET['name'])){
        $tag =  $_GET['name'];
        echo "<h1 class='text-center'>" . $tag . "</h1>";
    }
    ?>
    <div class="row">
        <?php
        

            // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
               
            if(isset($_GET['name'])){

                $tag =  $_GET['name'];
                
                
                
                
                $tagItems = getAllFrom("*", "items", "WHERE tags like '%$tag%'" , "AND Approve = 1" , "Item_ID", "DESC" );
                
                foreach($tagItems as $item){

                    echo '<div class="col-sm-6 col-md-3">';

                        echo '<div class="thumbnail item-box">';
                            echo '<span class="price-tag">'. $item['Price'] . '</span>';
                            echo '<img class="img-responsive" src="images.jpg" alt=""/>';
                            echo '<div class="caption">';
                                echo '<h3><a href="items.php?itemid='. $item['Item_ID'] . '">'. $item['Name'] .'</a></h3>';
                                echo '<p>' . $item['Description'] .'</p>';
                                echo '<div class="date">'. $item['Add_Date'] . '</div>' ;

                            echo '</div>';
                        echo '</div>';

                    echo '</div>';


                }                
            } else {
                echo '<div class="container">';
                    echo '<p class="alert alert-danger">You Must Add Page ID</p>';
                echo '</div>';
            }
            

        ?>

    </div>

</div>

<?php  include $tpl . 'fouter.php'; 

    ob_end_flush();
?>
