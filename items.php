<?php 
    ob_start();

    session_start();

    $pageTitle = 'Show Item';

    include 'init.php';

    
    // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
    
    // select all data depend on this id 
    
    $stmt = $con->prepare(" SELECT 
                                items.*, categories.Name AS category_name,
                                users.Username AS member_name 
                            FROM 
                                items
                            INNER JOIN 
                                categories 
                            ON 
                                categories.ID = items.Cat_ID
                            INNER JOIN 
                                users 
                            ON 
                                users.UserID = items.Member_ID
    
                            WHERE 
                                Item_ID = ?
                            AND 
                                Approve = 1
                            ");

    // EXECUTE QUERY

    $stmt->execute(array($itemid));

    // FETCH THE DATA

    $count = $stmt->rowCount();

    if($count > 0){
 
        $item = $stmt->fetch();   

        $img_file = $item['Image'];

        $images = explode(',' , $img_file); // Trasfer String data to Array data     

        $image_cap = $images[0];

        ?>

        <div class="container item_page" style="width:90%;">
            <div class="row" style="background: #FFF;">
                <div class="col-md-3">
                    <div class="box"> 
                        <div class="box_show_image">

                            <img id="show_img_item" class="img-responsive center-block img-thumbnail" src="<?php echo $imgs_admin.$image_cap ;?>" alt=""/>
                            
                        </div>
                        <div class="box_small_img">
                            <?php 
                                foreach($images as $image){
                                    echo '<div class="small_img"><img class="small_img_item" src="'.$imgs_admin . $image .'" alt="g"></div>';
                                }
                            ?>
                                       
                        </div>
                    </div>
                
                </div>
                <div class="col-md-9 item-info">
                    <h2><?php echo $item['Name'] ?></h2>
                    <p><?php echo $item['Description'] ?></p>
                    <ul class="list-unstyled">
                        
                        <li>
                            <b><?php echo $item['Price'] ?>DH</b>
                        </li>
                        <li>
                            <form action="" method="post">
                                <p for="">Quantity:</p>
                                <input type="number" name="" id="amount" min="1" value="1">    
                                <button type="submit">Add to Cart</button>
                            </form>
                            
                        </li>                        
                        <li>
                            <span>Added By : </span><a href="#"><?php echo $item['member_name'] ?></a>
                        </li>
                        <li>
                            <span>Added Date : </span><?php echo $item['Add_Date'] ?>
                        </li>
                        <li class="tags-items">
                            <i class="fa fa-user fa-fw"></i>
                            <span>Tags </span>: 
                            <?php
                            
                                $allTags = explode("," , $item['tags']);
                                if(!empty($item['tags'])){

                                    foreach($allTags as $tag){
                                    
                                        $tag = str_replace(" " , "" , $tag);

                                        $lowertag = strtolower($tag);

                                        echo "<a href='tags.php?name={$lowertag}'>" . $tag . "</a>";
                                    }
                                }
                            ?>
                        </li>
                    </ul>
                    
                </div>
            </div>

            <hr class="custom-hr">

            <?php 
            if(isset($_SESSION['user'])){ 
        
            ?>

                <!-- Start Add Comment -->
                <div class="row">
                    <div class="col-md-offset-3 add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ; ?>"method="POST">
                            <textarea name="comment" required cols="30" rows="10"></textarea>
                            <input type="submit" value="Add Comment" class="btn btn-primary">
                        </form>
                        <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                $userid = $item['Member_ID'];
                                $itemid = $item['Item_ID'];

                                // Insert Comments In DAtaBase

                                if(! empty($comment)){
                                    $stmt = $con->prepare("INSERT INTO 
                                                                    comments(comment, status, comment_date, item_id, user_id)
                                                            VALUES(:zcomment, 0, now(), :zitemid, :zuserid )");
                                    
                                    $stmt->execute(array(
                                        'zcomment' => $comment,
                                        'zitemid' => $itemid,
                                        'zuserid' => $_SESSION['uid']
                                    ));

                                    if($stmt){
                                        echo '<div class="alert alert-success">Comment Added</div>';
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>

                <!-- End Add Comment -->

                <?php 
            }else{
                echo '<div class="add-comment"><a href="login.php">Login</a> Or <a href="login.php"> Register</a> To Add Comment</div>';
            } ?>

            <hr class="custom-hr">

            <?php
                        // Select All Users Except Admin 

                        $stmt = $con->prepare("SELECT
                                                    comments.*, users.Username 
                                                FROM 
                                                    comments            
                                                INNER JOIN
                                                    users
                                                ON
                                                    users.UserID = comments.user_id
                                                WHERE 
                                                    item_id = ?
                                                AND 
                                                    status = 1
                                                ORDER BY
                                                    c_id DESC");

                        // Execute The Statement

                        $stmt->execute(array($item['Item_ID']));

                        // Assign To Variable

                        $comments = $stmt->fetchAll();

                    foreach($comments as $comment){?>
                        <div class="comment-box">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                    <img class="img-responsive img-thumbnail rounded-circle d-block m-auto " src="images.jpg" alt=""/>
                                    <?php echo $comment['Username'] ; ?>
                                </div>
                                <div class="col-sm-10">
                                    <p class="lead"><?php echo  $comment['comment'] ;?> </p>
                                </div>
                            </div>
                        </div>
                        <hr class="custom-hr">
                        <?php
                    }
                ?>

        </div>
        
        


        <?php
          
    }else{
        echo '<div class="container">';
            echo '<div class="alert alert-danger">There\'s No Such ID Or Waiting Approval</div>';
        echo '</div>';
    }

    

    

   include $tpl . 'fouter.php'; 
   ob_end_flush();

?>