

<?php

/*
=================================================
== Manage Comments PAGE
==You Can Edit | Delete Comments From Here
=================================================
*/
    ob_start();
    session_start();
    $pageTitle = 'Comments';
   if(isset($_SESSION['Username'])){
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 
        // Star Manage Page
        
        if ($do == 'Manage'){ // Manage Members Page 
            
        
        
            // Select All Users Except Admin 

            $stmt = $con->prepare("SELECT
                                        comments.*, items.Name AS Item_Name, users.Username 
                                    FROM 
                                        comments
                                    INNER JOIN
                                        items
                                    ON
                                        items.item_ID = comments.item_id
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id
                                    ORDER BY 
                                        c_id DESC ");

            // Execute The Statement

            $stmt->execute();
            
            // Assign To Variable

            $rows = $stmt->fetchAll();
            ?>
            
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Data</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                            foreach($rows as $row ){
                                echo "<tr>";
                                    echo "<td> " . $row['c_id'] . "</td>";
                                    echo "<td> " . $row['comment'] . "</td>";
                                    echo "<td> " . $row['Item_Name'] . "</td>";
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

            </div>

            <?php
        
        }elseif ($do == 'Edit'){ // Edit Page 

            // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            
            // select all data depend on this id 
            
            $stmt = $con->prepare("SELECT * FROM comments  WHERE c_id = ?");

            // EXECUTE QUERY

            $stmt->execute(array($comid));

            // FETCH THE DATA

            $row = $stmt->fetch();

            // THE ROW COUNT 

            $count = $stmt->rowCount(); 

            // IF THERE'S SUCH ID SHOW THE FORM
            
            if($count > 0){ ?>
            
                <h1 class="text-center">Edit Comment</h1>
                <div class="container">
                    <form action="?do=Update" method="POST" class="form-horizontal">
                        <input type="hidden" name="comid" value="<?php echo $comid ?>">
                        <!-- Start Comment Field -->
                        <div class="form-group row form-group-lg">
                            <label class="col-sm-2">Comment</label>
                            <div class="col-sm-10 col-md-6">
                                <textarea class="form-control" name="comment"><?php echo $row['comment']; ?></textarea> 
                            </div>
                        </div>
                        <!-- End Comment Field -->

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

                $comid = $_POST['comid'];
                $comment = $_POST['comment'];
            
                // Update The Database With This Info

                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ? ");
                $stmt->execute(array($comment, $comid));

                //Echo Success Message 

                $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back',4 );
            

            } else {

                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browsed this page Directly</div>';
                
                redirectHome($theMsg);

            }
            echo "</div>";

        }elseif($do == 'Delete'){ // Delete Members Page

            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

                // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('c_id', 'comments', $comid);

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    

                    $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcomment");

                    $stmt->bindParam(":zcomment", $comid );

                    $stmt->execute();
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted </div>';
                    redirectHome($theMsg , 'back');

                }else{
                    $theMsg = "<div class='alert alert-danger'> This Id Is Not Exist </div>";

                    redirectHome($theMsg);
                }
            echo '</div>';

        } elseif($do == 'Approve'){ // Activate Page

            echo "<h1 class='text-center'>Activate Member</h1>";

            echo "<div class='container'>";

                // Chick If Get Request Userid Is Numeric & Get The Integer Value Of It
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
                
                // select all data depend on this id 
                
                $check = checkItem('c_id', 'comments', $comid);

                // IF THERE'S SUCH ID SHOW THE FORM
                
                if($check > 0){

                    $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

                    $stmt->execute(array($comid));
                    
                    $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated  </div>';
                    redirectHome($theMsg, 'back');

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
?>