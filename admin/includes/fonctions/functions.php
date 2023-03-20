<?php

    /*
    ** Get All Function v1.0  
    ** Function To Get All Records From Any  Database Table
    ** 
    */
    function getAllFrom($field, $table, $where = NULL, $and = NULL , $orderBy,  $ordering = "DESC"){
        global $con;


        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderBy $ordering ");

        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;
    }

    
    /*
    ** Title Function v1.0  
    ** Title Function That Echo The Page Title In Case The Page 
    ** Has The Variable $pageTitle And Echo Defult Title For Othrt Pager
    */

    function getTitle(){
        global $pageTitle;
        if(isset($pageTitle)){
            echo $pageTitle;
        }else {
            echo ' Default';
        }
    }

    /*
    ** Home Redirect Function v2.0
    ** This Function Accept Paramaters
    ** $theMsg = Echo The Message[Error | Success | Warning]
    ** $url = The Link You Want To Redirect To 
    ** $seconds - Seconds Befor Redirecting
    */

    function redirectHome($errorMsg , $url = null, $seconds = 3){

        if($url === null ){
            $url = 'index.php';
            $link = 'Homepage';
        } else{

            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

                $url = $_SERVER['HTTP_REFERER'];
                $link = 'Previous Page';

            }else{
                $url = 'index.php'; 
                $link = 'Homepage';
            }
            
        }

        echo  $errorMsg ;

        echo "<div class ='alert alert-info'> You Will Be Redirected To $link After $seconds Seconds.</div>";

        header("refresh: $seconds ; url= $url ");
        exit();
    }

    /*
    ** Check Items Function v1.0
    ** Function To Check Item In Database [Function accept parameters]
    ** $select = The Item To Select [Example : user , item , category]
    ** $From = the table to select from [Example : users, items, categories]
    ** $Value = the Value of select [Example: Osama, Box, Elecronics]
    */

    function  checkItem($select, $from, $value){

        global $con;
 
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");

        $statement->execute(array($value));

        $count = $statement->rowCount();

        return $count;
    }

    /*
    ** Count Number Of Items Function v1.0
    ** Function To Count Number of Items Rows
    ** $item = The Item To Count
    ** $table = The table To Choose From
    */

    function countItems($item , $table){

        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

        $stmt2->execute();

        return $stmt2->fetchColumn();
         
    }
 

    /*
     ** Get Latest Records Function v1.0  
     ** Function To Get Latest Items From Database
     ** $select = fialed to select
     ** $table = the table to choose froom
     ** $limit = number of records to get 
     ** $order = The Decs Ordering
     */
    function getLatest($select, $table, $order , $limit = 5){
        global $con;

        $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

        $getstmt->execute();

        $rows = $getstmt->fetchAll();

        return $rows;
    }





















