<?php
    include('header.php');
    $alert1=$alert2=$alert3=$alert4=$alert5='';

    
    if(isset($_POST['signup-btn'])){
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        //include the connection to the database
        include('includes/dbh.inc.php');
        
        //fetch the values entered
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];
        $confirmPwd = $_POST['confirm-pwd'];
        
        if(empty($username) || empty($email) || empty($password) || empty($confirmPwd) ){
            $alert1 ='Empty fields';
        }else if(!(empty($username) || empty($email) || empty($password) || empty($confirmPwd)) ){
            if($password !== $confirmPwd){
                $alert2= 'Did not match password';
            } 
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $alert3= 'Invalid email';
            }
            if(!preg_match('/^[a-z\d-#_@]+$/i',$username)){
                $alert4= 'Invalid Username';
            }
            if(preg_match('/^[a-z\d-#_@]+$/i',$username) && (filter_var($email,FILTER_VALIDATE_EMAIL)) && ($password === $confirmPwd)){
                //to check if a similar username exist
                
                $sql = "SELECT * FROM user WHERE username=? OR password=?";
                $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt,$sql)){
                    header('Location:sign-in.php?sqlerror');
                    exit();
                }else{
                    mysqli_stmt_bind_param($stmt,"ss",$username,$password);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    $resultCheck = mysqli_stmt_num_rows($stmt);

                    if($resultCheck > 0){
                        $alert5 = 'The username or password has already been taken';
                    }else{
                        $hashedpwd = password_hash($password,PASSWORD_DEFAULT);
                        //insert info entered to the database
                        $sql ="INSERT INTO user(username,password,email) VALUE(?,?,?)";
                        $stmt=mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt,$sql)){
                            header('Location:sign-in.php?sqlerror');
                        }else{
                            mysqli_stmt_bind_param($stmt,'sss',$username,$hashedpwd,$email);
                            mysqli_stmt_execute($stmt);
                            header('Location:index.php');
                        }
                    }
                }
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            }    
        }    
    }
    
?>
<main>
    <div class="container">
        <div class="jumbotron">
            <h4>Hello Guest</h4>
            <p class='text-success'><?php echo $alert5 ?></p>
            <p class='text-danger'><?php echo $alert1 ?></p>
            <form action='sign-in.php' method='POST'>
                <div class="form-group">
                    <label for="username">Enter Username: </label>
                    <input type="text" name='username' placeholder='Enter Username' class='form-control'>
                    <p class='text-danger'><?php echo $alert4 ?></p>
                </div>
                <div class="form-group">
                    <label for="email">Enter Email: </label>
                    <input type="text" name='email' id='email' placeholder='Enter email' class='form-control'>
                    <p class='text-danger'><?php echo $alert3 ?></p>
                </div>
                
                <div class="form-group">
                    <label for="pwd">Enter Password: </label>
                    <input type="password" name='pwd' id='pwd' placeholder='Enter Password' class='form-control'>
                </div>
                <div class="form-group">
                    <label for="confirm-pwd">Confirm Password: </label>
                    <input type="password" name='confirm-pwd' id='confirm-pwd' placeholder='Confirm Password' class='form-control'>
                    <p class='text-danger'><?php echo $alert2 ?></p>
                </div>
                <div class="btn-container">
                    <button type="submit" class='btn btn-primary' name='signup-btn'>Sign Up</button>
                </div>
            </form>
        </div>
    </div>
</main>