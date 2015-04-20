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

$ses = new \sessionManager\sessionManager();
$ses->start();
$name=$ses->Get("name");
$loginId=$ses->Get("userIdLoged");
$loginGrp=$ses->Get("userGroupId");
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else
{



    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnSave"])) {

            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();

            if ($msg = "true") {

                    $handyCam = new \handyCam\handyCam();
                     $userId="";
                     $isApprove="";
                     if($loginGrp==="UG004")
                     {
                         $userId=$loginId;
                         $isApprove="No";
                     }
                     else
                     {
                         $userId=$_POST['person'];
                         $isApprove="Yes";
                     }
                    $data = array(
                        'userId' => $userId,
                        'transDate' => $handyCam->parseAppDate($_POST['paydate']),
                        'paymentBy' => $_POST['paidby'],
                        'transNo' => $_POST['transno'],
                        'amount' => floatval($_POST['amount']),
                        'remark' => $_POST['remark'],
                        'isApprove'=>$isApprove,


                    );
                    $result = $db->insertData("stdpayment", $data);

                    if (is_numeric($result)) {

                        //  $db->close();
                        echo '<script type="text/javascript"> alert("Payment Added Successfully.");window.location="add.php";</script>';
                    } elseif (strpos($result, 'Duplicate') !== false) {
                        echo '<script type="text/javascript"> alert("Payment Already Exits!!!");window.location="add.php"; </script>';
                        getData();
                    } else {
                        echo '<script type="text/javascript"> alert("' . $result . '");window.location="add.php";</script>';
                    }


            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $msg . '");window.location="add.php";</script>';
            }
        }
    }
    else
    {
        if($loginGrp=="UG004"){

                    $GLOBALS['output']='';
                    $GLOBALS['isData']="1";
                    $GLOBALS['output'] .= '<option value="'.$loginId.'">'.$name.'</option>';
        }
        else
        {
            getData();
        }

    }


}
function getData()
{
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    $data = array();
    $result = $db->getData("SELECT userId,name FROM studentinfo  where isActive='Y'");
    $GLOBALS['output']='';
    if(false===strpos((string)$result,"Can't"))
    {
        while ($row = mysql_fetch_array($result)) {
            $GLOBALS['isData']="1";
            $GLOBALS['output'] .= '<option value="'.$row['userId'].'">'.$row['name'].'</option>';

        }




    }
    else
    {
        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
    }
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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Payment Add</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Student Payment
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form name="attendence" action="add.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Student Name</label>
                                        <?php
                                        if($loginGrp=="UG004") {
                                          echo   '<select class="form-control" name="person" disabled required="">';
                                        }
                                        else{
                                          echo  '<select class="form-control" name="person" required="">';
                                        }
                                        ?>

                                            <?php echo $GLOBALS['output'];?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Payment Date</label>
                                        <div class="input-group date" id='dp1'>

                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                            <input type="text" placeholder="Payment Date" class="form-control datepicker" name="paydate" required  data-date-format="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Paid By</label>
                                        <select class="form-control" name="paidby" required="">

                                            <option value="Bank">Bank</option>
                                            <option value="DBBL">DBBL</option>
                                            <option value="Bkash">BKash</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">

                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Transection/Mobile No</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i> </span>
                                            <input type="text" placeholder="Transecton or Mobile no" class="form-control" name="transno" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Amount</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-money"></i> </span>
                                            <input type="text" placeholder="Amount" class="form-control" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Remark</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                            <input type="text" placeholder="Additional Info" class="form-control" name="remark" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>





                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-2">
                                    <div class="form-group ">
                                        <button type="submit" class="btn btn-success" name="btnSave" ><i class="fa fa-2x fa-check"></i>Save</button>
                                    </div>

                                </div>
                                <div class="col-lg-5">
                                </div>
                            </div>
                        </div>
                    </form>
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

        $('.datepicker').datepicker();

    });



</script>