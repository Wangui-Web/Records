<?php
include 'includes/dbh.inc.php';
include 'header.php';

$qtyRequired=$productName=$categoryName=$measurement=$price=$totalPrice=$profit='';
$alert1=$alert4='';
$id=0;

if(isset($_GET['cart'])){
    $id =$_GET['cart'];
}

$sql="SELECT productName,productCategory,productMeasure,productSelling,productProfit FROM products WHERE productID=$id";
$result=mysqli_query($conn,$sql);
$row1= mysqli_fetch_assoc($result);

$productName= $row1['productName'];
$categoryName= $row1['productCategory'];
$measurement= $row1['productMeasure'];
$price= $row1['productSelling'];
$profit=$row1['productProfit'];

if (isset($_POST['customerOk'])) {

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $productName=$_POST['productName'];
    $categoryName=$_POST['categoryName'];
    $measurement=$_POST['measurement'];
    $qtyRequired=$_POST['qtyRequired'];
    $price=$_POST['price'];
    $profit=$_POST['profit'] *$qtyRequired;

    if(empty($qtyRequired)){
        $_SESSION['message']='Empty Fields';
        $_SESSION['msg-type']='danger';
    }else if(!(empty($qtyRequired))){
        
        if(!(preg_match('/^[\d]+$/i',$qtyRequired))){
            $alert2 ='Invalid quantity required';
        }
        if((preg_match('/^[\d]+$/i',$qtyRequired))){
            //to check if the brand name already exists in the category
            
                $sql = "SELECT productName,qtyRequired FROM customer WHERE productName=? AND qtyRequired=?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    echo 'Error during connection to db'.mysqli_error($conn);
                }else{
                
                
                mysqli_stmt_bind_param($stmt,'si',$productName,$qtyRequired);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $result = mysqli_stmt_num_rows($stmt);
                if($result >0){
                    $_SESSION['message']= 'Has already made an order of '.$productName.' measurement '.$qtyRequired;
                    $_SESSION['msg-type']='danger';
                }else{

                    $sql ="INSERT INTO customer(productName,categoryName,measurement,qtyRequired,price,totalPrice,profit) VALUE(?,?,?,?,?,?,?)";

                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        echo 'Error during connection to db'.mysqli_error($conn);
                    }else{
                        $totalPrice=$qtyRequired*$price;
                        mysqli_stmt_bind_param($stmt,'sssiiii',$productName,$categoryName,$measurement,$qtyRequired,$price,$totalPrice,$profit);
                        mysqli_stmt_execute($stmt);   

                        $_SESSION['message']='The infomation has been saved';
                        $_SESSION['msg-type']='success';

                        
                        header('Location:checkout.php');
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
            top:20%;
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
<div class="container">
    <form action="checkoutCart.php" class='form-show' method='POST'>

        <label for="productName">Product's name: </label>
        <input type="text" name='productName' id='productName' placeholder="product's name" value ="<?php echo htmlspecialchars($productName)?>">

        <label for="categoryName">Category's name: </label>
        <input type="text" name='categoryName' id='categoryName' placeholder="category's name" value ="<?php echo htmlspecialchars($categoryName)?>">

        <label for="measurement">Measurement: </label>
        <input type="text" name='measurement' id='measurement' placeholder="Measurement" value ="<?php echo htmlspecialchars($measurement)?>">

        <label for="qtyRequired">Enter the quantity required: </label>
        <input type="text" name='qtyRequired' id='qtyRequired' placeholder="Quantity required" value ="<?php echo htmlspecialchars($qtyRequired)?>">
        <p class="text-danger"><?php echo $alert4?></p>

        <label for="price">Price: </label>
        <input type="text" name='price' id='price' placeholder="Price" value ="<?php echo htmlspecialchars($price)?>">

        <label for="profit">Profit: </label>
        <input type="text" name='profit' id='profit' placeholder="Profit" value ="<?php echo htmlspecialchars($profit)?>">

        <div class="container" style='width:100%;display:flex;justify-content:space-around;' >
            <a href='checkout.php' class='btn btn-info'>Cancel</a>
            <button type='submit' class='btn btn-warning' name='customerOk'>OK</button>
        </div>
    </form>
</div>