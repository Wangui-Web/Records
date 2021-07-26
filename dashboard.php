<?php
include('header2.php');
include('includes/dbh.inc.php');


$value =0;
$sql ="SELECT productSelling,productQtyLeft FROM products";
$result =mysqli_query($conn,$sql);
while($row =mysqli_fetch_all($result,MYSQLI_ASSOC)){
    for ($i=0; $i < count($row); $i++) { 
        $value += (($row[$i]['productSelling']) * ($row[$i]['productQtyLeft']));
    }
$_SESSION['totalValue'] =$value;
}
           
?>
<main>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="col">
                <div class="row-sm-4">
                    <div class="card text-center" style='background-color:rgba(255,165,0,0.8)'>
                        <div class="card-body">
                            <h3 class="card-title">TOTAL PROFIT </h3>
                            <p class="card-text">The total profit today is:</p>
                            <a href="transaction.php" class='btn btn-primary'><h4>Ksh <?php echo $_SESSION['totalValue']?></h4></a>
                        </div>
                    </div>
                </div><br>
                <div class="row-sm-4"></div>
                <div class="row-sm-4">
                    <div class="card text-center" style='background-color:rgba(220,20,60,0.7)'>
                        <div class="card-body">
                            <h3 class="card-title">TOTAL INVENTORY VALUE</h3>
                            <p class="card-text">The total Inventory value as of today is:</p>
                            <a href="products.php" class='btn btn-primary'><h4>Ksh <?php echo $_SESSION['totalValue']?></h4></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="jumbotron" style='width:100%;height:90%'>
                <div class="text-center">
                    <i class="fa fa-user fa-4x"></i>
                    <h3>Welcome <span><?php echo $_SESSION['userName']?></span></h3>
                    <p>Today is:</p><p id=dateToday></p>
                </div>
            </div>
        </div>
    </div><br>
    <div class="row" id='btm-row'>
        <div class="col-sm-4">
            <div class="card text-center" style='background-color:#f1f1f1'>
                <div class="card-body">
                    <h3 class="card-title">SUPPLIERS</h3>
                    <p class="card-text">Add and Manage your suppliers</p>
                    <div class="btn-container">
                        <a href="suppliers.php" type='button' class='btn btn-primary' name='suppliers'><i class='fa fa-plus'></i>Add Supplier
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card text-center" style='background-color:#f1f1f1'>
                <div class="card-body">
                    <h3 class="card-title">INVENTORY</h3>
                    <p class="card-text">Add and Manage your Inventory</p>
                    <div class="btn-container">
                        <a href="category.php" class='btn btn-primary'>Category</a>
                        <a href="products.php" class='btn btn-primary'>Products</a>                    
                    </div>
                </div>
            </div>            
        </div>
        <div class="col-sm-4">
            <div class="card text-center" style='background-color:#f1f1f1'>
                <div class="card-body">
                    <h3 class="card-title">Checkout</h3>
                    <p class="card-text">Add and Manage your customers Checkout</p>
                    <div class="btn-container">
                        <a href='checkout.php' type='button' class='btn btn-primary' name='checkout'>Checkout Customers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<script>
    const today = new Date();
    document.querySelector('#dateToday').innerHTML = today;
</script>