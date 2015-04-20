<?php

$GLOBALS['title']="Seat-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
$GLOBALS['userId']='';

if (isset($_GET['id']) && $_GET['wtd']) {
    $ses = new \sessionManager\sessionManager();
    $ses->start();
    $ses->Set("userIdFor",$_GET['id']);
    $GLOBALS['userId']=$ses->Get("userIdFor");

    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    if($_GET['wtd']==="edit")
    {



        if ($msg = "true") {
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

            $data = array();
            $result = $db->getData("SELECT * FROM seataloc where userId='".$GLOBALS['userId']."'");
            // var_dump($result);
            if(false===strpos((string)$result,"Can't"))
            {
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data,$row['blockNo']);
                    array_push($data,$row['roomNo']);
                    array_push($data,$row['monthlyRent']);
                }
                // var_dump($data);
                formRender($data);
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $result . '");</script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
        }
    }
    elseif($_GET['wtd']==="delete")
    {
        if ($msg = "true") {


            $result = $db->delete("delete from seataloc where userId='".$GLOBALS['userId']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Record Deleted Successfully.");
                                window.location.href = "seatalocation.php";
                        </script>';
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $result . '");</script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
        }

    }
    else
    {
        header("location: seatalocation.php");

    }

}
elseif($_GET['update']=="1")
{

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnUpdate"])) {
            $ses = new \sessionManager\sessionManager();
            $ses->start();

            $userIdFor=$ses->Get("userIdFor");
            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();
            if ($msg = "true") {


                $data = array(

                    'blockNo' => $_POST['blockNo'],
                    'roomNo' => $_POST['roomNo'],
                    'monthlyRent' =>$_POST['mrent']

                );

                $result = $db->updateData("seataloc", "userId",$userIdFor,$data);
                // var_dump($result);
                if ($result==="true") {

                    //  $db->close();
                    echo '<script type="text/javascript"> alert("Seat Alocation Updated Successfully.");
                                window.location.href = "seatalocation.php";
                        </script>';
                    // header("location: block.php");

                } else {
                    echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                }
            } else {
                echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
            }
        }
    }
}
else
{
    header("location: seatalocation.php");
}
function formRender($data)
{ ?>

    <?php include('./../../master.php'); ?>
    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Update Seat</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Update Student Seat Alocation[<?php echo $GLOBALS['userId'];?>]
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="seat" action="seatalaction.php?update=1"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Block No</label>
                                                <select class="form-control" name="blockNo" required="">

                                                    <?php echo $GLOBALS['output2'];?>
                                                     <option value="<?php echo $data[0];?>" selected><?php echo $data[0];?></option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Room No</label>
                                                <select class="form-control" name="roomNo" required="">
                                                    <?php echo $GLOBALS['output3'];?>
                                                     <option value="<?php echo $data[1];?>" selected><?php echo $data[1];?></option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Monthly Rent</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Monthly Rent" class="form-control" name="mrent" value="<?php echo $data[2];?>" required>
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
                                                <button type="submit" class="btn btn-success" name="btnUpdate" ><i class="fa fa-2x fa-check"></i>Update</button>
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
            </div>

        </div>

    </div>



    <?php include('./../../footer.php'); ?>

<?php }








