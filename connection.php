<?php
    $dsn="mysql:host=localhost;dbname=login";
    $user="root";
    $password="";
    $options=[];
    try { 
        $connection=new PDO($dsn,$user,$password,$options);
        // echo "connection successful";
    
    } 
    catch(PDOException){
        echo "connection error";
    }
?>