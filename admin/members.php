<?php

/*
=================================================
== Manage MemBers PAGE
==You Can Add | Edit | Delete | Activate Members From Here
=================================================
*/
    ob_start();
    session_start();
    $pageTitle = 'Members';
   if(isset($_SESSION['Username'])){
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 
        // Star Manage Page
        
        if ($do == 'Manage'){ // Manage Members Page 
            
            $query = '';

            if(isset($_GET['page']) && $_GET['page'] == 'Pending'){

                $query = $query = 'AND RegStatus = 0';

            }

            // Select All Users Except Admin 

            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC ");

            // Execute The Statement

            $stmt->execute();
            
            // Assign To Variable

            $rows = $stmt->fetchAll();
            ?>
            
            <h1 class="text-center">Manage MemBers</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registerd Data</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                            foreach($rows as $row ){
                                echo "<tr>";
                                echo "<td> " . $row['UserID'] . "</td>";
                                echo "<td> " . $row['Username'] . "</td>";
                                echo "<td> " . $row['Email'] . "</td>";
                                echo "<td> " . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date'] . "</td>";
                                echo "<td>
                                        <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                        <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm '><i class='fa fa-close'></i>Delete</a>";
                                    
                                        if($row['RegStatus'] == 0){
                                            echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";

                                        }
                                echo "</td>";

                                echo "</tr>";
                            }
                        ?>
                        
                    </table>
                </div>

                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Members</a>
            </div>

            <?php
        }elseif($do == 'Add'){ // Add Members Page
            ?>

            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form action="?do=Insert" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" required = "required" placeholder="Username To Login Itnto Shop" autocomplete="off" />
                        </div>
                    </div>
                    <!-- End Username Field -->

                    <!-- Start Pasword Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Pasword</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="password" name="password" class="password form-control" required = "required" placeholder="Password Must Be Hard And Complex" autocomplete="new-password" />
                            <i class="show-pass fa fa-eye fa-1x"></i>
                        </div>
                    </div>
                    <!-- End Pasword Field -->

                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" placeholder="Email Must Be Valid" required = "required"  class="form-control"/>
                        </div>
                    </div>
                    <!-- End Email Field -->

                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label" >Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" placeholder="Full Name Appear In Your Profile Page"  required = "required" class="form-control"/>
                        </div>
                    </div>
                    <!-- End Full Name Field -->

                    <!-- Start Avatar Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label" >User Avatar</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="avatar"  required = "required" class="form-control"/>
                        </div>
                    </div>
                    <!-- End Avatar Field -->

                    <!-- Start submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- End submit Field -->                    

                </form>
            </div>
            <?php
        }elseif($do == 'Insert'){ // Insert Member Page
            
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                

                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";

                // Get Variables From The Form

                
                $user = $_POST['username'];
                $pass = $_POST['password'];
                $email = $_POST['email'];
                $name = $_POST['full'];

                $hashPass = sha1($_POST['password']);

                // Avatar Variables
                $avatar         = $_FILES['avatar'];
                
                $avatarName     = $_FILES['avatar']['name'];
                $avatarSize     = $_FILES['avatar']['size'];
                $avatarTmp      = $_FILES['avatar']['tmp_name'];
                $avatarType     = $_FILES['avatar']['type'];

                // List of allowed File Typed of Apload
                $avatarAllowdExtenstion = array("jpeg", "jpg", "png","gif");

                $avatarExetenstion1 = explode ('.', $avatarName);
                $avatarExetenstion = strtolower(end($avatarExetenstion1));
                
                // Validate The Form 

                $formErrors = array();
                

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username Cant Be More Than <strong> 20 Characters</strong>';
                }

                if (empty($user)) {
                    $formErrors[] = 'Username Cant Be  <strong> Empty</strong>';
                }
                if (empty($pass)) {
                    $formErrors[] = 'Password Cant Be  <strong> Empty</strong>';
                }

                if (empty($email)) {
                    $formErrors[] = 'Email Cant Be <strong> Empty</strong>';
                }

                if (empty($name)) {
                    $formErrors[] = 'Full Name Cant Be  <strong> Empty</strong>';
                }
                if(! empty($avatarName) && ! in_array($avatarExetenstion , $avatarAllowdExtenstion)){
                    $formErrors[] = 'The Exetenstion is Not <strong>Allowed</strong>';
                }
                if(empty($avatarName)){
                    $formErrors[] = 'Avatar Cant Be <strong>Empty</strong';
                }
                if($avatarSize > 4194304){
                    $formErrors[] = 'Avatar Cant Be Large Than <strong>4MB</strong>';
                }
                    // Loop Into Errors Array And Echo It

                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>' ;
                }
                
                // Check If There's No Error Proceed The Update Operation 
                if (empty($formErrors)) {
                    
                    $avatar = rand(0, 100000000000) . '_' . $avatarName;
                    
                    move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
                    
                    // Check If User Exist in Database

                    $check = checkItem("Username", "users", $user);

                    if($check == 1){

                        $theMsg =  '<div class="alert alert-danger">Sorry This Username Is Exist</div>';
                        redirectHome($theMsg, 'back');

                    }else{

                        
                        // Insert Userinfo In Darabase

                        $stmt = $con->prepare("INSERT INTO 
                                                users(Username, Password, Email, FullName, RegStatus, Date, avatar)
                                                VALUES (:zuser, :zpass, :zemail, :zname, 1, now(), :zavatar ) ");
                        $stmt->execute(array(
                            
                            'zuser'   => $user,
                            'zpass'   => $hashPass,
                            'zemail'  => $email,
                            'zname'   => $name,
                            'zavatar' => $avatar
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
                redirectHome($theMsg,);
            }
            echo "</div>";       

        }elseif ($do == 'Edit'){ // Edit Page 

            // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            
            // select all data depend on this id 
            
            $stmt = $con->prepare("SELECT * FROM users  WHERE UserID = ? LIMIT 1");

            // EXECUTE QUERY

            $stmt->execute(array($userid));

            // FETCH THE DATA

            $row = $stmt->fetch();

            // THE ROW COUNT 

            $count = $stmt->rowCount(); 

            // IF THERE'S SUCH ID SHOW THE FORM
            
            if($count > 0){ ?>
                
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form action="?do=Update" method="POST" class="form-horizontal">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2">Username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" required = "required" autocomplete="off" />
                            </div>
                        </div>
                        <!-- End Username Field -->

                        <!-- Start Pasword Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2">Pasword</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                <input type="password" name="newpassword" class="form-control" placeholder="Leave Lank If You Dont Want To Change" autocomplete="new-password" />
                            </div>
                        </div>
                        <!-- End Pasword Field -->

                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2">Email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" name="email" value="<?php echo $row['Email'] ?>" required = "required"  class="form-control"/>
                            </div>
                        </div>
                        <!-- End Email Field -->

                        <!-- Start Full Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3" >Full Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="full" value="<?php echo $row['FullName'] ?>" required = "required" class="form-control"/>
                            </div>
                        </div>
                        <!-- End Full Name Field -->

                        <!-- Start submit Field -->
                        <div class="form-group form-group-lg">
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

                redirectHome($theMsg,);

                echo "</div>";

            }


        }elseif ($do == 'Update') { // UPADATE PAGE
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Get Variables From The Form

                $id = $_POST['userid'];
                $user = $_POST['username'];
                $email = $_POST['email'];
                $name = $_POST['full'];

                // Password Trick 

                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                // Validate The Form 

                $formErrors = array();

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username Cant Be More Than <strong> 20 Characters</strong>';
                }

                if (empty($user)) {
                    $formErrors[] = 'Username Cant Be  <strong> Empty</strong>';
                }

                if (empty($email)) {
                    $formErrors[] = 'Email Cant Be <strong> Empty</strong>';
                }

                if (empty($name)) {
                    $formErrors[] = 'Full Name Cant Be  <strong> Empty</strong>';
                }

                    // Loop Into Errors Array And Echo It

                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>' ;
                }

                // Check If There's No Error Proceed The Update Operation 
                if (empty($formErrors)) {

                    $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");

                    $stmt2->execute(array($user, $id));

                    $count = $stmt2->rowCount();

                    if($count == 1){
                        $theMsg = '<div class="alert alert-danger">Sorry This User Name Is Exist</div>';

                        redirectHome($theMsg, 'back');

                    }else{
                          
                        // Update The Database With This Info

                        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ? ");
                        
                        $stmt->execute(array($user, $email, $name, $pass, $id));

                        //Echo Success Message 

                        $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>';
                        redirectHome($theMsg, 'back');
                    }

                }

            } else {

                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browsed this page Directly</div>';
                
                redirectHome($theMsg);

            }
            echo "</div>";
        }elseif($do == 'Delete'){ // Delete Members Page

            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";

                // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('userid', 'users', $userid);

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    

                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

                    $stmt->bindParam(":zuser", $userid);

                    $stmt->execute();
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted </div>';
                    redirectHome($theMsg ,'back');

                }else{
                    $theMsg = "<div class='alert alert-danger'> This Id Is Not Exist </div>";

                    redirectHome($theMsg);
                }
            echo '</div>';
            
        } elseif($do == 'Activate'){ // Activate Page
            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";

                // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('userid', 'users', $userid);

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

                    $stmt->execute(array($userid));
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated  </div>';
                    redirectHome($theMsg);

                }else{
                    $theMsg = "<div class='alert alert-danger'> This Id Is Not Exist </div>";

                    redirectHome($theMsg);
                }
            echo '</div>';
        }

        include $tpl . 'fouter.php';

    } else{
        header('Location: index.php');
        exit();
    }
    ob_end_flush();