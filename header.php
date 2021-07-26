<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="bootstrap-4.0.0/dist/css/bootstrap.min.css">
</head>
<body>

<header>
    <nav class='navbar navbar-expand-lg navbar-dark bg-primary navbar-fixed-top' >
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class='navbar-brand'>Ben Traders</a>
                <button class='navbar-toggler' id= 'btn' type='button' data-toggle ="collapse" data-target='#nav' aria-controls='nav' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class="collapse navbar-collapse" id='nav'>
                    <ul class="navbar-nav">
                        <?php 
                        
                            if(isset($_SESSION['userId'])){
                                echo "<form action='includes/logout.inc.php' method='POST'>
                                    <button type='submit' class='btn btn-info' name='logout'>Log Out</button>
                                </form>";
                            }else{
                                echo "<li class='nav-item active'><a href='index.php' class='nav-link'>Log in</a></li>";
                            }
                        ?>
                        
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<script src="bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>