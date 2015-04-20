<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 10:56 AM
 */

?>
<?php

$GLOBALS['title']="Bill-HMS";
$base_url="http://localhost/hms/";
$GLOBALS['output']='';
$GLOBALS['isData']="";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');


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
    $name=$ses->Get("loginId");
    $msg="";
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {

        $data = array();
        if($loginGrp=="UG001")
        {
            $query ="SELECT a.billId,b.name,sum(a.amount) as amount,DATE_FORMAT(a.billingDate,'%D %M,%Y') as date from billing as a,studentinfo as b where a.billTo=b.userId and b.isActive='Y' group by billId";
        }
        else
        {
            $query ="SELECT a.billId,b.name,sum(a.amount) as amount,DATE_FORMAT(a.billingDate,'%D %M,%Y') as date from billing as a,studentinfo as b where a.billTo=b.userId and b.isActive='Y' and a.billTo='".$loginId."' group by billId";
        }
        $result = $db->getData($query);
        $GLOBALS['output']='';
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output'].='<div class="table-responsive">
                                <table id="billList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th>Bill Id</th>
                                             <th>Name</th>
                                            <th>Amount</th>

                                             <th>Bill Date</th>';
            if($loginGrp==="UG001") {        $GLOBALS['output'].='<th>Action</th>';}

                                      $GLOBALS['output'].= ' </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData']="1";
                $GLOBALS['output'] .= "<tr>";

                $GLOBALS['output'] .= "<td><a href='single.php?billId=".$row["billId"]."' title='View Details'>" . $row['billId'] . "</a></td>";
                $GLOBALS['output'] .= "<td>" . $row['name'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['amount'] . "/-</td>";
                $GLOBALS['output'] .= "<td>" . $row['date'] . "</td>";
                if($loginGrp==="UG001") {
                    $GLOBALS['output'] .= "<td><a title='Delete' class='btn btn-danger btn-circle' href='action.php?id=" . $row['billId'] . "&wtd=delete'" . "><i class='fa fa-trash-o'></i></a></td>";
                }
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



}


if($loginGrp==="UG004"){

include('./../../smater.php');

}
else
{
include('./../../master.php');
}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Billing View</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Hostel Bill List View

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">


                    <div class="row">
                        <div class="col-lg-12">
                            <hr />
                            <?php if($GLOBALS['isData']=="1"){echo $GLOBALS['output'];}?>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header alert alert-info">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 id="myModalLabel" class="modal-title"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-lg-6">
                                           <div class=""><label>Bill No: </label> <span id="billId"></span></div>
                                            </div> <div class="col-lg-6">
                                           <div class=""><label>Bill Date: </label> <span id="billDate"></span></div>
                                            </div>
                                            </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                        <table id="mbilllist" class="table table-responsive table-hover text-center">
                                            <thead >
                                            <tr>
                                                <th class="text-center text-primary">Type</th>
                                               <th class="text-center text-primary">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            </table>
                                            <div class="text-info"><label>Total: </label> <span id="total"></span></div>
                                        </div>

                                    </div>
                                <p></p>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>

                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->



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



        $('#billList').dataTable();
        $('.showModal').on('click', function(e) {
            e.preventDefault();

           var table =document.getElementById('billList');
            var r =  $(this).parent().parent().index();
           var BillTo =table.rows[r+1].cells[1].innerHTML;
            var billId=table.rows[r+1].cells[0].innerHTML;
            var date = table.rows[r+1].cells[3].innerHTML;
            var t = table.rows[r+1].cells[2].innerHTML;
            $('#myModalLabel').text("Billing Info of ["+BillTo+"]");
            $('#billId').text(billId);
            $('#billDate').text(date);
            $('#total').text(t);


            value = new Array();
            $.ajax({
                type: "GET",
                url: "action.php",
                dataType: 'json',
                success: function (result) {
                    alert(result);
                }

            });
          //  $("#myModal").modal('show');


        });
    });




</script>