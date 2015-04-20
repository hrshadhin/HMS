<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 2/27/15
 * Time: 11:42 AM
 */

$GLOBALS['title']="Payment-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/handyCam.php');

$ses = new \sessionManager\sessionManager();
$ses->start();
$loginId=$ses->Get("userIdLoged");
$loginGrp=$ses->Get("userGroupId");
$display="";
$displaytable="none";
$GLOBALS['isData']="0";
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


            $result = $db->delete("update stdpayment set isApprove='Yes' where serial='".$_GET['id']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Payment Aprroved Successfully.");
                                window.location.href = "approvallist.php";
                        </script>';
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $result . '");window.location.href = "approvallist.php";</script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");window.location.href = "approvallist.php";</script>';
        }

    }
    if ($msg = "true") {
        $handyCam = new \handyCam\handyCam();
    $query = "SELECT a.serial,b.name,a.transDate,a.paymentBy ,a.transNo,a.amount,a.remark FROM stdpayment as a,studentinfo as b where a.userId=b.userId and a.isApprove='No' and b.isActive='Y'";

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
                                             <th>Remark</th>
                                             <th >Action</th >
                                              </tr>
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

            $GLOBALS['output'] .= "<td><a title='Approve' class='btn btn-info btn-circle' href='approvallist.php?id=" . $row['serial'] ."&wtd=delete'"."><i class='fa fa-check'></i></a></td>";


        $GLOBALS['output'] .= "</tr>";

    }

    $GLOBALS['output'].=  '</tbody>
                                </table>
                            </div>';


}
else
{
    echo '<script type="text/javascript"> alert("' . $result . '");window.location="approvallist.php";</script>';
}
} else {
    echo '<script type="text/javascript"> alert("' . $msg . '");window.location="approvallist.php";</script>';
}
}


include('./../../master.php');

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Payment Approval List</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i><i class="fa fa-hand-o-right"></i> Student Payment Approval List
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">





                    <div class="row">
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


    });




</script>
