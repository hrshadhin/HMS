<?php

$GLOBALS['title']="Seat-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');


$ses = new \sessionManager\sessionManager();
$ses->start();
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else
{
    $name=$ses->Get("loginId");


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnSave"])) {

            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();

            if ($msg = "true") {


                $data = array(
                    'userId' => $_POST['person'],
                    'monthlyRent' => floatval($_POST['mrent']),
                    'blockNo' => $_POST['blockNo'],
                    'roomNo' => $_POST['roomNo']


                );
                $result = $db->insertData("seataloc",$data);

                if($result>=0)
                {

                    echo '<script type="text/javascript"> alert("Seat Alocation Successfull.");</script>';
                    getData();
                }
                else
                {
                    echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                }

            }
            else
            {
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
$msg="";
$db = new \dbPlayer\dbPlayer();
$msg = $db->open();
if ($msg = "true") {

    //load student list
    $data = array();
    $result = $db->getData("SELECT userId,name FROM studentinfo  where isActive='Y'");
    $GLOBALS['output'] = '';
    if (false === strpos((string)$result, "Can't")) {
        while ($row = mysql_fetch_array($result)) {
            $GLOBALS['isData'] = "1";
            $GLOBALS['output'] .= '<option value="' . $row['userId'] . '">' . $row['name'] . '</option>';

        }


    } else {
        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
    }
    $data = array();
    $result = $db->getData("SELECT blockId,blockNo FROM blocks  where isActive='Y'");
    $GLOBALS['output2'] = '';
    if (false === strpos((string)$result, "Can't")) {
        while ($row = mysql_fetch_array($result)) {
            $GLOBALS['isData2'] = "1";
            $GLOBALS['output2'] .= '<option value="' . $row['blockNo'] . '">' . $row['blockNo'] . '</option>';

        }


    } else {
        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
    }
    $data = array();
    $result = $db->getData("SELECT roomId,roomNo FROM rooms  where isActive='Y'");
    $GLOBALS['output3'] = '';
    if (false === strpos((string)$result, "Can't")) {
        while ($row = mysql_fetch_array($result)) {
            $GLOBALS['isData3'] = "1";
            $GLOBALS['output3'] .= '<option value="' . $row['roomNo'] . '">' . $row['roomNo'] . '</option>';

        }


    } else {
        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
    }

}
    if ($msg = "true") {

        $data = array();
        $result = $db->getData("SELECT a.userId,b.name,a.blockNo,a.roomNo,a.monthlyRent from seataloc as a, studentinfo as b where a.userId = b.userId and b.isActive='Y'");
        $GLOBALS['output1']='';
        $GLOBALS['isData1']='';
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output1'].='<div class="table-responsive">
                                <table id="seatList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Block No</th>
                                            <th>Room No</th>
                                            <th>Monthly Rent</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData1']="1";
                $GLOBALS['output1'] .= "<tr>";
                $GLOBALS['output1'] .= "<td>" . $row['name'] . "</td>";
                $GLOBALS['output1'] .= "<td>" . $row['blockNo'] . "</td>";
                $GLOBALS['output1'] .= "<td>" . $row['roomNo'] . "</td>";
                $GLOBALS['output1'] .= "<td>" . $row['monthlyRent'] . "</td>";
                $GLOBALS['output1'] .= "<td><a title='Edit' class='btn btn-success btn-circle' href='seatalaction.php?id=" . $row['userId'] ."&wtd=edit'"."><i class='fa fa-pencil'></i></a>&nbsp&nbsp<a title='Delete' class='btn btn-danger btn-circle' href='seatalaction.php?id=" . $row['userId'] ."&wtd=delete'"."><i class='fa fa-trash-o'></i></a></td>";
                $GLOBALS['output1'] .= "</tr>";

            }

            $GLOBALS['output1'].=  '</tbody>
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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Seat Alocation</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Student's Seat Alocation
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="deposit" action="seatalocation.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Student Name</label>
                                                <select class="form-control" name="person" required="">
                                                    <?php echo $GLOBALS['output'];?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Block No</label>
                                                <select class="form-control" name="blockNo" required="">
                                                    <?php echo $GLOBALS['output2'];?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Room No</label>
                                                <select class="form-control" name="roomNo" required="">
                                                    <?php echo $GLOBALS['output3'];?>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Monthly Rent</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Monthly Rent" class="form-control" name="mrent" required>
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
                            <?php if($GLOBALS['isData1']=="1"){echo $GLOBALS['output1'];}?>
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
        $('#seatList').dataTable();


    });



</script>