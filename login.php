 <?php  
    ob_start();
    session_start();

    $pageTitle = 'Login'; 

    if(isset($_SESSION['user'])){
        header('Location: index.php'); // Redirect To Dashboard Page 
    }

    include 'init.php';

    // Chek If User Coming From HTTP Pist Request

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['login'])){

            $user = $_POST['username'];

            $pass = $_POST['password'];
 
            $hashedPass = sha1($pass);

            // Check If The User Exust In Database

            $stmt = $con->prepare("SELECT 
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username= ? 
                                    AND 
                                        Password = ? 
                                    ");

            $stmt->execute(array($user , $hashedPass));

            $get = $stmt->fetch();

            $count = $stmt->rowCount();

            // Check If Count > 0 This Mean The Database Contain Record About This Username
            if ($count > 0) {

                $_SESSION['user'] = $user; //Reguster Session Name

                $_SESSION['uid'] = $get['UserID'] ; // Register userid in Session 
                
                header('Location: index.php'); // Redirect To Dashboard Page 
                exit(); 
            } 
        }else{
            
            $formErrors = array();
            
            $username   = $_POST['username'];

            $password   = $_POST['password'];

            $password2  = $_POST['password2'];

            $email      = $_POST['email'];

            if (isset($username)){

                $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

                if (strlen($filterdUser)< 4) {
                    $formErrors[] = 'Username Must Be Larger Than 4 Characters';
                }
            }
            if (isset($password) && isset($password2)) {
                
                if(empty($password)){
                    $formErrors[] = 'Sorry Password Cant Be Empty';
                }

                if (sha1($password) !== sha1($password2)) {
                    $formErrors[] = 'Sorry Password Is Not Match';
                }
            }
            if (isset($email)) {

                $filterdUser = filter_var($email , FILTER_SANITIZE_EMAIL);

                if(filter_var($email , FILTER_VALIDATE_EMAIL) != true){
                    $formErrors[] = 'This Email Is Not Valid';
                }
            }

             
            // Check If There's No Error Proceed The User Add 

            if (empty($formErrors)) {

                // Check If User Exist in Database

                $check = checkItem("Username", "users", $username);

                if($check == 1){

                    $formErrors[] =  'Sorry This Username Is Exist';
                    

                }else{
                    
                    
                    // Insert Userinfo In Darabase

                    $stmt = $con->prepare("INSERT INTO 
                                            users(Username, Password, Email, RegStatus, Date)
                                            VALUES (:zuser, :zpass, :zemail, 0, now() )");
                    $stmt->execute(array(
                        
                        'zuser'   => $username,
                        'zpass'   => sha1($password),
                        'zemail'  => $email
                        
                    ));


                    $succesMsg = 'Congrats You Now Registerd User';
                }   
            }

        }
        

    }

?>


<div class="container login-page">
    <h1 class="text-center">
        <span data-class="login" class="selected">Login</span> | <span data-class="signup">Signup</span>
    </h1>
    <!-- Start Login form -->
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
        <div class="input_container">
            <input type="text" name="username" required class="form-control" autocomplete="off" placeholder="Type your username">
        </div>
        <div class="input_container">
            <input type="password" name="password" required class="form-control"autocomplete="new-password" placeholder="Type your password">
        </div>
        <input type="submit" name="login" value="Login" class="btn btn-primary btn-block">   
    </form>
    <!-- End Login form --> 

    <!-- Start Signup form -->
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="signup">
        <div class="input_container">
            <input type="text" pattern=".{4,8}" title="Username Must Be Between 4 and 8 Chars" name="username" required class="form-control" autocomplete="off" placeholder="Type your username">
        </div>
        <div class="input_container">
            <input type="password" minlength="4" name="password" required class="form-control"autocomplete="new-password" placeholder="Type a Complex password">
        </div>
        <div class="input_container">
            <input type="password" minlength="4" name="password2" required class="form-control"autocomplete="new-password" placeholder="Confirm password">
        </div>
        <div class="input_container">
            <input type="email" name="email" required class="form-control" placeholder="Type a Valid Email">
        </div>
                
        <input type="submit" name="signup" value="Signup" class="btn btn-success btn-block">   
    </form>
    <!-- End Signup form -->

    <div class="the-errors text-center">
        <?php
        
        // Errors Message 
        if(!empty($formErrors)){

            foreach($formErrors as $error){
                echo '<p class="error>' .  $error . '</p>';

            }

        }
        // Succes Message 
        if (isset($succesMsg)) {
            echo '<p class="succes">' . $succesMsg . '</p>' ;
        }
        ?>
    </div>
</div>



<?php  include $tpl . 'fouter.php';
    ob_end_flush();
?>
