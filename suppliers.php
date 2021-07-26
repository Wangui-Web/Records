<?php

include 'header2.php';
include 'includes/dbh.inc.php';

$id=0;
$update = false;
$alert=$alert1=$alert2=$alert3=$alert4='';
$supName=$supContact=$supEmail='';

if(isset($_POST['suppliersOk'])){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $supName = $_POST['supplierName'];
    $supContact =$_POST['supplierContact'];
    $supEmail =$_POST['supplierEmail'];

    if(empty($supName) || empty($supContact) || empty($supEmail) ){
        $_SESSION['message'] = 'Empty Fields';
        $_SESSION['msg-type']='danger';
    }else if (!(empty($supName) || empty($supContact) || empty($supEmail) )){
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
            $sql = 'SELECT * FROM supplier WHERE supplierName =? AND supplierContact=? AND supplierEmail=? ';
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
                }else{
                    
                    $sql ="INSERT INTO supplier(supplierName,supplierContact,supplierEmail) VALUE(?,?,?)";

                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        echo 'Error during connection to db';
                    }else{
                        mysqli_stmt_bind_param($stmt,'sis',$supName,$supContact,$supEmail);
                        mysqli_stmt_execute($stmt);   

                        $_SESSION['message']='The supplier has been saved';
                        $_SESSION['msg-type']='success';
                    }
                }
            }
            
        }
        
    }    
}
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $sql ="DELETE FROM `supplier` WHERE `supplier`.`supplierID` = $id";
    if(!mysqli_query($conn,$sql)){
       $_SESSION['message']='Failed to connect to db'.mysqli_error($conn); 
       $_SESSION['msg-type']='danger';
    }else{
        
        $_SESSION['message']='The information has been deleted';
        $_SESSION['msg-type']='danger';
        
    }
}
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    
    header("location:suppliersEdit.php?edit=$id");
}

?>
 
<head>
    <style>
        .form-container{
            display:none;
        }
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
<div class='container-fluid'>
<h2 class='text-center' style='margin-top:20px'>Suppliers</h2>

<p class="text-danger"><?php echo $alert?></p>

<div class="add-container" style='margin-bottom:10px' >
    <button type='button' class='btn btn-info' name='suppliers' onclick='openForm()'><i class='fa fa-plus'></i>Add Supplier</button>
</div>
<table class='table'>
    <thead class='thead-dark'>
        <tr>
            <th scope='col'>#</th>
            <th scope='col'>Name</th>
            <th scope='col'>Contact Info.</th>
            <th scope='col'>Email</th>
            <th scope='col'>Manage</th>
        </tr>
    </thead>
    <?php 
        $sql = "SELECT * FROM supplier";
        $result =mysqli_query($conn,$sql);
        while($row =mysqli_fetch_assoc($result)){?>

            <tbody>
                <tr>
                    <th scope ='row'><?php echo "#"?></th>
                    <td><?php echo $row['supplierName']?></td>
                    <td><?php echo $row['supplierContact']?></td>
                    <td><?php echo $row['supplierEmail']?></td>
                    <td>
                        <a href='suppliers.php?delete=<?php echo $row['supplierID']?>' class='btn btn-danger' style=cursor:pointer;margin-right:10px><i class='far fa-trash-alt'></i>Delete</a>
                    
                        <a type='button' name='edit_btn' class='btn btn-info' href='suppliers.php?edit=<?php echo $row['supplierID']?>'><i class='fa fa-edit'></i>Edit</a>

                    </td>
                </tr>
            </tbody>
    <?php }?>
    
</table>

    <form action="suppliers.php" class='form-container' method='POST'>
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
                <button type='submit' class='btn btn-info' onclick='openForm()'>Cancel</button>
                <button type='submit' class='btn btn-warning' name='suppliersOk'>OK</button>
            </div>
    </form>
</div>

<script>
    let formContainer =document.querySelector('.form-container');
    const openForm =()=>{
        formContainer.classList.toggle('form-show');
    }
    
</script>
