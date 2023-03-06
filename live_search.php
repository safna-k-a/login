<?php

    require 'connection.php';
    if(isset($_POST['email'])){
    $input=$_POST['email'];
    $sql='SELECT * FROM student WHERE (email=:email)';
    $statement=$connection->prepare($sql);
    $statement->execute([':email'=>$input]);
    $detail=$statement->fetch(PDO::FETCH_OBJ);

    if($statement->rowCount()>0){
        echo json_encode($detail);

    }
    else{
        echo '0' ;
    }}
    
   
    ?> 

