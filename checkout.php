<?php
include 'header2.php';
include 'includes/dbh.inc.php';

$qtyRequired=$customerName=$productName=$categoryName=$measurement=$price=$totalPrice=$payment=$paid=$due='';
$alert1=$alert2=$alert4='';
$id=$totalAmount=$totalProfits=0;

$sql="SELECT totalPrice FROM customer";
$result= mysqli_query($conn,$sql);
$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
for ($i=0; $i < count($row); $i++) { 
    $totalAmount += $row[$i]['totalPrice'];
}

$sql="SELECT profit FROM customer";
$result= mysqli_query($conn,$sql);
$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
for ($i=0; $i < count($row); $i++) { 
    $totalProfits += $row[$i]['profit'];
}

if(isset($_GET['delete'])){
    $id =$_GET['delete'];
    $sql="DELETE FROM customer WHERE customerID =$id";
    if(!mysqli_query($conn,$sql)){
       $_SESSION['message']='Failed to connect to db'.mysqli_error($conn); 
       $_SESSION['msg-type']='danger';
    }else{
        $_SESSION['message']='The information has been deleted';
        $_SESSION['msg-type']='success';
    }
}

if(isset($_POST['checkoutPayment'])){

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $customerName=htmlspecialchars($_POST['customerName']);
    $payment=htmlspecialchars($_POST['payment']);
    $paid=htmlspecialchars($_POST['paid']);
    $due= $paid-$totalAmount;

    if(empty($customerName) || empty($paid)||empty($payment)){
        $_SESSION['message']='How much did the customer pay or Which means did the customer use to pay?';
        $_SESSION['msg-type']='danger';
    }else if(!(empty($customerName) && empty($paid) && empty($payment))){
        if(!preg_match('/^[\d]+$/',$paid)){
            $alert2='Invalid amount entered';
        }if(!preg_match('/^[a-z\s]+$/i',$customerName)){
            $alert2='Invalid amount entered';
        }
        if(preg_match('/^[\d]+$/',$paid) && preg_match('/^[a-z\s]+$/i',$customerName)){
            $sql="INSERT INTO checkout(customerName,checkoutMethod,checkoutPaid,checkoutDue) VALUE(?,?,?,?)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                echo 'Error during connection'.mysqli_error($conn);
            }else{
                mysqli_stmt_bind_param($stmt,'ssii',$customerName,$payment,$paid,$due);
                mysqli_stmt_execute($stmt);

                $sql ="INSERT INTO transactions(customerName,paymentMethod,payable,paid,due,totalProfit) VALUE(?,?,?,?,?,?)";

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    echo 'Error during connection to db'.mysqli_error($conn);
                }else{
                    mysqli_stmt_bind_param($stmt,'ssiiii',$customerName,$payment,$totalAmount,$paid,$due,$totalProfits);
                    mysqli_stmt_execute($stmt);   
                    $_SESSION['message']='The infomation has been saved';
                    $_SESSION['msg-type']='success';
                }

                $sql="DELETE FROM customer";
                if(!mysqli_query($conn,$sql)){
                    $_SESSION['message']='Failed to connect to db'.mysqli_error($conn); 
                    $_SESSION['msg-type']='danger';
                }else{
                    $_SESSION['message']='The transaction has been recorded';
                    $_SESSION['msg-type']='success';
                }
            }
        }
    }
}

?>
<?php 
if(isset($_SESSION['message'])){?>
<div class='alert alert-<?php echo $_SESSION['msg-type']?>'>
    <?php 
    echo $_SESSION['message'];
    unset($_SESSION['message']);
    ?>
</div>
<?php }?>
<div class="container-fluid" style="margin-top:20px;">
<!--Products Available Table-->
    <div style="width:51%; float:left;">
        <h3>Products Available</h3>
        <div style="max-height:450px;overflow:scroll;border:2px solid black;">
            <table class='table'>
                <thead class='thead-dark'>
                    <tr>
                        <th scope='col'>Name</th>
                        <th scope='col'>Supplier</th>
                        <th scope='col'>Quantity Available</th>
                        <th scope='col'>Measurement</th>
                        <th scope='col'>Selling Price</th>
                        <th scope='col'>Profit</th>
                        <th scope='col'>Add To Cart</th>
                    </tr>
                </thead>
                <?php 

                    $sql = "SELECT * FROM products";
                    $result =mysqli_query($conn,$sql);
                    while($row =mysqli_fetch_assoc($result)){?>
                        <tbody>
                            <tr>
                                <td><?php echo $row['productName']?></td>
                                <td><?php echo $row['productSupplier']?></td>
                                <td><?php echo $row['productQtyLeft']?></td>
                                <td><?php echo $row['productMeasure']?></td>
                                <td><?php echo $row['productSelling']?></td>
                                <td><?php echo $row['productProfit']?></td>
                                <td >
                                    <a href='checkoutCart.php?cart=<?php echo $row['productID']?>' style='border:none;outline:none;background:transparent;cursor:pointer;'><i class='fas fa-shopping-cart'></i></a>
                                    
                                </td>
                            </tr>
                        </tbody>
                    <?php }?>
                </table> 
        </div>
    </div>
    <!--Add to Cart Table-->
    <div style="width:48%;float:right;">
        <h3>Add to Cart</h3>
        <div style="max-height:300px;overflow:scroll;border:2px solid black;">
            <table class='table'>
                <thead class='thead-dark'>
                    <tr>
                        <th scope='col'>Product Name</th>
                        <th scope='col'>Category Name</th>
                        <th scope='col'>Measurement</th>
                        <th scope='col'>Quantity Required</th>
                        <th scope='col'>Price per Item</th>
                        <th scope='col'>Total Price</th>
                        <th scope='col'>Manage</th>
                    </tr>
                </thead>
                <?php 
                    $sql = "SELECT * FROM customer";
                    $result =mysqli_query($conn,$sql);
                    while($row =mysqli_fetch_assoc($result)) {
                        ?>
                        
                        <tbody>
                            <tr>
                                <td><?php 
                                    echo $row['productName']
                                ?></td>
                                <td><?php echo $row['categoryName'];?></td>
                                <td><?php echo $row['measurement']?></td>                                    
                                <td><?php echo $row['qtyRequired']?></td>
                                <td><?php echo $row['price']?></td>                                   
                                <td><?php echo $row['qtyRequired']*$row['price']?></td>
                                <td>
                                    <a href='checkout.php?delete=<?php echo $row['customerID']?>' class='btn btn-danger' style=cursor:pointer;margin-right:10px>Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    <?php }?>
            </table>    
        </div>

        
        <form action="checkout.php" method="POST">
            <div style="margin-left:150px;display:flex;justify-content:center;flex-direction:column;width:60%">
                <label for='customerName'>Customer Name:</label>
                <input type='text' name='customerName' id="customerName">
                
                <label for="payment">Payment Method:</label>
                <select name="payment" id="payment">
                    <option>Cash</option>
                    <option>M-Pesa</option>
                </select>

                <label>Total Amount Paid:</label>
                <input type="text" name='paid'>
                <p class="text-danger"><?php echo $alert2?></p>
                
                <div style="display:flex; justify-content:center;margin-top:10px;"><h5>Total Amount Due:<?php echo 'Ksh'.$totalAmount?></h5></div>

                <div style="display:flex; justify-content:center;margin-top:10px;"><h5>Total Amount Paid: <?php echo 'Ksh'.$paid?></h5></div>

                <div style="display:flex; justify-content:center;margin-top:10px;"><h5>Total Change: <?php echo 'Ksh'.$due?></h5></div>

                <div style="display:flex; justify-content:center;margin-top:10px;"><h5>Total Profit: <?php echo 'Ksh'.$totalProfits?></h5></div>

                <button style="margin:10px 0px 10px 100px;width:60%;" type='submit' class='btn btn-primary' name='checkoutPayment'>Checkout</button>
            </div>
        </form>
    </div>
</div>

