<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 2/27/15
 * Time: 11:41 AM
 */

$GLOBALS['title']="Payment-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/handyCam.php');
require('./../../inc/fpdf.php');
$ses = new \sessionManager\sessionManager();
$ses->start();
$loginId=$ses->Get("userIdLoged");
$loginGrp=$ses->Get("userGroupId");
$display="";
$displaytable="none";
$disBtnPrint="none";

$GLOBALS['isData']="0";
if($loginGrp=="UG001") {
    $GLOBALS['Name'] = "";
    $disBtnPrint2="none";
}
else
{
    $disBtnPrint2="";
    $GLOBALS['Name'] = "";
}
$ses->remove("UserIddrp");
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else
{

    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    if(isset($_GET['id']) && $_GET['wtd']==="delete")
    {
        if ($msg = "true") {


            $result = $db->delete("delete from stdpayment where serial='".$_GET['id']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Payment Deleted Successfully.");
                                window.location.href = "view.php";
                        </script>';
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $result . '");window.location.href = "view.php";</script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");window.location.href = "view.php";</script>';
        }

    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnUpdate"])) {
            $ses->Set("UserIddrp",$_POST['person']);
            getTableData($loginGrp, $_POST['person'], $db);
            $displaytable = "";
            $disBtnPrint="";
        }
        elseif (isset($_POST["btnPrint"])) {

            if($loginGrp=="UG001"){
                $ses->Set("UserIddrp",$_POST['person']);
            }
            else
            {
                $ses->Set("UserIddrp",$ses->Get("userIdLoged"));
            }
            printData($db);
        }
        elseif (isset($_POST["btnUpdatePay"])) {

            if ($msg = "true") {

                $handyCam = new \handyCam\handyCam();
                $serial = $ses->Get("serial");

                $data = array(

                    'transDate' => $handyCam->parseAppDate($_POST['paydate']),
                    'paymentBy' => $_POST['paidby'],
                    'transNo' => $_POST['transno'],
                    'amount' => floatval($_POST['amount']),
                    'remark' => $_POST['remark'],
                    'isApprove' => "Yes",


                );
                $result = $db->updateData("stdpayment", "serial", $serial, $data);

                if ($result === "true") {
                    echo '<script type="text/javascript"> alert("Payment Updated Successfully.");
                                window.location.href = "view.php";
                        </script>';


                }
                  else {
                echo '<script type="text/javascript"> alert("' . $result . '");window.location="view.php";</script>';
                }
                $ses->remove("serial");


        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");window.location="view.php";</script>';
        }

    }



    }



    if($loginGrp=="UG004"){
        getTableData($loginGrp,$loginId,$db);
        $display="none";
        $displaytable="";
    }

    $result = $db->getData("SELECT userId,name FROM studentinfo  where isActive='Y'");
    $GLOBALS['output1']='';
    if(false===strpos((string)$result,"Can't"))
    {
        while ($row = mysql_fetch_array($result)) {
            $GLOBALS['isData1']="1";
            $GLOBALS['output1'] .= '<option value="'.$row['userId'].'">'.$row['name'].'</option>';

        }

    }
    else
    {
        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
    }



}
function getTableData($logGRP,$userId,$db)
{

    if ($msg = "true") {
        $handyCam = new \handyCam\handyCam();
        $data = array();
        if($logGRP==="UG004")
        {
            $query = "SELECT a.serial,b.name,a.transDate,a.paymentBy ,a.transNo,a.amount,a.remark,a.isApprove FROM stdpayment as a,studentinfo as b where a.userId='" . $userId . "' and a.userId=b.userId and b.isActive='Y'";
        }
        else {
            $query = "SELECT a.serial,b.name,a.transDate,a.paymentBy ,a.transNo,a.amount,a.remark FROM stdpayment as a,studentinfo as b where a.userId='" . $userId . "' and a.userId=b.userId and a.isApprove='Yes' and b.isActive='Y'";
        }
      //  var_dump($query);
        $result = $db->getData($query);
        $GLOBALS['output']='';
        // var_dump($result);
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output'].='<div class="table-responsive">
                                <table id="paymentList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th>Name</th>
                                             <th>Payment Date</th>
                                             <th>Paid By</th>
                                             <th>Transection/Mobile No</th>
                                             <th>Amount</th>
                                             <th>Remark</th>';
            if($logGRP!=="UG004") {
                $GLOBALS['output'].=  '<th > Action</th >';
            }else
            {
                $GLOBALS['output'].=' <th>Is Approve</th>';
            }



        $GLOBALS['output'].=              ' </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData']="1";
                $GLOBALS['output'] .= "<tr>";

                $GLOBALS['output'] .= "<td>" . $row['name'] . "</td>";
                $GLOBALS['output'] .= "<td>" .$handyCam->getAppDate($row['transDate']) . "</td>";

                $GLOBALS['output'] .= "<td>" . $row['paymentBy'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['transNo'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['amount'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['remark'] . "</td>";

                if($logGRP!=="UG004")
                {
                    $GLOBALS['output'] .= "<td><a title='Edit' class='btn btn-success btn-circle editBtn' href='#".$row['serial']."'><i class='fa fa-pencil'></i></a>&nbsp&nbsp<a title='Delete' class='btn btn-danger btn-circle' href='view.php?id=" . $row['serial'] ."&wtd=delete'"."><i class='fa fa-trash-o'></i></a></td>";
                }
                else
                {
                    $GLOBALS['output'] .= "<td>" . $row['isApprove'] . "</td>";
                }

                $GLOBALS['output'] .= "</tr>";

            }

            $GLOBALS['output'].=  '</tbody>
                                </table>
                            </div>';


        }
        else
        {
            echo '<script type="text/javascript"> alert("' . $result . '");window.location="view.php";</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");window.location="view.php";</script>';
    }



}
function  printData($db)
{
    $ses = new \sessionManager\sessionManager();
    $usId= $ses->Get("UserIddrp");
    class PDF extends FPDF
    {
        function Header()
        {
            // Logo
            $this->Image('./../../dist/images/logo.png',10,6,30,20);
            $title="DIU HOSTEL";
            $subtitle="4/2,Sobhanbag,Mirpur Road,Dhaka-1207";
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
            $w = array(40,40,40,70);
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
                $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
                $this->Cell($w[2],6,number_format($row[2]).'/-','LR',0,'R',$fill);
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
    $dataall =LoadData($db,$usId);
    $billhead ="Payment By: ".$GLOBALS["Name"];
    $w = $pdf->GetStringWidth($billhead)+4;
    $pdf->SetLeftMargin(50);
    $pdf->Cell($w,10,$billhead,0,1,'L',true);
    $pdf->Ln(5);
    $pdf->SetX(10);
    $header = array('Payment Date', 'Payment By','Amount','Remark');
   // $dataall =LoadData($db,$usId);
    $pdf->SetFont('Arial','',14);
    $pdf->FancyTable($header,$dataall);
    $pdf->Output("payment.pdf");
   echo '<script> window.open("payment.pdf", "_blank");</script>';

   // header("location: payment.pdf");

}
function LoadData($db,$userId)
{
    $query = "SELECT a.serial,b.name,a.transDate,a.paymentBy ,a.transNo,a.amount,a.remark,a.isApprove FROM stdpayment as a,studentinfo as b where a.userId='" . $userId . "' and a.userId=b.userId and b.isActive='Y'";
    $result = $db->execDataTable($query);
    $paydata = array();
    $handyCam = new \handyCam\handyCam();
    while ($row = mysql_fetch_array($result)) {

        $GLOBALS['Name']=$row["name"];
        $rowd=array();
        array_push($rowd,$handyCam->getAppDate($row["transDate"]));
        array_push($rowd,$row["paymentBy"]);
        array_push($rowd,$row["amount"]);
        array_push($rowd,$row["remark"]);
        array_push($paydata,$rowd);

    }

    return $paydata;
}

if($loginGrp==="UG004"){

    include('./../../smater.php');

}
elseif($loginGrp==="UG003")

{

    include('./../../emaster.php');
}
else
{
    include('./../../master.php');
}

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Payment View</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i><i class="fa fa-hand-o-right"></i> Student Payment View
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form name="apyment" action="view.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-info" style="display:<?php echo $disBtnPrint2;?>;" name="btnPrint" ><i class="fa fa-print"></i>Print</button>
                    </form>
                    <form name="apyment" action="view.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">
                        <div class="row" id="divview" style="display:<?php echo $display;?>">
                            <div class="col-lg-12">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Student Name</label>
                                        <select class="form-control" name="person" required="">
                                            <?php echo $GLOBALS['output1'];?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-success" name="btnUpdate" ><i class="fa fa-check-circle-o"></i>View</button>
                                            <button type="submit" class="btn btn-info" style="display:<?php echo $disBtnPrint;?>;" name="btnPrint" ><i class="fa fa-print"></i>Print</button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                    <div id="editpayment" class="" style="display:none">
                        <form name="apyment" action="view.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-lg-12">
                                 <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Payment Date</label>
                                        <div class="input-group date" id='dp1'>

                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                            <input id="paydate" type="text" placeholder="Payment Date" class="form-control datepicker" name="paydate" required  data-date-format="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Paid By</label>
                                        <select id="payby" class="form-control" name="paidby" required="">

                                            <option value="Bank">Bank</option>
                                            <option value="DBBL">DBBL</option>
                                            <option value="Bkash">BKash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Transection/Mobile No</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i> </span>
                                            <input id="transno" type="text" placeholder="Transecton or Mobile no" class="form-control" name="transno" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">


                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Amount</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-money"></i> </span>
                                            <input id="amount" type="text" placeholder="Amount" class="form-control" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Remark</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                            <input id="remark" type="text" placeholder="Additional Info" class="form-control" name="remark" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>&nbsp;</label>
                                        <div class="input-group">

                                            <button type="submit" class="btn btn-success pull-right" name="btnUpdatePay" ><i class="fa fa-2x fa-check"></i>Update</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                         </form>
                        </div>

                        <div class="row" style="display:<?php echo $displaytable;?>">
                        <div class="col-lg-12">
                            <hr />
                            <?php if($GLOBALS['isData']=="1"){echo $GLOBALS['output'];}
                            else
                            {
                                echo "<h1 class='text-warning'>Payment Data Not Found!!!</h1>";
                            }
                            ?>
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
        $('.editBtn').on('click', function(){

            $('#divview').hide();
            $('#editpayment').show();

            var serial=$(this).attr('href').substring(1);

            $('#paydate').val($(this).closest("tr").find("td").eq('1').text());
            $('#payby').val($(this).closest("tr").find("td").eq('2').text());
            $('#transno').val($(this).closest("tr").find("td").eq('3').text());
            $('#amount').val($(this).closest("tr").find("td").eq('4').text());
            $('#remark').val($(this).closest("tr").find("td").eq('5').text());

            $.ajax({
                type: 'POST',
                url: '/hms/sesboss.php',
                data: {'serial': serial},
                success: function (msg) {
                    // alert(msg);
                },
                error: function (err){
                    console.log(err);
                    alert('Error');
                }
            });

        });

        $("select option").filter(function() {

            return $(this).val() =='<?php echo $uid=$ses->Get("UserIddrp");?>';
        }).prop('selected', true);

    });




</script>
