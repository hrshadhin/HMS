<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 10:56 AM
 */

?>
<?php

$GLOBALS['title']="Cost-HMS";
$base_url="http://localhost/hms/";
$GLOBALS['output']='';
$GLOBALS['isData']="";
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/handyCam.php');
require('./../../inc/fpdf.php');
$ses = new \sessionManager\sessionManager();
$ses->start();
$name=$ses->Get("name");
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnPrint"])) {

        $db = new \dbPlayer\dbPlayer();
        printData($db);
       // header( 'Location: view.php');
    }
    else
    {
        header( 'Location: view.php');
    }

}

    $name=$ses->Get("loginId");
    $msg="";
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {
        $handyCam = new \handyCam\handyCam();
        $data = array();
        $result = $db->getData("SELECT * FROM cost");
        $GLOBALS['output']='';
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output'].='<div class="table-responsive">
                                <table id="paymentList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th>Cost Type</th>
                                             <th>Amount</th>
                                            <th>Description</th>
                                             <th>Date</th>
                                              <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData']="1";
                $GLOBALS['output'] .= "<tr>";

                $GLOBALS['output'] .= "<td>" . $row['type'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['amount'] . "</td>";

                $GLOBALS['output'] .= "<td>" . $row['description'] . "</td>";

                $GLOBALS['output'] .= "<td>" .$handyCam->getAppDate($row['date']). "</td>";
                $GLOBALS['output'] .= "<td><a title='Edit' class='btn btn-success btn-circle' href='edit.php?id=" . $row['serial'] ."&wtd=edit'"."><i class='fa fa-pencil'></i></a>&nbsp&nbsp<a title='Delete' class='btn btn-danger btn-circle' href='edit.php?id=" . $row['serial'] ."&wtd=delete'"."><i class='fa fa-trash-o'></i></a></td>";
                $GLOBALS['output'] .= "</tr>";

            }

            $GLOBALS['output'].=  '</tbody>
                                </table>
                            </div>';


        }
        else
        {
            echo '<script type="text/javascript"> alert("' . $result . '");</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
    }




function  printData($db)
{


    class PDF extends FPDF
    {
        function Header()
        {
            // Logo
            $this->Image('./../../dist/images/logo.png',10,6,30,20);
              $title="HRS HOSTEL";
            $subtitle="Localhost,Mirpur Road,Dhaka-1207";
            $this->Cell(80);
            // Arial bold 15
            $this->SetFont('Arial','B',16);
            // Calculate width of title and position
            $w = $this->GetStringWidth($title)+6;
            $this->SetX((210-$w)/2);

            $this->SetTextColor(0,122,195);

            $this->Cell($w,9,$title,0,1,'C');

            $this->Cell(80);
            // Arial bold 15
            $this->SetFont('Arial','',12);
            // Calculate width of title and position
            $w = $this->GetStringWidth($subtitle)+6;
            $this->SetX((210-$w)/2);

            $this->SetTextColor(0,122,195);
            $this->Cell($w,9,$subtitle,0,1,'C');
        }

// Page footer
        function Footer()
        {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','B',8);
            $this->SetTextColor(0);
            // Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}     Print Date:'.date("d/m/Y"),0,0,'C');
        }
        function FancyTable($header, $data)
        {
            // Colors, line width and bold font
            $this->SetFillColor(0,166,81);
            $this->SetTextColor(255);
            $this->SetDrawColor(128,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');
            // Header
            $w = array(40,30,70,40);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
            $this->Ln();
            // Color and font restoration
            $this->SetFillColor(224,235,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Data
            $fill = false;
            foreach($data as $row)
            {
                $this->SetX(10);
                $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
                $this->Cell($w[1],6,number_format($row[1]).'/-','LR',0,'R',$fill);
                $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
                $this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);

                $this->Ln();
                $fill = !$fill;
            }
            $this->SetX(10);
            // Closing line
            $this->Cell(array_sum($w),0,'','T');
        }
    }

// Instanciation of inherited class
    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->SetTextColor(0,0,0);
    $dataall =LoadData($db);
    $billhead ="Hostel Cost List";
    $w = $pdf->GetStringWidth($billhead)+4;
    $pdf->SetLeftMargin(80);
    $pdf->Cell($w,10,$billhead,0,1,'L',true);
    $pdf->Ln(5);
    $pdf->SetX(10);
    $header = array('Type','Amount','Description','Date');
    // $dataall =LoadData($db,$usId);
    $pdf->SetFont('Arial','',14);
    $pdf->FancyTable($header,$dataall);
    $pdf->Output("cost.pdf");
    echo '<script> window.open("cost.pdf", "_blank");</script>';
   //  header("location: view.pdf");

}
function LoadData($db)
{
    $msg= $db->open();
    $query = "SELECT * FROM cost";
    $result = $db->execDataTable($query);
    $paydata = array();
    $handyCam = new \handyCam\handyCam();
    while ($row = mysql_fetch_array($result)) {

        $rowd=array();

        array_push($rowd,$row["type"]);
        array_push($rowd,$row["amount"]);
        array_push($rowd,$row["description"]);
        array_push($rowd,$handyCam->getAppDate($row["date"]));
        array_push($paydata,$rowd);

    }

    return $paydata;
}
?>
<?php include('./../../master.php'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Cost View</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Hostel Cost List View
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <form name="apyment" action="view.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">
                                <button type="submit" class="btn btn-info pull-right"  name="btnPrint" ><i class="fa fa-print"></i>Print</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <hr />
                            <?php if($GLOBALS['isData']=="1"){echo $GLOBALS['output'];}?>
                        </div>
                    </div>


                </div>
                <!-- /.panel-body -->
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>

</div>
<!-- /#page-wrapper -->


<?php include('./../../footer.php'); ?>
<script type="text/javascript">
    $( document ).ready(function() {



        $('#paymentList').dataTable();
    });




</script>
