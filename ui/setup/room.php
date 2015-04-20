<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 10:56 AM
 */

?>
<?php

$GLOBALS['title']="Room-HMS";
$base_url="http://localhost/hms/";
$GLOBALS['output']='';
$GLOBALS['isData']="";
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');


$ses = new \sessionManager\sessionManager();
$ses->start();
$name=$ses->Get("name");
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else
{
    $name=$ses->Get("loginId");
    $msg="";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnSave"])) {

            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();
            //echo '<script type="text/javascript"> alert("'.$msg.'");</script>';
            if ($msg = "true") {

                $userIds = $db->getAutoId("RM");
                $data = array(

                    'roomId' => $userIds[1],
                    'roomNo' => $_POST['roomNo'],
                    'blockId' => $_POST['blockId'],
                    'description' => $_POST['description'],
                    'noOfSeat' => $_POST['noOfSeat'],
                    'isActive'      => 'Y'

                );
                $result = $db->insertData("rooms",$data);
                if($result>=0) {
                    $id =intval($userIds[0])+1;

                    $query="UPDATE auto_id set number=".$id." where prefix='RM';";
                    $result=$db->update($query);
                   // var_dump($result);
                    //  $db->close();
                    echo '<script type="text/javascript"> alert("Room Added Successfully.");</script>';
                    getData();

                } else {
                    echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                }
            } else {
                echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
            }
        }





    }
    else
    {
        getData();
    }



}
function getData()
{
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {

        $data = array();
        $result = $db->getData("SELECT * FROM rooms where isActive='Y'");
        $GLOBALS['output']='';
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output'].='<div class="table-responsive">
                                <table id="roomList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th>Room No</th>
                                            <th>No Of Seat</th>
                                            <th>Block No</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData']="1";
                $GLOBALS['output'] .= "<tr>";

                $GLOBALS['output'] .= "<td>" . $row['roomNo'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['noOfSeat'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['blockId'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['description'] . "</td>";
                $GLOBALS['output'] .= "<td><a title='Edit' class='btn btn-success btn-circle' href='roomaction.php?id=" . $row['roomId'] ."&wtd=edit'"."><i class='fa fa-pencil'></i></a>&nbsp&nbsp<a title='Delete' class='btn btn-danger btn-circle' href='roomaction.php?id=" . $row['roomId'] ."&wtd=delete'"."><i class='fa fa-trash-o'></i></a></td>";
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
?>
<?php include('./../../master.php'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Rooms</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Hostel Room Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="fees" action="room.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Room No</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i> </span>
                                                    <input type="text" placeholder="Room No" class="form-control" name="roomNo" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Number Of Seat</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="No Of Seat" class="form-control" name="noOfSeat" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Block No</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i> </span>
                                                    <input type="text" placeholder="Block No" class="form-control" name="blockId" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Description</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <textarea rows="1" placeholder="Description" class="form-control" name="description" required></textarea>
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



        $('#roomList').dataTable();
    });



</script>