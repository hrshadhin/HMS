<?php

/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/8/15
 * Time: 7:39 PM
 */
$GLOBALS['title']="Bill-HMS";
$base_url="http://localhost/hms/";
$GLOBALS['output']='';
$GLOBALS['isData']="0";
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/fpdf.php');


$ses = new \sessionManager\sessionManager();
$ses->start();
$loginId=$ses->Get("userIdLoged");
$loginGrp=$ses->Get("userGroupId");

if(isset($_POST["btnPrint"])) {



        $billId= $ses->Get("billId");
        class PDF extends FPDF
        {
// Page header
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
            $w = array(100,40);
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
        $data=billInfo($billId);
        $billhead ="Bill Id: ".$billId."     Bill To: ".$data[0]."    Bill Date: ".$data[1];
        $w = $pdf->GetStringWidth($billhead)+4;
        $pdf->SetLeftMargin(50);
        $pdf->Cell($w,10,$billhead,0,1,'L',true);
    $pdf->Ln(5);
    $pdf->SetX(10);
    $header = array('Type', 'Amount');
    $dataall =LoadData($billId);
    $pdf->SetFont('Arial','',14);
    $pdf->FancyTable($header,$dataall);
    $pdf->SetX(110);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0,122,195);

    $totalbill ="Total Bill: ".$data[2].'/-';
    $w = $pdf->GetStringWidth($totalbill)+4;
    $pdf->Cell($w,10,$totalbill,0,1,'C');


        $pdf->Output("bill.pdf");
        header("location: bill.pdf");
       // $userId = $ses->Get("userId");








}
elseif (isset($_GET['billId'])) {

       $billId=$_GET['billId'];
      $ses->Set("billId",$billId);
      $billData = array();
     $billInfo = array("","",0.00);
        $db = new \dbPlayer\dbPlayer();
        $msg = $db->open();
        if ($msg = "true") {


            $result = $db->execDataTable("SELECT a.billId,b.name,a.type,a.amount,DATE_FORMAT(a.billingDate,'%D %M,%Y') as date from billing as a,studentinfo as b where a.billTo=b.userId and  a.billId='" . $billId . "'");


            if (false === strpos((string)$result, "Can't")) {


                $GLOBALS['output'].='<div class="table-responsive">
                                <table id="billList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th>Bill Type</th>

                                            <th>Amount</th>

                                        </tr>
                                    </thead>
                                    <tbody>';
                while ($row = mysql_fetch_array($result)) {
                    $billInfo[0]=$row["name"];
                    $billInfo[1]=$row["date"];
                    $billInfo[2]=$billInfo[2]+$row["amount"];
                    $GLOBALS['isData']="1";
                    $GLOBALS['output'] .= "<tr>";


                    $GLOBALS['output'] .= "<td>" . $row['type'] . "</td>";
                    $GLOBALS['output'] .= "<td>" . $row['amount'] . "/-</td>";

                    $GLOBALS['output'] .= "</tr>";

                }

                $GLOBALS['output'].=  '</tbody>
                                </table>
                            </div>';



            } else {
                echo '<script type="text/javascript"> alert("Bill Info Not Present.");
                    window.location.href = "view.php";
                        </script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");
               window.location.href = "view.php";
               </script>';
        }

    }
else
{
    header("location: view.php");
}

if($loginGrp==="UG004"){

include('./../../smater.php');

}
else
{
include('./../../master.php');
}

function billInfo($billId)
{
     $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {
    $result = $db->execDataTable("SELECT a.billId,b.name,sum(a.amount) as total,DATE_FORMAT(a.billingDate,'%D %M,%Y') as date from billing as a,studentinfo as b where a.billTo=b.userId and  a.billId='" . $billId . "'");
    $billInfo = array("","",0.00);
  while ($row = mysql_fetch_array($result)) {
    $billInfo[0] = $row["name"];
    $billInfo[1] = $row["date"];
    $billInfo[2] = $row["total"];
  }
        return $billInfo;
    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");

        </script>';

    }
}
function LoadData($billId)
{
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {
        $result = $db->execDataTable("SELECT a.type,a.amount from billing as a  where  a.billId='" . $billId . "'");
        $billdata = array();
        while ($row = mysql_fetch_array($result)) {
            $rowd=array();
            array_push($rowd,$row["type"]);
            array_push($rowd,$row["amount"]);
            array_push($billdata,$rowd);

        }

        return $billdata;
    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");

        </script>';

    }
}

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Bill Info</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading text-success">
                    <i class="fa fa-info-circle fa-fw"></i>Bill Info of <label class="text-success">[<?php echo $billId?>]</label>
                    <a class="btn btn-info pull-right" href="view.php"><i class="fa fa-reply">Back To View</i></a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                        <div class="row">
                            <div class="col-lg-12">
                            <div class="col-lg-6">
                                <p class=" text-info text-left"><strong>Bill To:</strong> <?php echo $billInfo[0]; ?></p>
                                </div>
                            <div class="col-lg-6">
                                <p class=" text-info text-right"><strong>Bill Date:</strong> <?php echo $billInfo[1]; ?></p>
                                </div>


                            </div>
                        </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo $GLOBALS['output'];


                            ?>

                            </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-6">
                              <form action="single.php" method="post" enctype="multipart/form-data">
                                  <button class="btn btn-info" type="submit" name="btnPrint"><i class="fa fa-print">Print</i></button>
                              </form>
                                </div>

                            <div class="col-lg-6">


                        <div class="col-lg-6">
                            <p class="text-right"><strong>Total Amount:</strong></p>

                        </div>
                            <div class="col-lg-6">
                                <p class="text-left"><strong><?php echo number_format((float)$billInfo[2], 2, '.', '').'/-';?></strong></p>

                            </div>
                            </div>

                            </div>


                        </div>

            </div>
                </div>

        </div>

    </div>


    <?php include('./../../footer.php'); ?>




