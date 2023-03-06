<?php
    require 'header.php';
    require 'connection.php';
    session_start();
?> 
<?php
    $val=false;
    $pass=false;
    if(isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['question'])&&isset($_POST['answer'])){
        $first_name=$_POST['first_name'];
            $last_name=$_POST['last_name'];
            $email=$_POST['email'];
            $password=md5($_POST['password']);
            $confirm_password=md5($_POST['confirm_password']);
            if($password!=$confirm_password){
                $pass=true;

            }
            else{
            $question=$_POST['question'];
            $answer=$_POST['answer'];
            $pic=$_FILES['image']['name']; 
            $temp=$_FILES['image']['tmp_name'];
            $target="uploads/".basename($pic);
            $move_pic=move_uploaded_file($temp,$target);
            $sql=("SELECT * FROM student WHERE email=:email LIMIT 1");
        $statement=$connection->prepare($sql);
        $statement->execute([':email' => $email]);
        $check_email=$statement->fetch(PDO::FETCH_ASSOC);
        if ($check_email) {
            $val=true;
        }
        else{
            
            $sql='INSERT INTO student(first_name,last_name,email,password,BLOB_pic,question,answer) VALUES(:first_name,:last_name,:email,:password,:image,:question,:answer)';
            $statement=$connection->prepare($sql);
            if($statement->execute([':first_name'=>$first_name,':last_name'=>$last_name,':email'=>$email,':password'=>md5($password),':image'=>$pic,':question'=>$question,':answer'=>$answer])){
                echo "<script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Data Added Successfully',
                    showConfirmButton: false,
                    timer: 2000
                  }).then(function(){
                    window.location='index.php';
                  });

                </script>";
                    }
            }
        }
            
    }
?>

        <div class="container">
        <div class="row mt-5">
            <div class="col-sm-6 mt-5"><img src="images/register.png"></div>
            <div class="col-sm-6">
                <div class="mt-5"><h2 class="text center"> Register</h2></div>
                <?php
                if($val){
                echo"
                    <div class='alert alert-danger' role='alert'>
                    Email already exist!
                  </div>";}
                  if($pass){
                    echo"
                        <div class='alert alert-danger' role='alert'>
                        password and confirm password is not same!
                      </div>";}
                ?>
                <form action="" method="POST" enctype="multipart/form-data" class="">
                    <div><input type="text" name="first_name" placeholder="First Name" class="form-control mt-5 p-2" required></div>
                    <div><input type="text" name="last_name" placeholder="Last Name" class="form-control mt-3 p-2" required></div>
                    <div><input type="email" name="email" placeholder="Email" class="form-control mt-3 p-2"></div>
                    <div><input type="password" name="password" placeholder="Password" class="form-control mt-3 p-2" required></div>
                    <div><input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control mt-3 p-2" required></div>
                    <div><input type="file" name="image" value="No File Choosen" class="form-control mt-3 p-2" required></div>
                    <div><select id="question" name="question" class="form-control" required>
                    <option value="What is your pet name?">Choose one security question</option>
                    <option value="What is your pet name?">What is your pet name?</option>
                    <option value="Enter your 4 digit number?">Enter your 4 digit number?</option>
                    <option value="which is your favourite color?">which is your favourite color?</option>
                    <option value="What is your nick name?">What is your nick name?</option>
                    </select></div>
                    
                    <div><input type="text" name="answer" placeholder="Your Answer" class="form-control mt-3 p-2" required></div>
                    <div><input type="submit" name="submit" value="Submit" class="form-control btn btn-primary mt-3 p-2"></div>
                    <div><a href="index.php" name="create" value="Already have an Account??" class="form-control btn btn-info mt-3 mb-3 p-3 bg-opacity-75">Already have an Account??</a></div>

                </form>
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
        <!-- fontawsome CDN -->
        <script
            src="https://kit.fontawesome.com/34baa6b8a4.js"
            crossorigin="anonymous"
        ></script>
        <!-- custom script -->
        
        <script src="js/script.js"></script>
    </body>
</html>
