<?php
/**
 * Created by PhpStorm.
 * User: lmx
 * Date: 2/26/2015
 * Time: 3:04 PM
 *
 *
 */

$GLOBALS['title']="Attendence-HMS";
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnUpdate"])) {

            getTableData($_POST['person'],$db);
            $displaytable="";
        }
    }

    if($loginGrp=="UG004"){
        getTableData($loginId,$db);
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
function getTableData($userId,$db)
{

    if ($msg = "true") {
        $handyCam = new \handyCam\handyCam();
        $data = array();

        $query="SELECT a.serial,b.name,a.date,a.isAbsence ,a.isLeave,a.remark FROM attendence as a,studentinfo as b where a.userId='".$userId."' and a.userId=b.userId and b.isActive='Y'";
        $result = $db->getData($query);
        $GLOBALS['output']='';
      // var_dump($result);
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output'].='<div class="table-responsive">
                                <table id="attendenceList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th>Name</th>
                                             <th>Attend Date</th>
                                             <th>Is Absence</th>
                                             <th>Is Leave</th>
                                             <th>Remark</th>


                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData']="1";
                $GLOBALS['output'] .= "<tr>";

                $GLOBALS['output'] .= "<td>" . $row['name'] . "</td>";
                $GLOBALS['output'] .= "<td>" .$handyCam->getAppDate($row['date']) . "</td>";

                $GLOBALS['output'] .= "<td>" . $row['isAbsence'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['isLeave'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['remark'] . "</td>";


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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Attendence View</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i><i class="fa fa-hand-o-right"></i> Student Attendence View
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form name="attendence" action="view.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">
                    <div class="row" style="display:<?php echo $display;?>">
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
                                    </div>

                            </div>
                        </div>

                   </div>
                        </div>
                    </form>

                    <div class="row" style="display:<?php echo $displaytable;?>">
                        <div class="col-lg-12">
                            <hr />
                            <?php if($GLOBALS['isData']=="1"){echo $GLOBALS['output'];}
                            else
                            {
                                echo "<h1 class='text-warning'>Attendance Data Not Found!!!</h1>";
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

        $('#attendenceList').dataTable();

    });




</script>
