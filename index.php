<?php 
include('header.php');
$alert1='';
if(isset($_POST['login'])){
    
    include('includes/dbh.inc.php');

    $username =$_POST['username'];
    $password =$_POST['pwd'];

    //if any fields were left empty
    if(empty($username) || empty($password) ){
        $alert1 ='Empty fields';
        
    }else{

        //check the values of username and password from the database
        $sql = "SELECT * FROM user WHERE username=? OR email=?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt,$sql)){
            header('Location:index.php?sqlerror');
        }else{
            mysqli_stmt_bind_param($stmt,'ss',$username,$username);
            mysqli_stmt_execute($stmt);

            //grab the data from the database and store it as an array
            $result =mysqli_stmt_get_result($stmt);

            //check if $result has data in it or not
            if($row =mysqli_fetch_assoc($result)){
                //$pwdCheck checks password from the username enter in the database and try and match it with the password entered by the user
                //$password is the password entered by the user while $row['password'] is the password in the db
                $pwdCheck = password_verify($password,$row['password']);
                if($pwdCheck == false){
                    $alert1 = 'Incorrect password';
                }else if($pwdCheck == true){
                    //start a session and save info that is not sensitive for security purposes
                    session_start();
                    $_SESSION['userId'] =$row['userID'];
                    $_SESSION['userName'] =$row['username'];
                    $_SESSION['currentTime'] =$row['currenttime'];

                    header('Location:dashboard.php');
                    
                }else{
                    header('Location:index.php');
                }
            }else{
                $alert1='No Such User Found.Please sign up first';
            }
        }
    }
}

?>

<main>
 <div class="container">
    <div class="jumbotron">
    <p class='text-danger'><?php echo $alert1?></p>
        <h4>Welcome <span>User</span>, login</h4>
        <form action='index.php' method='POST'>
            <div class="form-group">
                <label for="username">Enter Username: </label>
                <input type="text" name='username' id='username' placeholder='Enter Username' class='form-control'>
            </div>
            <div class="form-group">
                <label for="pwd">Enter Password: </label>
                <input type="password" name='pwd' id='pwd' placeholder='Enter Password' class='form-control'>
            </div>
            <div class="btn-container">
                <button type="submit" class='btn btn-primary' name='login'>Log In</button>
                <button type="submit" class='btn btn-info'><a href="sign-in.php" style='color:#fff'>Sign Up</a></button>
            </div>
        </form>
    </div>
</div>
</main>


<?php include('footer.php');?>

