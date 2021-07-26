<?php
include 'header2.php';
include 'includes/dbh.inc.php';
mysqli_report(MYSQLI_REPORT_ERROR |MYSQLI_REPORT_STRICT);



if(isset($_GET['delete'])){
    $id =$_GET['delete'];
    $sql="DELETE FROM transactions WHERE transactionsID =$id";
    if(!mysqli_query($conn,$sql)){
       $_SESSION['message']='Failed to connect to db'.mysqli_error($conn); 
       $_SESSION['msg-type']='danger';
    }else{
        $_SESSION['message']='The information has been deleted';
        $_SESSION['msg-type']='success';
        
    }
}
if(isset($_POST['deleteAll'])){
    $sql="DELETE FROM transactions";
    if(!mysqli_query($conn,$sql)){
        $_SESSION['message']='Failed to connect to db'.mysqli_error($conn); 
        $_SESSION['msg-type']='danger';
    }else{
        $_SESSION['message']='The transactions records have all been deleted';
        $_SESSION['msg-type']='success';
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
<div class='container-fluid' style='margin:20px'>
    <h2 class='text-center'>Transaction Details</h2>

    
    <table class='table'>
        <thead class='thead-dark'>
            <tr>
                <th scope='col'>Customer Name </th>
                <th scope='col'>Recorded At</th>
                <th scope='col'>Payment Method</th>
                <th scope='col'>Amount Due</th>
                <th scope='col'>Amount Paid</th>
                <th scope='col'>Change</th>
                <th scope='col'>Total Profit</th>
                <th scope='col'>Manage</th>
            </tr>
        </thead>
        
        <?php 
            $sql = "SELECT * FROM transactions";
            $result =mysqli_query($conn,$sql);
            while($row =mysqli_fetch_assoc($result)){?>
                
                <tbody>
                    <tr>
                        <td><?php echo $row['customerName']?></td>
                        <td><?php echo $row['recordedAt']?></td>
                        <td><?php echo $row['paymentMethod']?></td>
                        <td><?php echo $row['payable']?></td>
                        <td><?php echo $row['paid']?></td>
                        <td><?php echo $row['due']?></td>
                        <td><?php echo $row['totalProfit']?></td>
                        <td>
                            <a href='transaction.php?delete=<?php echo $row['transactionsID']?>' class='btn btn-danger' style=cursor:pointer;margin-right:10px><i class='far fa-trash-alt'></i>Delete</a>
                        </td>
                    </tr>
                </tbody>
            <?php }?>
    </table>
    <div style="width:100%;display:flex;justify-content:space-around;">
        <a href="#" class='btn btn-warning'><?php 
            $totalEarned=0;
            $sql="SELECT payable FROM transactions";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_all($result,MYSQLI_ASSOC);
            for ($i=0; $i <count($row) ; $i++) { 
                $totalEarned += $row[$i]['payable'];
                
            }echo "<h4>Total Amount Earned: ".$totalEarned."</h4>";

        ?>
        </a>
        <button type='submit' class='btn btn-danger' name='deleteAll'><h4>Delete All Records</h4></button>
        <a href="#" class='btn btn-info'><?php 
            $total=0;
            $sql="SELECT totalProfit FROM transactions";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_all($result,MYSQLI_ASSOC);
            for ($i=0; $i <count($row) ; $i++) { 
                $total += $row[$i]['totalProfit'];
                
            }echo "<h4>Total Profit Earned: ".$total."</h4>";
        ?></a>
        
    </div>
</div>

