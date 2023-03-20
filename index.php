<?php 
   ob_start();

   session_start();

   $pageTitle = 'Homepage';

   include 'init.php';?>
   
   
   <div class="container">
        <div class="grid-header">
            <aside>
                <?php
                    $allcat = getAllFrom('*', 'categories', '', '', 'ID');
                    echo "<ul>";
                    foreach($allcat as $cat){
                        echo "<li><a href='categories.php?pageid=". $cat['ID'] ."'>" . $cat['Name'] . "</a></li>";                        
                    }
                    echo "</ul>";
                ?>
            </aside>
            <?php
             /* 
            =================================
            ==        Slider Images        ==
            =================================
            */
            ?>
            <div class="slider">

                <a href="" id="link_cat"><img src="" id="slider_show" alt=""></a>

                <?php
                    /* 
                    ====================================
                    ==  Fetch Pictures From DataBase  ==
                    ====================================
                    */
                    $slider_images = array();
                    
                    foreach($allcat as $image_cat){
                        if(! empty($image_cat['image'])){
                            $link_cat[] = $image_cat['ID'];
                            $slider_images[] = $image_cat['image'];
                        }
                        
                    }
                ?>
                
            </div>     
            <div class="sponsor">
                
            </div>       
        </div>
        <?php
        /* 
        ====================================
        ==          Show Items           ==
        ====================================
        */
        ?>
        <div class="last_items">

            <!-- Title -->
            <b id="title_last_items">Last items</b>
        
            <div class="row items" >
                
                <?php
                    $item = getAllFrom('*', 'items' , 'WHERE Approve = 1' ,'', 'Item_ID');

                    for ($i=0; $i <5 ; $i++) { 

                        $img_file = $item[$i]['Image'];

                        $image_cap0 = explode(',' , $img_file); // Trasfer String data to Array data

                        $image_cap = $image_cap0[0];
                        
                        echo '<div class="items-home"><a href="items.php?itemid='. $item[$i]['Item_ID'] . '">';

                            echo '<span class="price-tag">$'. $item[$i]['Price'] . '</span>';

                            echo '<img class="img-responsive" src="admin/uploads/images/'. $image_cap .'" alt=""/>';
                            
                        echo '</a></div>';
                        
                    }
                    

                ?>

            </div>
            <div class="all_prudects" id="all_prudects">

                <b id="title_all_pudects">All Prudects</b>
                <div class="row">
                    <?php
                        foreach($item as $prudect){
                            $img_file = $prudect['Image'];

                            $image_cap0 = explode(',' , $img_file); // Trasfer String data to Array data

                            $image_cap = $image_cap0[0];

                            if(!empty($image_cap)){
                                ?>
                                    <div class="col-md-2" style=" padding: 5px;">
                                        <div class="item_box_index">

                                            <a href="items.php?itemid=<?php echo $prudect['Item_ID'] ?>">

                                                <div class="image_box">
                                                    <img src="admin/uploads/images/<?php echo $image_cap ?>" alt="">
                                                </div>

                                                <div class="info_item">

                                                    <h6 class="name_item"><?php echo $prudect['Name'] ?></h6>

                                                    <span class="price_item"><?php echo $prudect['Price'] ?>$</span>
                                                
                                                </div>

                                            </a>

                                        </div>
                                        
                                    </div>
                                <?php 
                            }
                            
                        }
                    ?>
                </div>
            </div>
            
        </div>
    </div>




    <?php
    /*
    ====================================
    ==    Part Of Script index.php    ==
    ====================================
    */
    ?>
    <script>
        //
        // Start Create a SliderShow 
        //
        
        var slider_image = <?php echo json_encode($slider_images);?> ;  // Fetch Images Data
        var link_show = <?php echo json_encode($link_cat); ?>;          // Fetch links 
        
        var i = 0;
        function slider_image_show(){

            document.getElementById("slider_show").setAttribute("src", "admin/uploads/images/" + slider_image[i]);
            document.getElementById("link_cat").setAttribute("href", "categories.php?pageid=" + link_show[i]);
            if(i < slider_image.length - 1){
                i++;
            }else{
                i = 0 ;
            }
            
        }
        setInterval(slider_image_show, 4000);
        window.onload = slider_image_show();   
        //
        // End Create a SliderShow
        //
    </script> 
   <?php
   
   include $tpl . 'fouter.php'; 
   ob_end_flush();

?>