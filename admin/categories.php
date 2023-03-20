<?php

/*
=================================================
== Categories Page
=================================================
*/
    ob_start(); // Output Buffering Start

    session_start();

    $pageTitle = 'Categories';

   if(isset($_SESSION['Username'])){
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 

        
        if ($do == 'Manage'){

            $sort = 'ASC';

            $sort_array = array('ASC', 'DESC');

            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

                $sort = $_GET['sort'];

            }

            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");

            $stmt2->execute();

            $cats = $stmt2->fetchAll();?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container  categories">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-edit"></i>Manage Categories
                        <div class="ordering pull-right">
                            Ordering:
                            <a class="<?php if($sort == 'ASC'){ echo 'active'; } ?>" href="?sort=ASC">Asc</a> |
                            <a class="<?php if($sort == 'DESC'){ echo 'active'; } ?>" href="?sort=DESC">Desc</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach($cats as $cat){
                            echo '<div class="cat">';
                                echo '<div class="hidden-buttons">';
                                    echo '<a href="categories.php?do=Edit&catid='. $cat['ID'] .'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Edit</a>';
                                    echo '<a href="categories.php?do=Delete&catid='. $cat['ID'].'" class=" confirm btn btn-xs btn-danger"><i class="fa fa-close"></i>Delete</a>';                                    
                                echo '</div>';
                                echo '<h4 class="h4">' . $cat['Name'] . '</h4>';
                                echo '<div class="full-view">';
                                    echo '<p>' ; 
                                        if($cat['Description'] == ''){
                                            echo 'This Category has no description'; 
                                        }else{
                                            echo $cat['Description'];
                                        }
                                    echo '</p>'; 
                                    if($cat['Visibility'] == 1){
                                        echo '<span class="visibility">Hidden</span>';
                                    }  
                                    if($cat['Allow_Comment'] == 1){
                                        echo '<span class="commenting">Comment Disabled</span>';
                                    }
                                    if($cat['Allow_Ads'] == 1){
                                        echo '<span class="advertises">Ads Disabled</span>';
                                    }
                                echo '</div>';
                                
                            
                            
                                
                                // Get Child Categories
                                $allCatChild = getAllFrom("*", "categories","WHERE parent = {$cat['ID']}" , "" , "Ordering");
                                
                                if(!empty($allCatChild)){
                                    echo 'Child Categories ';
                                    echo '<ul>';
                                        foreach ($allCatChild as $catchild){
                                            echo '<li><a href="categories.php?do=Edit&catid='. $catchild['ID'] .'">' . $catchild['Name'] . '</a> 
                                            <a href="categories.php?do=Delete&catid='. $catchild['ID'].'" class=" confirm btn btn-xs btn-danger"><i class="fa fa-close"></i>Delete</a>                 
                                            </li>';
                                        }    
                                    echo '</ul>';
                                }
                            echo '</div>';
                            echo '<hr>';
                            
                        }
                        ?>
                    </div>

                </div>
                <a class="add-category btn btn-sm btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
            </div>

            <?php

        }elseif($do == 'Add'){
            ?>

            <h1 class="text-center">Add New Categories</h1>
            <div class="container">
                <form action="?do=Insert" method="POST" class="form-horizontal" enctype=multipart/form-data>
                    
                    <!-- Start Name Field -->
                    <div class="form-group row form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" required = "required" placeholder="Name Of The Category" autocomplete="off" />
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class="form-group row form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Descripe The Category " />
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Ordering Field -->
                    <div class="form-group row form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" placeholder="Number To Arrange The Categories "   class="form-control"/>
                        </div>
                    </div>
                    <!-- End Ordering Field -->

                    <!-- Start Ordering Field -->
                    <div class="form-group row form-group-lg">
                        <label class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="image"  class="form-control"/>
                        </div>
                    </div>
                    <!-- End Ordering Field -->

                    <!-- Start Category Type  -->

                    <div class="form-group row form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent" class="form-control">
                                <option value="0">None</option>
                                <?php
                                    $allCats = getAllFrom( '*', 'categories' , 'WHERE parent = 0 ', '', 'ID' );

                                    foreach ($allCats as $cat){
                                        echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- End Category Type -->

                    <!-- Start Visiblity Field -->
                    <div class="form-group row form-group-lg" >
                        <label class="col-sm-2 control-label" >Allow Visible</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visiblity Field -->
                    <!-- Start Commenting Field -->

                    <div class="form-group row form-group-lg" >
                        <label class="col-sm-2 control-label" >Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Field -->
                    <!-- Start Ads Field -->

                    <div class="form-group row form-group-lg" >
                        <label class="col-sm-2 control-label" >Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Field -->

                    <!-- Start submit Field -->
                    <div class="form-group row form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- End submit Field -->                    

                </form>
            </div>

            <?php
        }elseif($do == 'Insert'){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                

                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";

                // Get Variables From The Form

                $name           = $_POST['name'];
                $desc           = $_POST['description'];
                $parent         = $_POST['parent'];
                $order          = $_POST['ordering'];
                $visible        = $_POST['visibility'];
                $comment        = $_POST['commenting'];
                $ads            = $_POST['ads'];

                // Get Variables image
                $image          = $_FILES['image'];
                $image_name     = $image['name'];
                $image_size     = $image['size'];
                $image_tmp      = $image['tmp_name'];
                $image_error    = $image['error'];

                // List of allowed File Typed of upload

                $imageAllowdExtenstion = array("jpeg", "jpg", "png","gif");

                $imageExetenstion1 = explode ('.', $image_name);
                $imageExetenstion = strtolower(end($imageExetenstion1));
                
                // Validate The Form 
                $formErrors = array();

                if(!empty($image_name) && !in_array($imageExetenstion , $imageAllowdExtenstion)){
                    $formErrors[] = 'The Exetenstion is Not <strong>Allowed</strong>';
                }
                if(!empty($image_name) && $image_size > 200000){
                    $formErrors[] = 'Avatar Cant Be Large Than <strong>4MB</strong>';
                }

                if(! empty($formErrors)){
                    foreach($formErrors as $error){
                        echo '<div class="alert alert-danger">' . $error . '</div>' ;
                        
                    }
                    $theMsg = '<div class="alert alert-danger">Sorry Try again</div>';
                    redirectHome($theMsg, 'back' , 5);
                }
                if (empty($formErrors)) {
                    
                    $avatar = rand(0, 100000000000) . '_' . $image_name;
                    
                    move_uploaded_file($image_tmp, "uploads\images\\" . $avatar);

                    // Check If Category Exist in Database
                    $check = checkItem("Name", "categories", $name);
                    

                    if($check == 1){

                        $theMsg =  '<div class="alert alert-danger">Sorry This Category Is Exist</div>';
                        redirectHome($theMsg, 'back');

                    }else{
                        
                        // Insert Category info In Darabase

                        $stmt = $con->prepare("INSERT INTO 
                                                        categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads, image)
                                                        VALUES (:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads, :zimage)");
                        $stmt->execute(array(

                        'zname'         => $name,
                        'zdesc'         => $desc,
                        'zparent'       => $parent,
                        'zorder'        => $order,
                        'zvisible'      => $visible,
                        'zcomment'      => $comment,
                        'zads'          => $ads,
                        'zimage'        => $avatar
                        ));



                        //Echo Success Message 

                        echo "<div class='container'>";

                        $theMsg =  "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Inserted</div>';

                        redirectHome($theMsg , 'back');

                        echo "</div>";
                        
                    }
                
                }
                
            } else {
                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browsed this page Directly</div>';
                redirectHome($theMsg );
            }
            echo "</div>"; 



        }elseif ($do == 'Edit') {

            // Chick If Get Request catig Is Numeric & Get The Integer Value Of It
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            
            // select all data depend on this id 
            
            $stmt = $con->prepare("SELECT * FROM categories  WHERE ID = ?");

            // EXECUTE QUERY

            $stmt->execute(array($catid));

            // FETCH THE DATA

            $cat = $stmt->fetch();

            // THE ROW COUNT 

            $count = $stmt->rowCount(); 

            // IF THERE'S SUCH ID SHOW THE FORM
            
            if($count > 0){ ?>
            
                <h1 class="text-center">Edit Categories</h1>
                <div class="container">
                    <form action="?do=Update " method="POST" class="form-horizontal">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>">
                        
                        <!-- Start Name Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" required = "required" placeholder="Name Of The Category" value="<?php echo $cat['Name'];  ?>" />
                            </div>
                        </div>
                        <!-- End Name Field -->

                        <!-- Start Description Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" placeholder="Descripe The Category " value="<?php echo $cat['Description'];  ?>"/>
                            </div>
                        </div>
                        <!-- End Description Field -->

                        <!-- Start Ordering Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories " value="<?php echo $cat['Ordering'];  ?>"  />
                            </div>
                        </div>
                        <!-- End Ordering Field -->

                        <!--start Parent Field  -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Parent?</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="parent" class="form-control">
                                    <option value="0">None</option>
                                    <?php
                                        $allCats = getAllFrom( '*', 'categories' , 'WHERE parent = 0 ', '', 'ID' );

                                        foreach ($allCats as $catchild){
                                            echo '<option value="' . $catchild['ID'] . '" ';
                                            if($cat['parent'] == $catchild['ID']){echo 'selected';}
                                            echo   ' >' . $catchild['Name'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Parent Field -->

                        <!-- Start Visiblity Field -->
                        <div class="form-group row form-group-lg" >
                            <label class="col-sm-2 control-label" >Allow Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){ echo 'checked';} ?> />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1){ echo 'checked';} ?> />
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Visiblity Field -->
                        <!-- Start Commenting Field -->

                        <div class="form-group row form-group-lg" >
                            <label class="col-sm-2 control-label" >Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){ echo 'checked';} ?> />
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){ echo 'checked';} ?> />
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Commenting Field -->
                        <!-- Start Ads Field -->

                        <div class="form-group row form-group-lg" >
                            <label class="col-sm-2 control-label" >Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){ echo 'checked';} ?> />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){ echo 'checked';} ?> />
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Ads Field -->

                        <!-- Start submit Field -->
                        <div class="form-group row form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- End submit Field -->                    

                    </form>
                </div>

                <?php 

                // IF THERE'S NO SUCH ID SHOW ERROR MESSAGE

            }else {
    
                echo "<div class='container'>";

                $theMsg =  '<div class="alert alert-danger">Theres No Such ID</div>';

                redirectHome($theMsg);

                echo "</div>";

            } 



        }elseif ($do == 'Update') {


            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Get Variables From The Form

                $id         = $_POST['catid'];
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $order      = $_POST['ordering'];
                $parent     = $_POST['parent'];
                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['ads'];
                
                // Update The Database With This Info

                $stmt = $con->prepare("UPDATE 
                                            categories 
                                        SET 
                                            Name = ?, 
                                            Description = ?, 
                                            Ordering = ?, 
                                            parent =?,
                                            Visibility = ?, 
                                            Allow_Comment = ?, 
                                            Allow_Ads = ? 
                                        WHERE 
                                            ID = ? ");
                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

                //Echo Success Message 

                $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back', 4);
                
            }else{

                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browsed this page Directly</div>';
                
                redirectHome($theMsg);

            }
            echo "</div>";


        }elseif ($do == 'Delete'){


            echo "<h1 class='text-center'>Delete Category</h1>";
            echo "<div class='container'>";

                // Chick If Get Request catid Is Numeric & Get The Integer Value Of It
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('ID', 'categories', $catid);

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    

                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");

                    $stmt->bindParam(":zid", $catid);

                    $stmt->execute();
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted </div>';
                    redirectHome($theMsg , 'back');

                }else{
                    $theMsg = "<div class='alert alert-danger'> This Id Is Not Exist </div>";

                    redirectHome($theMsg);
                }
            echo '</div>';

        }

        include $tpl . 'fouter.php';

   }else{

       header('Location : index.php');

       exit();

   }    

   ob_end_flush();
?>