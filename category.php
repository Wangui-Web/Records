<?php
include 'header2.php';
include 'includes/dbh.inc.php';
$alert1 =$alert2 ='';
$catName=$catBrand='';
if(isset($_POST['categoryOk'])){
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
                    $_SESSION['message']= $catBrand.' of category '.$catName.' has already been saved.Click edit to make changes to '.$catBrand;
                    $_SESSION['msg-type']='danger';
                }else{
                    
                    $sql ="INSERT INTO categories(categoryName,categoryBrand) VALUE(?,?)";

                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        echo 'Error during connection to db';
                    }else{
                        mysqli_stmt_bind_param($stmt,'ss',$catName,$catBrand);
                        mysqli_stmt_execute($stmt);   
                        $_SESSION['message']='The infomation has been saved';
                        $_SESSION['msg-type']='success';
                    }
                }
            
            }
        }
    }
}
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $sql ="DELETE FROM `categories` WHERE `categories`.`categoryID` = $id";
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
    
    header("location:categoryEdit.php?edit=$id");
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

<div class='container-fluid' style='margin:20px'>
<h2 class='text-center'>Categories</h2>

<div class="add-container" style='margin-bottom:10px'>
    <button type='button' class='btn btn-info' name='categories' onclick='openForm()'><i class='fa fa-plus'></i>Add Category</button>
</div>
<table class='table'>
    <thead class='thead-dark'>
        <tr>
            <th scope='col'>#</th>
            <th scope='col'>Category</th>
            <th scope='col'>Types of Brands</th>
            <th scope='col'>Manage</th>
        </tr>
    </thead>
    <?php 
        $sql = "SELECT * FROM categories";
        $result =mysqli_query($conn,$sql);
        while($row =mysqli_fetch_assoc($result)){?>
            
            <tbody>
                <tr>
                    <th scope ='row'><?php echo "#"?></th>
                    <td><?php echo $row['categoryName']?></td>
                    <td><?php echo $row['categoryBrand']?></td>
                    
                    <td>
                        <a href='category.php?delete=<?php echo $row['categoryID']?>' class='btn btn-danger' style=cursor:pointer;margin-right:10px><i class='far fa-trash-alt'></i>Delete</a>
                    
                        <a type='button' class='btn btn-info' href='category.php?edit=<?php echo $row['categoryID']?>'><i class='fa fa-edit'></i>Edit</a>

                    </td>
                </tr>
            </tbody>
    <?php }?>

</table>

    <form action="category.php" class='form-container' method='POST'>
            <label for="categoryName">Enter the category's name: </label>
            <input type="text" name='categoryName' id='categoryName' placeholder="category's name" value="<?php echo htmlspecialchars($catName)?>">
            <p class='text-danger'><?php echo $alert1?></p>

            <label for="categoryBrand">Enter the brand name of the product: </label>
            <input type="text" name='categoryBrand' id='categoryBrand' placeholder="category's brand" value="<?php echo htmlspecialchars($catBrand)?>">
            <p class='text-danger'><?php echo $alert2?></p>

            <div class="container" style='width:100%;display:flex;justify-content:space-around;' >
                <button type='submit' class='btn btn-info' onclick='openForm()'>Cancel</button>
                <button type='submit' class='btn btn-warning' name='categoryOk'>OK</button>
            </div>
    </form>
</div>
<script>
    let formContainer =document.querySelector('.form-container');
    const openForm =()=>{
        formContainer.classList.toggle('form-show');
    }
    
</script>


