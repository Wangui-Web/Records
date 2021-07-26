<?php
include 'header2.php';
include 'includes/dbh.inc.php';

if(isset($_POST['inventoryReport'])){
    require('fpdf/fpdf.php');
    $title='Inventory Report';

    $pdf = new FPDF();
    $pdf -> AddPage();
    $pdf ->SetTitle($title);
    $pdf ->SetFont('Arial','B',15);
    //cell
    $pdf -> Cell(0,10,$title,1,1,'C');
    $pdf ->Output();
}

?>
<h2 class='text-center'>Generate Reports</h2>

<div style="width:100%;display:flex;justify-content:space-around;margin-top:20px;">
    <a href="#" class='btn btn-warning'><h5>Generate Transaction Report</h5></a>
    <button class='btn btn-danger' name='inventoryReport'><h5>Generate Inventory Report</h5></button>
</div>