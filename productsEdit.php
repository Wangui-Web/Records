<?php
include 'includes/dbh.inc.php';
include 'header.php';
$alert1 =$alert2 =$alert3 =$alert4 =$alert5 =$alert6 =$alert7 =$alert8 =$alert9 =$alert10 =$alert11 ='';

$productName=$productCategory=$productSupplier=$productDate=$productUser=$productMeasure=$productQty=$productBuying=$productSelling=$productProfit=$productQtyLeft='';

$id=0;

if(isset($_GET['edit'])){
    $id =$_GET['edit'];
    
    $sql="SELECT * FROM products WHERE productID =$id";
    $result =mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $productName = $row['productName'];
        $productCategory = $row['productCategory'];
        $productSupplier = $row['productSupplier'];
        $productUser = $row['productUser'];
        $productMeasure = $row['productMeasure'];
        $productQty= $row['productQty'];
        $productBuying = $row['productBuying'];
        $productSelling = $row['productSelling'];
        $productQtyLeft = $row['productQtyLeft'];
    }
}
if(isset($_POST['update'])){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $id =$_POST['id'];
    $productName = $_POST['productName'];
    $productCategory = $_POST['productCategory'];
    $productSupplier = $_POST['productSupplier'];
    $productUser = $_POST['productUser'];
    $productMeasure = $_POST['productMeasure'];
    $productQty= $_POST['productQty'];
    $productBuying = $_POST['productBuying'];
    $productSelling = $_POST['productSelling'];
    $productProfit=$productSelling-$productBuying;
    $productQtyLeft = $productQtyLeft + $productQty;
        
    if(empty($productName)||empty($productCategory)||empty($productSupplier)||empty($productUser)||empty($productMeasure)||empty($productQty)||empty($productBuying)||empty($productSelling)){
        $_SESSION['message']='Empty Fields';
        $_SESSION['msg-type']='danger';
    }else if(!(empty($productName)||empty($productCategory)||empty($productSupplier)||empty($productUser)||empty($productMeasure)||empty($productQty)||empty($productBuying)||empty($productSelling))){

        if(!(preg_match('/^[a-z\s]+$/i',$productName))){
            $alert1 ='Invalid product Name';
        }
        if(!(preg_match('/^[a-z\s]+$/i',$productCategory))){
            $alert2 ='Invalid product category';
        }
        if(!(preg_match("/^[a-z\s'\_]+$/i",$productSupplier))){
            $alert3 ='Invalid product supplier.Enter the supplier for this product';
        }
        
        if(!(preg_match('/^[a-z\d-#_@]+$/i',$productUser))){
            $alert5 ='Such a user doesnt exist.Enter your username';
        }
        if(!(preg_match('/^[\da-z\s]+$/i',$productMeasure))){
            $alert6 ='Invalid measurement';
        }
        if(!(preg_match('/^[\d]+$/i',$productQty))){
            $alert7 ='Invalid product quantity';
        }
        if(!(preg_match('/^[\d]+$/i',$productBuying))){
            $alert8 ='Invalid product buying price';
        }
        if(!(preg_match('/^[\d]+$/i',$productSelling))){
            $alert9 ='Invalid product selling price';
        }
        
        
        if((preg_match('/^[a-z\s]+$/i',$productName))&&(preg_match('/^[a-z\s]+$/i',$productCategory))&&(preg_match("/^[a-z\s'\_]+$/i",$productSupplier))&&(preg_match('/^[a-z\d-#_@]+$/i',$productUser))&&(preg_match('/^[\da-z\s]+$/i',$productMeasure))&&(preg_match('/^[\d]+$/i',$productQty))&&(preg_match('/^[\d]+$/i',$productBuying))&&(preg_match('/^[\d]+$/i',$productSelling))){

            $sql="SELECT productName,productCategory,productSupplier,productQty,productBuying,productSelling FROM products WHERE productName=? AND productCategory=? AND productSupplier=? AND productQty=? AND productBuying=? AND productSelling=?";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                echo 'Error during connection to database'.mysqli_error($conn);
            }else{
                mysqli_stmt_bind_param($stmt,'sssiii',$productName,$productCategory,$productSupplier,$productQty,$productBuying,$productSelling);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $result=mysqli_stmt_num_rows($stmt);
                if($result>0){
                    $_SESSION['message']= $productName.' supplied by '.$productSupplier.' has already been saved.Click edit to make changes to '.$productName;
                    $_SESSION['msg-type']='danger';
                }else{
                    $sql ="UPDATE products SET productName='$productName',productCategory='$productCategory',productSupplier='$productSupplier',productUser='$productUser',productMeasure='$productMeasure',productQty='$productQty',productBuying='$productBuying',productSelling='$productSelling',productProfit='$productProfit',productQtyLeft='$productQtyLeft' WHERE productID='$id'";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        echo 'Error during connection to db'.mysqli_error($conn);
                    }else{
                        mysqli_stmt_bind_param($stmt,'sssssiiiii',$productName,$productCategory,$productSupplier,$productUser,$productMeasure,$productQty,$productBuying,$productSelling,$productProfit,$productQtyLeft);
                        mysqli_stmt_execute($stmt);   
                        $_SESSION['message']='The infomation has been saved';
                        $_SESSION['msg-type']='success';
                        header('Location:products.php') or die();
                    }
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
            top:10%;
            left:25%;
            z-index:9;
            padding:20px 0;
            width: 50%;
            height:550px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            overflow:scroll;
        }
        .form-show input,.form-show select{
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
<form action="productsEdit.php" class='form-show' method='POST'>
    <input type="hidden" name='id' value="<?php echo $id?>">
    <label for="productName">Enter the product's Name: </label>
    <input type='text' name ='productName' id='productName' value="<?php echo htmlspecialchars($productName)?>">
    <p class='text-danger'><?php echo $alert1?></p>

    <label for="productCategory">Enter the product's Category: </label>
    <input type='text' name ='productCategory' id='productCategory' value="<?php echo htmlspecialchars($productCategory)?>">
    <p class='text-danger'><?php echo $alert2?></p>

    <label for="productSupplier">Enter the product's Supplier: </label>
    <input type='text' name ='productSupplier' id='productSupplier' value="<?php echo htmlspecialchars($productSupplier)?>">
    <p class='text-danger'><?php echo $alert3?></p>

    <label for="productUser">Enter the product's User: </label>
    <input type='text' name ='productUser' id='productUser' value="<?php echo htmlspecialchars($productUser)?>">
    <p class='text-danger'><?php echo $alert5?></p>

    <label for="productMeasure">Enter the product's measurement: </label>
    <input type="text" name='productMeasure' id='productMeasure' placeholder="e.g. Kilogram" value="<?php echo htmlspecialchars($productMeasure)?>">
    <p class='text-danger'><?php echo $alert6?></p>

    <label for="productQty">Enter the product's quantity: </label>
    <input type="text" name='productQty' id='productQty' placeholder="product's Qty" value="<?php echo htmlspecialchars($productQty)?>">
    <p class='text-danger'><?php echo $alert7?></p>

    <label for="productBuying">Enter the product's buying price: </label>
    <input type="text" name='productBuying' id='productBuying' placeholder="product's Buying" value="<?php echo htmlspecialchars($productBuying)?>">
    <p class='text-danger'><?php echo $alert8?></p>

    <label for="productSelling">Enter the product's selling price: </label>
    <input type="text" name='productSelling' id='productSelling' placeholder="product's Selling" value="<?php echo htmlspecialchars($productSelling)?>">
    <p class='text-danger'><?php echo $alert9?></p>

    <div class="container" style='width:100%;display:flex;justify-content:space-around;' >
        <a href="products.php" class='btn btn-info'>Cancel</a> 
        <button type='submit' class='btn btn-warning' name='update'>Update</button>
    </div>
    </form>
