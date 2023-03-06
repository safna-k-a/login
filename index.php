<?php
    require 'connection.php';
    session_start();
    require 'header.php';
?> 
<?php
    $val=false;
    if(isset($_POST['email'])&&isset($_POST['password'])){
        $email=$_POST['email'];
        $password=($_POST['password']);
        $_SESSION['email']=$email;
        $_SESSION['password']=$password;
        $sql='SELECT * from student where email=:email LIMIT 1';
        $statement=$connection->prepare($sql);
        $statement->execute([':email'=>$email]);
        $stud=$statement->fetch(PDO::FETCH_OBJ);
        if($stud){
            $var=$stud->password;
            if(md5($password)==$var){
                header("Location:profile.php");
            }
            else{
                $val=true;
            }
           
            
        }
        else{
            $val=true;
        }
        
    
    }
    if(isset($_POST['ok'])){
        $email=$_POST['femail'];
        $sql='SELECT * from student where email=:email';
        $statement=$connection->prepare($sql);
        $statement->execute([':email'=>$email]);
        $fstud=$statement->fetch(PDO::FETCH_OBJ);

    }
    if(isset($_POST['change'])){
        $email=$_POST['femail'];
        $newpassword=md5($_POST['newpassword']);
        $re_password=md5($_POST['re_password']);
        if($newpassword==$re_password){
            $sql='UPDATE student SET password=:password WHERE email=:email';
        $statement=$connection->prepare($sql);
        if($statement->execute([':password'=>$newpassword,':email'=>$email])){
            echo "<script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Password changed',
                showConfirmButton: false,
                timer: 2000
              }).then(function(){
                window.location='index.php';
              });

            </script>";
        }
        else{
            echo "<script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Failed to Reset',
                showConfirmButton: false,
                timer: 2000
              }).then(function(){
                window.location='index.php';
              });

            </script>";
        }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        />
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div class="container">
        <div class="row mt-5">
            <div class="col-sm-6"><img src="images/login.png"></div>
            <div class="col-sm-6">
                <h2 class="mt-5">Login</h2>
                <?php
                if($val){
                echo"
                    <div class='alert alert-danger' role='alert'>
                    Incorrect email or password!
                  </div>";}
                ?>
                <form action="" method="POST" class="">
                    <div><input type="email" name="email" placeholder="Email" class="form-control mt-5 p-3"></div>
                    <div><input type="password" name="password" placeholder="Password" class="form-control mt-3 p-3"></div>
                    <div><input type="submit" name="submit" value="Login" class="form-control btn btn-primary mt-3 p-3"></div>
                    <div><a href="register.php" name="create" value="Create New Account" class="form-control btn btn-info mt-3 mb-3 p-3 bg-opacity-75">Create New Account</a></div>
                    <a href="" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Forgotten Password</a>
                </form>
                <!--  -->
            </div>
        </div>
        <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Reset Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form method="POST">
            <div><input type="email" id="femail" name="femail" placeholder="Email Please"class="form-control" required></div>
            <p id="test"></p>
            <div class="mt-5 text-primary"><label>Your Security Question:<p id="question"></p></label></div>
            
            <div id="ans"></div>
            <p id="msg"></p>
            <div id="new"></div>
            <div id="confirm"></div>
            <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <div id="modal_submit"></div>
      </div>
            </form>
        </div>
      
    </div>
  </div>
</div>
        </div>

        
        <!--bootstrap-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jquery CDN -->
        <script
            src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
            crossorigin="anonymous"
        ></script>
        <script>
            $(document).ready(() => {
               $('#femail').keyup(()=>{
                    let email_search=$('#femail').val()
                    $.ajax({
                        url:'live_search.php',
                        method:'POST',
                        data:{email:email_search},
                        success:function(data){
                            console.log(data);
                            if(data!=0){
                                $('#test').html('<i class="fa-solid fa-check"></i>');
                                var json = JSON.parse(data);
                                $('#question').html(json.question);
                                $('#ans').html('<input type="text" name="answer" id="ans1" placeholder="Your Answer" class="form-control mt-5"required>');
                                var ans=json.answer;
                                $('#ans1').keyup(()=>{
                                    let ans_search=$('#ans1').val();
                                    console.log(ans_search);
                                    if(ans==ans_search){
                                        $('#new').html('<input type="text" name="newpassword" placeholder="New password" class="form-control mt-5"required>');
                                        $('#confirm').html('<input type="text" name="re_password" placeholder="Re enter password" class="form-control mt-5"required>');
                                        $('#modal_submit').html('<button type="submit" name="change" class="btn btn-primary">Save changes</button>');
                                    }
                                    
                                });
                            }
                            else{
                                $('#test').html('<i class="fa-solid fa-circle-xmark"></i>');
                            }
                        }
                    });
               });
                            
               });

                    
               
        </script>
        <!-- fontawsome CDN -->
        <script
            src="https://kit.fontawesome.com/34baa6b8a4.js"
            crossorigin="anonymous"
        ></script>
        <!-- custom script -->
        <script src="js/script.js"></script>
    </body>
</html>
