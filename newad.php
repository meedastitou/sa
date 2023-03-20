<?php 
    ob_start();

    session_start();

    $pageTitle = 'Create New Item';

    include 'init.php';

    if(isset($_SESSION['user'])){

        $getUser = $con->prepare('SELECT * FROM users WHERE Username = ?');

        $getUser->execute(array($sessionUser));

        $info = $getUser->fetch();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $formErrors = array();

            $name       = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'] , FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);  
            $status     = filter_var($_POST['status'] , FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['category'] , FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'] , FILTER_SANITIZE_STRING);

            if(strlen($name) < 4){
                $formErrors[] = 'Item Title Must Be At Least 4 Characters';
            }
            if(strlen($desc) < 10){
                $formErrors[] = 'Item Description Must Be At Least 10 Characters';
            }
            if(empty($price)){
                $formErrors[] = 'Item Price Must Be Not Empty';
            }
            if(strlen($country) < 2){
                $formErrors[] = 'Item Country Must Be At Least 2 Characters';
            } 
            if(empty($status)){
                $formErrors[] = 'Item Status Must Be Not Empty';
            }
            if(empty($category)){
                $formErrors[] = 'Item Category Must Be Not Empty';
            }

            // Check If There's No Error Proceed The Update Operation 
            if (empty($formErrors)) {

                            
                                
                // Insert Userinfo In Darabase

                $stmt = $con->prepare("INSERT INTO 
                                        Items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                                        VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember , :ztags ) ");
                $stmt->execute(array(
                    
                    'zname'       => $name,
                    'zdesc'       => $desc,
                    'zprice'      => $price,
                    'zcountry'    => $country, 
                    'zstatus'     => $status,
                    'zcat'        => $category,
                    'zmember'     => $_SESSION['uid'],
                    'ztags'       => $tags      
                
                ));



                //Echo Success Message 

                if($stmt){

                    $succesMsg = 'Item Added';
                }
                
            }
            
        }

        ?>

        <h1 class="text-center"><?php echo $pageTitle ; ?></h1>

        <div class="create block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $pageTitle ; ?></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" class="form-horizontal main-form">
                    
                                    <!-- Start Name Field -->
                                    <div class="form-group row form-group-lg">
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="name" class="form-control live" data-class=".live-name"  pattern=".{4,}" title="This Field Require At Least 4 Characters" required placeholder="Name Of The Item" />
                                        </div>
                                    </div>
                                    <!-- End Name Field -->

                                    <!-- Start Description Field -->
                                    <div class="form-group row form-group-lg">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="description" class="form-control live" data-class=".live-desc" pattern=".{10,}" title="This Field Require At Least 10 Characters" required  placeholder="Description Of The Item" />
                                        </div>
                                    </div>
                                    <!-- End Description Field -->

                                    <!-- Start Price Field -->
                                    <div class="form-group row form-group-lg">
                                        <label class="col-sm-2 control-label">Price</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="number" name="price" class="form-control live" data-class=".live-price" required  placeholder="Price Of The Item" />
                                        </div>
                                    </div>
                                    <!-- End Price Field -->

                                    <!-- Start Country Field -->
                                    <div class="form-group row form-group-lg">
                                        <label class="col-sm-2 control-label">Country</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="country" class="form-control" required  placeholder="Country Of Made The Item" />
                                        </div>
                                    </div>
                                    <!-- End Country Field -->

                                    <!-- Start Status Field -->
                                    <div class="form-group row form-group-lg"> 
                                        <label class="col-sm-2 control-label">Status </label>
                                        <div class="col-sm-10 col-md-9">
                                            <select class="form-control" required name="status">
                                                <option value="" style="color: #ced4da;" >Select Status</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Old</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End Status Field -->
                                    
                                    <!-- Start Categoreis Field -->
                                    <div class="form-group row form-group-lg">
                                        <label class="col-sm-2 control-label">Category </label>
                                        <div class="col-sm-10 col-md-9">
                                            <select class="form-control" required name="category">
                                                <option value="" style="color: #ced4da;">Choose Category</option>
                                                <?php
                                                $cats = getAllFrom('*', 'categories','','','ID');                                                
                                                foreach($cats as $cat){
                                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div> 
                                    <!-- End Categoreis Field -->

                                    <!-- Start Tags Field -->
                                    <div class="form-group row form-group-lg">
                                        <label class="col-sm-2 control-label">Tags</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
                                        </div>
                                    </div>
                                    <!-- End Tags Field -->
                                    

                                    <!-- Start submit Field -->
                                    <div class="form-group row form-group-lg">
                                        <div class="col-sm-offset-3 col-sm-10">
                                            <input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
                                        </div>
                                    </div>
                                    <!-- End submit Field -->                    

                                </form>
                            </div>
                            <div class="col-md-4">
                                
                                <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">$<span class="live-price"></span></span>
                                    <img class="img-responsive" src="images.jpg" alt=""/>
                                    <div class="caption">
                                        <h3 class="live-name">Title</h3>
                                        <p class="live-desc">Description</p>

                                    </div>
                                 </div>
            
                            </div>
                        </div>
                        <!-- Start Looping Through Errors -->

                        <?php

                            // Errors Message
                            if(!empty($formErrors)){

                                foreach($formErrors as $error){
                                    echo '<div class="alert alert-danger">'. $error . '</div>';
                                }
                            }
                            // Succes Message 
                            if (isset($succesMsg)) {
                                echo '<p class="alert alert-success">' . $succesMsg . '</p>' ;
                            }
                        
                        ?>                        

                        <!-- End Looping Through Errors -->
                    </div>
            </div>
        </div>


        

        <?php
    }else{
        header('Location: login.php');
        exit();
    }
   include $tpl . 'fouter.php'; 
   ob_end_flush();

?>