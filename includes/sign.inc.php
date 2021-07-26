<?php
if(isset($_POST['signup-btn'])){
  
    //include the connection to the database
    include('dbh.inc.php');

    
    //fetch the values entered
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $confirmPwd = $_POST['confirm-pwd'];

    if(empty($username) || empty($email) || empty($password) || empty($confirmPwd) ){
        echo 'empty fields';
    }else if(!preg_match('/^[a-z\d_#@]$/i',$username)){
        echo 'Invalid Username';
    }else if($password !== $confirmPwd){
        echo 'Could not confirm password';
    }else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo 'Invalid email';
    }


}
?>