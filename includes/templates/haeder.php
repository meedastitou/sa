 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css;?>font-awesome.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo $css;?>frontend.css">   
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="account">
        <?php 
            if(isset($_SESSION['user'])){?>

                <div class="btn-group my-info">

                    <img class="img-responsive img-thumbnail rounded-circle " src="images.jpg" alt=""/>

                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="app-nav" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $sessionUser ;?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="profile.php">My Profile</a>
                            <a class="dropdown-item" href="profile.php#my-ads">My Items</a>
                            <a class="dropdown-item" href="newad.php">New Item </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php"><?php echo lang('Logout') ?></a>
                            </div>
                        </li>
                    </ul>

                </div>

                <?php
                $userStatus = checkUserStatus($_SESSION['user']);

                if ($userStatus == 1) {

                    echo "<span style='color: #fff; font-size: 13px;'>Your Membership Need To Activate By Admin</span>";
                    
                }
                
            }else{?>
                <div class="log_sinup">
                    <a href="login.php">
                        <span>Login | Sign Up</span>
                    </a>
                </div>
                <?php
            }
        ?>
    </div>
    <!-- Compte -->
    <div class="info">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#all_prudects">Prodects</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">about</a></li>
        </ul>
        
        <a href="#"><i class="fas fa-shopping-bag"></i></a>
    </div>
    
    
</nav>
<?php 
    if($pageTitle !== 'Login'){?>
        <div class="header_logo">
            <div class="logo">
                <a href="index.php"><img src="<?php echo $imgs;?>logo.png" alt="LaraShop" srcset=""></a>
            </div>
        </div>
        <?php
    }
?>


