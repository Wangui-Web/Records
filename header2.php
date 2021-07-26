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
    <link rel="stylesheet" href="font-awesome/css/all.min.css">
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
                        <li class='nav-item'><a class='nav-link' href="dashboard.php">DashBoard</a></li>
                        <li class='nav-item'><a href="suppliers.php" class='nav-link'>Suppliers</a></li>
                        <div class="dropdown show">
                            <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style='background-color:transparent;border:none;outline:none;color:white;opacity:0.5'>
                                Manage Inventory
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="category.php">Category</a>
                                <a class="dropdown-item" href="products.php">Products</a>
                            </div>
                        </div>
                        <li class='nav-item'><a href="checkout.php" class='nav-link'>Checkout</a></li>
                        <li class='nav-item'><a href="transaction.php" class='nav-link'>Transaction Details</a></li>
                        <li class='nav-item'><a href="report.php" class='nav-link'>Generate Report</a></li>
                        
                        <form action='includes/logout.inc.php' method='POST'>
                            <button type='submit' class='btn btn-info' name='logout'>Log Out</button>
                        </form>
                        
                        
                        
                        

                        
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>