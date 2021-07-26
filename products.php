<?php
include 'header2.php';
include 'includes/dbh.inc.php';

$alert1 =$alert2 =$alert3 =$alert4 =$alert5 =$alert6 =$alert7 =$alert8 =$alert9 =$alert10 =$alert11 ='';
$productName=$productCategory=$productSupplier=$productDate=$productUser=$productMeasure=$productQty=$productBuying=$productSelling=$productProfit='';
$productQtyLeft=0;

if(isset($_POST['productOk'])){
    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $productName = $_POST['productName'];
    $productCategory = $_POST['productCategory'];
    $productSupplier = $_POST['productSupplier'];
    $productUser = $_POST['productUser'];
    $productMeasure = $_POST['productMeasure'];
    $productQty= $_POST['productQty'];
    $productBuying = $_POST['productBuying'];
    $productSelling = $_POST['productSelling'];
    $productProfit =$productSelling-$productBuying;
    
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
                //to check if the brand name already exists in the table
            
                $sql = "SELECT * FROM products WHERE productName =? AND productCategory=? AND productSupplier =?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    echo 'Error during connection to db'.mysqli_error($conn);
                }else{
                mysqli_stmt_bind_param($stmt,'sss',$productName,$productCategory,$productSupplier);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $result = mysqli_stmt_num_rows($stmt);
                if($result >0){
                    $_SESSION['message']= $productName.' supplied by '.$productSupplier.' has already been saved.Click edit to make changes to '.$productName;
                    $_SESSION['msg-type']='danger';
                }else{
                    
                    $sql ="INSERT INTO products(productName,productCategory,productSupplier,productUser,productMeasure,productQty,productBuying,productSelling,productProfit) VALUE(?,?,?,?,?,?,?,?,?)";

                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        echo 'Error during connection to db'.mysqli_error($conn);
                    }else{
                        mysqli_stmt_bind_param($stmt,'sssssiiii',$productName,$productCategory,$productSupplier,$productUser,$productMeasure,$productQty,$productBuying,$productSelling,$productProfit);
                        mysqli_stmt_execute($stmt);   
                        $_SESSION['message']='The infomation has been saved';
                        $_SESSION['msg-type']='success';
                    }
                    
                }
            
            } 
            $sql="SELECT productID FROM products WHERE productName=$productName";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_assoc($result);
            echo $row;     
        } 
    }  
}
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $sql ="DELETE FROM `products` WHERE `products`.`productID` = $id";
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
    
    header("location:productsEdit.php?edit=$id");
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
            top:2%;
            left:25%;
            z-index:9;
            padding:20px 0;
            width: 50%;
            height:600px;
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

<div class='container-fluid'style='margin-top:20px'>
<h2 class='text-center'>Products</h2>

<div class="add-container" style='float:right;margin-bottom:10px;'>
    <button type='button' class='btn btn-info' name='categories' onclick='openForm()' ><i class='fa fa-plus'></i>Add Product</button>
</div>
<table class='table'>
    <thead class='thead-dark'>
        <tr>
            <th scope='col'>Name</th>
            <th scope='col'>Category</th>
            <th scope='col'>Supplier</th>
            <th scope='col'>Date Recorded</th>
            <th scope='col'>Recorded By</th>
            <th scope='col'>Measurement</th>
            <th scope='col'>Quantity</th>
            <th scope='col'>Buying Price</th>
            <th scope='col'>Selling Price</th>
            <th scope='col'>Profit</th>
            <th scope='col'>Total Expected Profit</th>
            <th scope='col'>Quantity Left</th>
            <th scope='col'>Manage</th>
        </tr>
    </thead>
    <?php 
        

        $sql = "SELECT * FROM products";
        $result =mysqli_query($conn,$sql);
        while($row =mysqli_fetch_assoc($result)){?>
            <tbody>
                <tr>
                    <td><?php echo $row['productName']?></td>
                    <td><?php echo $row['productCategory']?></td>
                    <td><?php echo $row['productSupplier']?></td>
                    <td><?php echo $row['productDate']?></td>
                    <td><?php echo $row['productUser']?></td>
                    <td><?php echo $row['productMeasure']?></td>
                    <td><?php echo $row['productQty']?></td>
                    <td><?php echo $row['productBuying']?></td>
                    <td><?php echo $row['productSelling']?></td>
                    <td><?php echo $row['productProfit']?></td>
                    <td><?php echo $row['productQty']*$row['productProfit']?></td>
                    <td><?php echo $row['productQtyLeft']?></td>
                    <td>
                        <a href='products.php?delete=<?php echo $row['productID']?>' class='btn btn-danger' style=cursor:pointer;margin-right:10px><i class='far fa-trash-alt'></i>Delete</a>
                    
                        <a type='button' class='btn btn-info' href='products.php?edit=<?php echo $row['productID']?>'><i class='fa fa-edit'></i>Edit</a>

                    </td>
                </tr>
            </tbody>
        <?php }?>
        <h3>Today's inventory value is:
            <?php
                $value =0;
                $sql ="SELECT productSelling,productQtyLeft FROM products";
                $result =mysqli_query($conn,$sql);
                while($row =mysqli_fetch_all($result,MYSQLI_ASSOC)){
                    for ($i=0; $i < count($row); $i++) { 
                        $value += (($row[$i]['productSelling']) * ($row[$i]['productQtyLeft']));
                    }
                echo $value;
                $_SESSION['totalValue'] =$value;
                }
            ?>
        </h3>
    

</table>

    <form action="products.php" class='form-container' method='POST'>
            <label for="productName">Enter the product's Name: </label>
            <select name ='productName' id='productName'>
            <?php 
                $sql1="SELECT categoryBrand FROM categories";
                $result=mysqli_query($conn,$sql1);
                while($row =mysqli_fetch_assoc($result)){?>
                <option>
                    <?php echo $row['categoryBrand']?>
                </option> 

                <?php }?>               

            </select>
            <p class='text-danger'><?php echo $alert1?></p>

            <label for="productCategory">Enter the product's Category: </label>
            <select name ='productCategory' id='productCategory'>
            <?php 
                $sql1="SELECT categoryName FROM categories";
                $result=mysqli_query($conn,$sql1);
                while($row =mysqli_fetch_assoc($result)){?>
                <option>
                    <?php echo $row['categoryName']?>
                </option> 

                <?php }?>               

            </select>
            <p class='text-danger'><?php echo $alert2?></p>

            <label for="productSupplier">Enter the product's Supplier: </label>
            <select name ='productSupplier' id='productSupplier'>
            <?php 
                $sql1="SELECT supplierName FROM supplier";
                $result=mysqli_query($conn,$sql1);
                while($row =mysqli_fetch_assoc($result)){?>
                <option>
                    <?php echo $row['supplierName']?>
                </option> 

                <?php }?>               

            </select>
            <p class='text-danger'><?php echo $alert3?></p>

            <label for="productUser">Enter the product's User: </label>
            <select name ='productUser' id='productUser'>
                <?php 
                    $sql1="SELECT username FROM user";
                    $result=mysqli_query($conn,$sql1);
                    while($row =mysqli_fetch_assoc($result)){?>
                    <option>
                        <?php echo $row['username']?>
                    </option> 
                <?php }?>               
            </select>
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
                <button type='submit' class='btn btn-info' onclick='openForm()'>Cancel</button>
                <button type='submit' class='btn btn-warning' name='productOk'>OK</button>
            </div>
    </form>
</div>
<script>
    let formContainer =document.querySelector('.form-container');
    const openForm =()=>{
        formContainer.classList.toggle('form-show');
    }
    
</script>


