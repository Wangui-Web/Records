<?php
include 'includes/dbh.inc.php';
include 'header.php';
$alert =$alert1=$alert2=$alert3=$alert4 ='';
$supName = $supEmail = $supContact ='';
$id=0;
if(isset($_GET['edit'])){
    
    $id =$_GET['edit'];
    $sql="SELECT * FROM supplier WHERE supplierID =$id";
    $result =mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $supName = $row['supplierName'];
        $supContact =$row['supplierContact'];
        $supEmail =$row['supplierEmail'];
    }
}
if(isset($_POST['update'])){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $id =$_POST['id'];
    $supName =$_POST['supplierName'];
    $supContact =$_POST['supplierContact'];
    $supEmail =$_POST['supplierEmail'];

    if (!(empty($supName) || empty($supContact) || empty($supEmail) )){
        if(!preg_match("/^[a-z\s'\_]+$/i",$supName)){
            $alert1 ='Invalid supplier name';
        }
        if(!preg_match('/^07[0-9]{8}$/',$supContact)){
            $alert3 ='Invalid contact number';
        }
        if(!filter_var($supEmail,FILTER_VALIDATE_EMAIL)){
            $alert4 ='Invalid supplier email';
        }
        if((preg_match("/^[a-z\s'\_]+$/i",$supName))&& (preg_match('/^07[0-9]{8}$/',$supContact))&& (filter_var($supEmail,FILTER_VALIDATE_EMAIL))){

            //if there exist a similiar supplier with similiar product already stored
            $sql = 'SELECT * FROM supplier WHERE supplierName =? AND supplierContact=? AND supplierEmail=?';
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                echo 'Error during connection to db';
            }else{
                mysqli_stmt_bind_param($stmt,'sis',$supName,$supContact,$supEmail);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $result = mysqli_stmt_num_rows($stmt);
                if($result >0){
                    $_SESSION['message']= $supName.' has already been saved';
                    $_SESSION['msg-type']='danger';
                }
                else{               
                    $sql ="UPDATE supplier SET supplierName='$supName',supplierContact='$supContact',supplierEmail='$supEmail' WHERE supplierID='$id'";
                    mysqli_query($conn,$sql);

                    header('Location:suppliers.php');
                    
                }
            }    
        }
    }    
}


?>

<head>
    <style>
        
        .form-show{
            background-color:#3f3f3f;
            color:white;
            border: 2px solid black;
            position:fixed;
            top:20%;
            left:20%;
            z-index:9;
            padding:20px 0;
            width: 60%;
            min-height:400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .form-show input{
            width:80%;
            border:none;
            outline:none;
            border:1px solid black;
            border-radius:20px;
            text-align:center
        }
    </style>
</head>
<?php
if(isset($_SESSION['message'])){?>
<div class='alert alert-<?php echo $_SESSION['msg-type']?>'>
    <?php 
    echo $_SESSION['message'];
    unset($_SESSION['message']);
    ?>
</div>
<?php }?>
<form action="suppliersEdit.php" class='form-show' method='POST'>
    <input type="hidden" name='id' value="<?php echo $id?>">
    <label for="supplierName">Enter the supplier's name: </label>
    <input type="text" name='supplierName' id='supplierName' placeholder="Supplier's name" value ="<?php echo htmlspecialchars($supName)?>">
    <p class="text-danger"><?php echo $alert1?></p>

    <label for="supplierContact">Enter the supplier's contact: </label>
    <input type="text" name='supplierContact' id='supplierContact' placeholder="Supplier's Contact" value ="<?php echo htmlspecialchars($supContact)?>">
    <p class="text-danger"><?php echo $alert3?></p>

    <label for="supplierEmail">Enter the supplier's Email: </label>
    <input type="text" name='supplierEmail' id='supplierEmail' placeholder="Supplier's Email'" value ="<?php echo htmlspecialchars($supEmail)?>">
    <p class="text-danger"><?php echo $alert4?></p>

    <div class="container" style='width:100%;display:flex;justify-content:space-around;' >
        <a href="suppliers.php" class='btn btn-info'>Cancel</a> 
        <button type='submit' class='btn btn-warning' name='update'>Update</button>
    </div>
    </form>