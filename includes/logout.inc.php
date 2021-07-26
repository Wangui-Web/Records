<?php
session_start();
session_unset();
session_destroy();

include '../header.php';
include 'dbh.inc.php';

if(isset($_POST['logout'])){
    header('Location: ../index.php');
}