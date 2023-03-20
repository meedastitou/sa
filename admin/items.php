<?php

/*
=================================================
== Items Page
=================================================
*/
    ob_start();
    session_start();
    $pageTitle = 'Items';
    if(isset($_SESSION['Username'])){
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 

        
        if ($do == 'Manage'){

            

            $stmt = $con->prepare("SELECT items.*, categories.Name AS category_name, users.Username AS member_name FROM items
                                    INNER JOIN categories ON categories.ID = items.Cat_ID
                                    INNER JOIN users ON users.UserID = items.Member_ID
                                    ORDER BY Item_ID DESC ");

            // Execute The Statement

            $stmt->execute();
            
            // Assign To Variable

            $items = $stmt->fetchAll();
            ?>
            
            <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Member</td>
                            <td>Category</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                            foreach($items as $item ){
                                echo "<tr>";
                                echo "<td> " . $item['Item_ID'] . "</td>";
                                echo "<td class='name_item'> " . $item['Name'] . "</td>";
                                echo "<td class='desc_item'> " . $item['Description'] . "</td>";
                                echo "<td> " . $item['Price'] . "</td>";
                                echo "<td>" . $item['Add_Date'] . "</td>";
                                echo "<td>" . $item['member_name'] . "</td>";
                                echo "<td>" . $item['category_name'] . "</td>";
                                echo "<td>
                                    <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                    <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm '><i class='fa fa-close'></i>Delete</a>";
                                    
                                    if($item['Approve'] == 0){
                                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>  Approve</a>";

                                    }
                                echo "</td>";

                                echo "</tr>";
                            }
                        ?>
                        
                    </table>
                </div>

                <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
            </div>

            <?php                            
        }elseif($do == 'Add'){
            ?>


            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <div class="row">
                    
                    <div class="col-9">
                        <form action="?do=Insert" method="POST" class="form-horizontal" enctype=multipart/form-data>
                        
                            <!-- Start Name Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="name" required class="form-control"  placeholder="Name Of The Item" />
                                </div>
                            </div>
                            <!-- End Name Field -->

                            <!-- Start Description Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="description" required class="form-control"  placeholder="Description Of The Item" />
                                </div>
                            </div>
                            <!-- End Description Field -->

                            <!-- Start Price Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="price" required class="form-control" placeholder="Price Of The Item" />
                                </div>
                            </div>
                            <!-- End Price Field -->

                            <!-- Start Country Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="country" class="form-control" placeholder="Country Of Made The Item" required/>
                                </div>
                            </div>
                            <!-- End Country Field -->

                            <!-- Start Status Field -->
                            <div class="form-group row form-group-lg"> 
                                <label class="col-sm-2 control-label">Status </label>
                                <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="status">
                                        <option value="0" style="color: #ced4da;" >Select Status</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Status Field -->
                            
                            <!-- Start Status Field -->
                            <!-- <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Ratting </label>
                                <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="ratting">
                                        <option value="0" style="color: #ced4da;">Select Ratting</option>
                                        <option value="1">*</option>
                                        <option value="2">**</option>
                                        <option value="3">***</option>
                                        <option value="4">****</option>
                                        <option value="5">*****</option>
                                    </select>
                                </div>
                            </div> -->
                            <!-- End Status Field -->

                            <!-- Start Members Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Member </label>
                                <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="member">
                                        <option value="0" style="color: #ced4da;">Choose Member</option>
                                        <?php
                                        $allmembers = getAllFrom("*" , "users" ,"", "", "UserID" , "");
                                        foreach($allmembers as $user){
                                            echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Ebd Members Field -->

                            <!-- Start Categoreis Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Category </label>
                                <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="category">
                                        <option value="0" style="color: #ced4da;">Choose Category</option>
                                        <?php

                                        $allcategories = getAllFrom("*" , "categories" , "WHERE parent = 0", "", "ID" , "");
                                        
                                        foreach($allcategories as $cat){
                                            
                                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";

                                            $childcat1 = getAllFrom("*" , "categories" , "WHERE parent  = {$cat['ID']}", "", "ID","" );
                                            foreach($childcat1 as $child1){
                                                echo "<option value='" . $child1['ID'] . "'>... " . $child1['Name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Ebd Categoreis Field -->

                            <!-- Start TAgs Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
                                </div>
                            </div>
                            <!-- End TAgs Field -->
                            <!-- Start Avatar Field -->
                            <div class="form-group row form-group-lg">
                                <label class="col-sm-2 control-label" >Item Avatar</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="file" multiple="multiple" name="images_item[]"  required class="form-control"/>
                                </div>
                            </div>
                            <!-- End Avatar Field -->
                            <!-- Start submit Field -->
                            <div class="form-group row form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
                                </div>
                            </div>
                            <!-- End submit Field -->                    

                        </form> 
                    </div>
                </div>
                
                
            </div>
            <?php

        }elseif($do == 'Insert'){


            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                

                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";

                // Get Variables From The Form 

                $name           = $_POST['name'];
                $desc           = $_POST['description'];
                $price          = $_POST['price'];
                $country        = $_POST['country'];
                $status         = $_POST['status'];
                $member         = $_POST['member'];
                $category       = $_POST['category'];
                $tags           = $_POST['tags'];

                // image Variables
                $images_item = $_FILES['images_item'];
                
                $imageName      = $images_item['name'];
                $imageSize      = $images_item['size'];
                $image_tmp      = $images_item['tmp_name'];
                $image_err      = $images_item['error'];
             
                // List of Allowed File Typed of Apload
                $imageAllowdExtenstion = array("jpeg", "jpg", "png","gif","jfif");

                // Validate The Form 
                $formErrors = array(); 
                
                
                if (empty($name)) {
                    $formErrors[] = 'Name Cant Be  <strong> Empty</strong>';
                }
                if (empty($descripton)) {
                    $formErrors[] = 'Description Cant Be  <strong> Empty</strong>';
                }
                
                if (empty($price)) {
                    $formErrors[] = 'Price Cant Be <strong> Empty</strong>';
                }
                
                if (empty($country)) {
                    $formErrors[] = 'Country Cant Be  <strong> Empty</strong>';
                }
                
                if ( $status == 0) {
                    $formErrors[] = 'You Must Choose The  <strong> Status</strong>';
                }
                if ( $member == 0) {
                    $formErrors[] = 'You Must Choose The  <strong> Member</strong>';
                }
                if ( $category == 0) {
                    $formErrors[] = 'You Must Choose The  <strong> Category</strong>';
                }

                // Check if Fille is Uploaded
                if($image_err[0] == 4){ // if Not image Uploaded

                    echo '<div class="alert alert-danger">No images <strong>Uploaded</strong></div>' ;

                }else{ // Theres image Uploaded

                    // Count Files Uploaded
                    $images_count = count($imageName);

                    for($i=0 ; $i < $images_count ; $i++){

                        $imageExtenstion0 = explode('.', $imageName[$i]);
                        $imageExtenstion[$i] = strtolower(end($imageExtenstion0));
                        
                        if(!in_array($imageExtenstion[$i] , $imageAllowdExtenstion)){
                            $formErrors[] = "The Extenstion ". ($i + 1) . " is Not <strong>Allowed</strong>";
                        }
                        if($imageSize[$i] > 900000){
                            $formErrors[] = "image ". ($i + 1) . " Cant Be Large Than <strong>4MB</strong>";
                        }
                    }
                }
                
                    // Loop Into Errors Array And Echo It

                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>' ;
                }

                // Check If There's No Error Proceed The Update Operation 
                if (empty($formErrors)) {

                    for($i=0 ; $i < $images_count ; $i++){

                        $imageNewName[$i] = rand(0 , 100000000000000) . '_' . $imageName[$i];
        
        
                        // if empty errors upload this Image
                        move_uploaded_file($image_tmp[$i], 'uploads\images\\'. $imageNewName[$i]);
        
                        $all_images[] = $imageNewName[$i];
                    }

                    $img_field = implode(',' , $all_images);
                
                    // Insert Userinfo In Darabase

                    $stmt = $con->prepare("INSERT INTO 
                                            Items(Name, Description, Price, Country_Made, Image, Status, Add_Date, Cat_ID, Member_ID, tags)
                                            VALUES (:zname, :zdesc, :zprice, :zcountry, :zimage, :zstatus, now(), :zcat, :zmember, :ztags ) ");
                    $stmt->execute(array(
                        
                        'zname'       => $name,
                        'zdesc'       => $desc,
                        'zprice'      => $price,
                        'zcountry'    => $country, 
                        'zimage'      => $img_field,
                        'zstatus'     => $status,
                        'zcat'        => $category,
                        'zmember'     => $member,
                        'ztags'       => $tags
                    
                    ));



                    //Echo Success Message 

                    echo "<div class='container'>";

                    $theMsg =  "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Inserted</div>';

                    redirectHome($theMsg , 'back');

                    echo "</div>";
                    
                }

            } else {
                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browsed this page Directly</div>';
                redirectHome($theMsg);
            }
            echo "</div>";       



        }elseif ($do == 'Edit') { 

            
            // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
            // select all data depend on this id 
            
            $stmt = $con->prepare("SELECT * FROM items  WHERE Item_ID = ?");

            // EXECUTE QUERY

            $stmt->execute(array($itemid));

            // FETCH THE DATA

            $item = $stmt->fetch();

            // THE ROW COUNT 

            $count = $stmt->rowCount(); 


            

            // IF THERE'S SUCH ID SHOW THE FORM
            
            if($count > 0){ ?>

                

                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form action="?do=Update" method="POST" class="form-horizontal">
                        
                        <input type="hidden" name="itemid" value="<?php echo $itemid ; ?>"/>

                        <!-- Start Name Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" value="<?php echo  $item['Name'] ; ?>"  placeholder="Name Of The Item" />
                            </div>
                        </div>
                        <!-- End Name Field -->

                        <!-- Start Description Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" value="<?php echo $item['Description'] ;?>"  placeholder="Description Of The Item" />
                            </div>
                        </div>
                        <!-- End Description Field -->

                        <!-- Start Price Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="price" class="form-control" value="<?php echo $item['Price'];?>" placeholder="Price Of The Item" />
                            </div>
                        </div>
                        <!-- End Price Field -->

                        <!-- Start Country Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="country" class="form-control" value="<?php echo $item['Country_made'];?> " placeholder="Country Of Made The Item" />
                            </div>
                        </div>
                        <!-- End Country Field -->

                        <!-- Start Status Field -->
                        <div class="form-group row form-group-lg"> 
                            <label class="col-sm-2 control-label">Status </label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="status">
                                     <option value="1"<?php if($item['Status'] == 1){echo 'selected';}?>>New</option>
                                    <option value="2"<?php if($item['Status'] == 2){echo 'selected';}?>>Like New</option>
                                    <option value="3"<?php if($item['Status'] == 3){echo 'selected';}?>>Used</option>
                                    <option value="4"<?php if($item['Status'] == 4){echo 'selected';}?>>Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Field -->
                        
                        <!-- Start Status Field -->
                        <!-- <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Ratting </label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="ratting">
                                    <option value="0" style="color: #ced4da;">Select Ratting</option>
                                    <option value="1">*</option>
                                    <option value="2">**</option>
                                    <option value="3">***</option>
                                    <option value="4">****</option>
                                    <option value="5">*****</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- End Status Field -->

                        <!-- Start Members Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Member </label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="member">
                                    <?php
                                    $users = getAllFrom("*" , "users" , "", "" , "UserID" , "");                                    

                                    foreach($users as $user){
                                        echo "<option value='" . $user['UserID'] . "'"; 
                                        if($item['Member_ID'] == $user['UserID']){echo 'selected';} 
                                        echo ">" . $user['Username'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Ebd Members Field -->

                        <!-- Start Categoreis Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Category </label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="category">
                                    <?php

                                    $cats = getAllFrom("*" , "categories" , "", "", "ID" , "");
                                    
                                    foreach($cats as $cat){
                                        echo "<option value='" . $cat['ID'] . "'"; 
                                        if($item['Cat_ID'] == $cat['ID']){echo 'selected';} 
                                        echo ">" . $cat['Name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Ebd Categoreis Field -->

                        <!-- Start TAgs Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2 control-label">Tags</label>
                            <div class="col-sm-10 col-md-6">
                                <input 
                                type="text" 
                                name="tags" 
                                class="form-control" 
                                placeholder="Separate Tags With Comma (,)"
                                value="<?php echo $item['tags'];?> " />
                            </div>
                        </div>
                        <!-- End TAgs Field -->
                    
                        <!-- Start submit Field -->
                        <div class="form-group row form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Edit Item" class="btn btn-primary btn-sm"/>
                            </div>
                        </div>
                        <!-- End submit Field -->                    

                    </form>
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
                                            WHERE item_id = ?");

                    // Execute The Statement

                    $stmt->execute(array($itemid));

                    // Assign To Variable

                    $rows = $stmt->fetchAll();
                    
                    if(!empty($rows)){

                        
                        ?>

                        <h1 class="text-center">Manage [<?php echo  $item['Name'] ; ?>] Comments</h1>
                        
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                                <tr>

                                    <td>Comment</td>
                                    <td>User Name</td>
                                    <td>Added Data</td>
                                    <td>Control</td>
                                </tr>
                                <?php 
                                    foreach($rows as $row ){
                                        echo "<tr>";
                                            echo "<td> " . $row['comment'] . "</td>";
                                            echo "<td> " . $row['Username'] . "</td>";
                                            echo "<td>" . $row['comment_date'] . "</td>";
                                            echo "<td>
                                                    <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                                    <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm '><i class='fa fa-close'></i>Delete</a>";
                                                
                                                    if($row['status'] == 0){
                                                        echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";

                                                    }
                                            echo "</td>";

                                        echo "</tr>";
                                    }
                                ?>
                                
                            </table>
                        </div>
                        <?php 
                    } 
                    ?>
                </div>

                <?php 

                // IF THERE'S NO SUCH ID SHOW ERROR MESSAGE

            }else {
 
                echo "<div class='container'>";

                $theMsg =  '<div class="alert alert-danger">Theres No Such ID</div>';

                redirectHome($theMsg,);

                echo "</div>";

            }

        }elseif ($do == 'Update') {

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Get Variables From The Form

                $id          = $_POST['itemid'];
                $name        = $_POST['name'];
                $desc        = $_POST['description'];
                $price       = $_POST['price'];
                $country     = $_POST['country'];
                $status      = $_POST['status'];
                $member      = $_POST['member'];
                $cat         = $_POST['category'];
                $tags        = $_POST['tags'];

                // Validate The Form 

                $formErrors = array(); 
                
                if (empty($name)) {
                    $formErrors[] = 'Name Cant Be  <strong> Empty</strong>';
                }
                if (empty($desc)) {
                    $formErrors[] = 'Description Cant Be  <strong> Empty</strong>';
                }
                
                if (empty($price)) {
                    $formErrors[] = 'Price Cant Be <strong> Empty</strong>';
                }
                
                if (empty($country)) {
                    $formErrors[] = 'Country Cant Be  <strong> Empty</strong>';
                }
                
                if ( $status == 0) {
                    $formErrors[] = 'You Must Choose The  <strong> Status</strong>';
                }
                if ( $member == 0) {
                    $formErrors[] = 'You Must Choose The  <strong> Member</strong>';
                }
                if ( $cat == 0) {
                    $formErrors[] = 'You Must Choose The  <strong> Category</strong>';
                }
                
                    // Loop Into Errors Array And Echo It

                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>' ;
                }

                // Check If There's No Error Proceed The Update Operation 
                if (empty($formErrors)) {

                    // Update The Database With This Info

                    $stmt = $con->prepare("UPDATE 
                                            items 
                                        SET     
                                            Name = ?,
                                            Description = ?, 
                                            Price = ?, 
                                            Country_Made = ?,
                                            Status = ?,
                                            Member_ID = ?,
                                            Cat_ID = ?,
                                            tags = ?
                                        WHERE 
                                            Item_ID = ? ");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $member, $cat, $tags, $id));

                    //Echo Success Message 

                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back',4 );
                }

            } else {

                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browsed this page Directly</div>';
                
                redirectHome($theMsg);

            }
            echo "</div>";
        


        }elseif ($do == 'Delete'){

            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";

                // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('Item_ID', 'items', $itemid );

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    

                    $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitemid");

                    $stmt->bindParam(":zitemid", $itemid);

                    $stmt->execute();
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted </div>';
                    redirectHome($theMsg, 'Back');

                }else{
                    $theMsg = "<div class='alert alert-danger'> This Id Is Not Exist </div>";

                    redirectHome($theMsg);
                }
            echo '</div>';


        }elseif ($do == 'Approve'){

            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

                // Chick If Get Request ItemID Is Numeric & Get The Integer Value Of It
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('Item_ID', 'items', $itemid);

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

                    $stmt->execute(array($itemid));
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated  </div>';
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