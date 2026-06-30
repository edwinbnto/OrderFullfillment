<?php 

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "project8";
    $conn = "";

    try{
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    }
    catch(mysqli_sql_exception){
        echo"Connection failed!";
    }

    if($conn){
        echo"You are connected!";
    }

    //  if(DB::connection()->getPdo()){
    //     echo "Connected to the database successfully! DB name is " . DB::connection()->getDatabaseName();
    // }
?>