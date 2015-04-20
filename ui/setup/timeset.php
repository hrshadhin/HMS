<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 4:49 PM
 */


$GLOBALS['title']="Time Set-HMS";
$base_url="http://localhost/hms/";
$GLOBALS['rate']='';
$GLOBALS['note']="";
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');


$ses = new \sessionManager\sessionManager();
$ses->start();
$name=$ses->Get("name");

if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else {
    $msg = "";
    $db = new \dbPlayer\dbPlayer();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnSave"])) {


            $msg = $db->open();
            if ($msg = "true") {
                $result = $db->getData("SELECT * FROM timeset");
                if(mysql_num_rows($result)>0) {
                    $query = "update timeset set inTime='" . $_POST['start_time'] . "',outTime='" . $_POST['end_time'] . "'";
                    $result = $db->update($query);
                    if ($result === "true") {

                        //  $db->close();
                        echo '<script type="text/javascript"> alert("Time Updated Successfully.");
                                window.location.href = "timeset.php";
                        </script>';
                        //  header('Location: mealrate.php');

                    } else {
                        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                    }
                }
                else
                {
                    $query = "insert into timeset  VALUES ('" . $_POST['start_time'] . "','" . $_POST['end_time'] . "')";
                    $result = $db->execNonQuery($query);
                    if ($result === "true") {

                        //  $db->close();
                        echo '<script type="text/javascript"> alert("Time Added Successfully.");
                                window.location.href = "timeset.php";
                        </script>';
                        //  header('Location: mealrate.php');

                    } else {
                        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                    }
                }
            } else {
                echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
            }
        }

    } else {

        $msg = $db->open();
        if ($msg = "true") {

            $data = array();
            $result = $db->getData("SELECT * FROM timeset");
            if(mysql_num_rows($result)>0) {
                if (false === strpos((string)$result, "Can't")) {
                    $data = array();
                    while ($row = mysql_fetch_array($result)) {
                        array_push($data, $row['inTime']);
                        array_push($data, $row['outTime']);


                    }
                    $GLOBALS['start_time'] = $data[0];
                    $GLOBALS['end_time'] = $data[1];
                } else {
                    echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                }
            }
            else
            {
                $GLOBALS['start_time'] = "";
                $GLOBALS['end_time'] = "";
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
        }

    }
}



?>

<?php include('./../../master.php'); ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Time Set</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle fa-fw"></i> Hostel Time Set <span id="lblmsg"> (Click On Clock Icon)</span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form name="mealrate" action="timeset.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                                    <div class="row">
                                        <div class="col-lg-12">



                                            <div class="col-lg-4">
                                                <div class="form-group ">
                                                    <label>In Time</label>
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" id="timepicker2" name="start_time" data-default-time="false" placeholder="h:mm AM"  class="form-control"  value="<?php echo $GLOBALS['start_time'];?>" required>
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group ">
                                                    <label>Out Time</label>
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" id="timepicker1" name="end_time" data-default-time="false" placeholder="h:mm AM"  class="form-control"  value="<?php echo $GLOBALS['end_time'];?>" required>
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
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
        $('#timepicker1').timepicker();
        $('#timepicker2').timepicker();


    });
</script>
