<?php
include 'includes/dbh.inc.php';
include 'header.php';
$alert =$alert1=$alert2=$alert3=$alert4 ='';
$catName=$catBrand='';

$id=0;

if(isset($_GET['edit'])){
    $id =$_GET['edit'];
    
    $sql="SELECT * FROM categories WHERE categoryID =$id";
    $result =mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $catName = $row['categoryName'];
        $catBrand = $row['categoryBrand'];
    }
}
if(isset($_POST['update'])){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $id =$_POST['id'];
    $catName = $_POST['categoryName'];
    $catBrand = $_POST['categoryBrand'];

    if(empty($catName)||empty($catBrand)){
        $_SESSION['message']='Empty Fields';
        $_SESSION['msg-type']='danger';
    }else if(!(empty($catName)||empty($catBrand))){
        if(!(preg_match('/^[a-z\s]+$/i',$catName))){
            $alert1 ='Invalid Category Name';
        }
        if(!(preg_match('/^[a-z\s]+$/i',$catBrand))){
            $alert2 ='Invalid Category Name';
        }
        if((preg_match('/^[a-z\s]+$/i',$catName))&&(preg_match('/^[a-z\s]+$/i',$catBrand))){
            //to check if the brand name already exists in the category
            
                $sql = 'SELECT * FROM categories WHERE categoryName =? AND categoryBrand=?';
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    echo 'Error during connection to db'.mysqli_error($conn);
                }else{
                mysqli_stmt_bind_param($stmt,'ss',$catName,$catBrand);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $result = mysqli_stmt_num_rows($stmt);
                if($result >0){
                    $_SESSION['message']= $catName.' who supplies '.$catBrand.' has already been saved';
                    $_SESSION['msg-type']='danger';
                }else{
                    
                    $sql ="UPDATE categories SET categoryName='$catName',categoryBrand='$catBrand' WHERE categoryID='$id'";
                    mysqli_query($conn,$sql);

                    header('Location:category.php');
                    
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
            top:25%;
            left:25%;
            z-index:9;
            padding:20px 0;
            width: 50%;
            min-height:300px;
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
<form action="categoryEdit.php" class='form-show' method='POST'>
    <input type="hidden" name='id' value="<?php echo $id?>">
    <label for="categoryName">Enter the category's name: </label>
    <input type="text" name='categoryName' id='categoryName' placeholder="category's name" value="<?php echo htmlspecialchars($catName)?>">
    <p class='text-danger'><?php echo $alert1?></p>

    <label for="categoryBrand">Enter the brand name of the product: </label>
    <input type="text" name='categoryBrand' id='categoryBrand' placeholder="category's brand" value="<?php echo htmlspecialchars($catBrand)?>">
    <p class='text-danger'><?php echo $alert2?></p>

    <div class="container" style='width:100%;display:flex;justify-content:space-around;' >
        <a href="category.php" class='btn btn-info'>Cancel</a> 
        <button type='submit' class='btn btn-warning' name='update'>Update</button>
    </div>
    </form>