<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 4:49 PM
 */


$GLOBALS['title']="Meal Rate-HMS";
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

                $rate = (float)$_POST['rate'];
                $result = $db->getData("SELECT * FROM mealrate");
                if(mysql_num_rows($result)>0) {
                    $query = "update mealrate set rate=" . $rate . ",note='" . $_POST['note'] . "'";
                    $result = $db->update($query);
                    if ($result === "true") {

                        //  $db->close();
                        echo '<script type="text/javascript"> alert("Meal Rate Updated Successfully.");
                                window.location.href = "mealrate.php";
                        </script>';
                        //  header('Location: mealrate.php');

                    } else {
                        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                    }
                }
                else
                {
                    $query = "insert into mealrate VALUES(" . $rate . ",'" . $_POST['note'] . "')";
                    //var_dump($query);
                    $result = $db->execNonQuery($query);
                    if ($result === "true") {

                        //  $db->close();
                        echo '<script type="text/javascript"> alert("Meal Rate Added Successfully.");
                                window.location.href = "mealrate.php";
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
            $result = $db->getData("SELECT * FROM mealrate");

            if (false === strpos((string)$result, "Can't")) {
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data, $row['rate']);
                    array_push($data, $row['note']);


                }

                if(mysql_num_rows($result)>0) {
                    $GLOBALS['note'] = $data[1];
                    $GLOBALS['rate'] = $data[0];
                }
                else{
                    $GLOBALS['note'] = "";
                    $GLOBALS['rate'] = "";
                }
            } else {
                echo '<script type="text/javascript"> alert("' . $result . '");</script>';
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
                <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Meal Rate</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle fa-fw"></i> Hostel Meal Rate
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form name="mealrate" action="mealrate.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                                    <div class="row">
                                        <div class="col-lg-12">


                                            <div class="col-lg-4">
                                                <div class="form-group ">
                                                    <label>Rate</label>
                                                    <div class="input-group">

                                                        <span class="input-group-addon"><i class="fa fa-money"></i> </span>
                                                        <input type="text" placeholder="Meal Rate" class="form-control"  value="<?php echo $GLOBALS['rate'];?>" name="rate" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group ">
                                                    <label>Note</label>
                                                    <div class="input-group">

                                                        <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                        <textarea rows="1" placeholder="Note" class="form-control" name="note" required><?php echo $GLOBALS['note'];?></textarea>
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