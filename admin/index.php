<?php 
   session_start();
   $noNavbar = ''; 
   $pageTitle = 'Login';
   if(isset($_SESSION['Username'])){
      header('Location: dashboard.php'); // Redirect To Dashboard Page 
   }
   include 'init.php';
   // include $tpl . 'haeder.php'; 
   // include 'includes/languages/english.php';

   // Chek If User Coming From HTTP Pist Request

   if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $username = $_POST['user'];
      $password = $_POST['pass'];
      $hashedPass = sha1($password);

      // Check If The User Exust In Database
         
      $stmt = $con->prepare("SELECT 
                                    UserID, Username, Password 
                              FROM 
                                    users 
                              WHERE 
                                    Username= ? 
                              AND 
                                    Password = ? 
                              AND 
                                    GroupID = 1
                              LIMIT 1");
      $stmt->execute(array($username , $hashedPass));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      
      // Check If Count > 0 This Mean The Database Contain Record About This Username
      if ($count > 0) {
         $_SESSION['Username'] = $username; //Reguster Session Name
         $_SESSION['ID'] = $row['UserID'];
         header('Location: dashboard.php'); // Redirect To Dashboard Page 
         exit(); 
      }

   }
?>

   <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?> " method="POST" >
      <h4 class="text-center">Admin login</h4>
      <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off"/>
      <input class="form-control" type="password" name="pass" placeholder= "Password" autocomplete="mew-password"/>
      <input class="btn btn-primary btn-block" type="submit" value="Login"/>
   </form>


<?php include $tpl . 'fouter.php'; ?>
