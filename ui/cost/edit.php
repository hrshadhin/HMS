<?php
$GLOBALS['title']="Cost-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
$GLOBALS['serial']='';

if (isset($_GET['id']) && $_GET['wtd']) {
    $ses = new \sessionManager\sessionManager();
    $ses->start();
    $ses->Set("serialFor",$_GET['id']);
    $GLOBALS['serial']=$ses->Get("serialFor");
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    if($_GET['wtd']==="edit")
    {



        if ($msg = "true") {

            $data = array();
            $result = $db->getData("SELECT * FROM cost where serial='".$GLOBALS["serial"]."'");
            // var_dump($result);
            if(false===strpos((string)$result,"Can't"))
            {
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data,$row['type']);
                    array_push($data,$row['amount']);

                    array_push($data,$row['description']);
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


            $result = $db->delete("delete from cost where serial='".$GLOBALS['serial']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Cost Deleted Successfully.");
                                window.location.href = "view.php";
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
        header("location: view.php");

    }

}
elseif($_GET['update']=="1")
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnUpdate"])) {
            $ses = new \sessionManager\sessionManager();
            $ses->start();

            $serialFor=$ses->Get("serialFor");
            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();
            if ($msg = "true") {


                $data = array(

                    'type' => $_POST['type'],
                    'amount' => floatval($_POST['amount']),
                    'date' =>date("Y-m-d"),

                    'description' => $_POST['description']

                );

                $result = $db->updateData("cost", "serial",$serialFor,$data);
                // var_dump($result);
                if ($result==="true") {

                    //  $db->close();
                    echo '<script type="text/javascript"> alert("Cost Updated Successfully.");
                                window.location.href = "view.php";
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
    header("location: view.php");
}
function formRender($data)
{ ?>

    <?php include('./../../master.php'); ?>
    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Update Cost</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Hostel Cost Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="payment" action="edit.php?update=1"  accept-charset="utf-8" method="post" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Cost Type</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-user"></i> </span>
                                                    <input type="text" placeholder="Cost Type" class="form-control" name="type" value="<?php echo $data[0];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Amount</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-money"></i> </span>
                                                    <input type="text" placeholder="Amount" class="form-control" name="amount" value="<?php echo $data[1];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Description" class="form-control" value="<?php echo $data[2];?>" name="description">
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








