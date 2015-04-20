<?php
$GLOBALS['title']="Salary-HMS";
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
            $result = $db->execDataTable("SELECT * FROM salary where serial='".$GLOBALS["serial"]."'");
            // var_dump($result);
            if(false===strpos((string)$result,"Can't"))
            {
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data,$row['empId']);
                    array_push($data,$row['monthYear']);
                    array_push($data,$row['amount']);

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


            $result = $db->execNonQuery("delete from salary where serial='".$GLOBALS['serial']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Salary Deleted Successfully.");
                                window.location.href = "salaryview.php";
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
        header("location: salaryview.php");

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

                    'monthYear' => $_POST['monthyear'],
                    'amount' => floatval($_POST['amount']),
                    'addedDate' =>date("Y-m-d"),


                );

                $result = $db->updateData("salary", "serial",$serialFor,$data);
                // var_dump($result);
                if ($result==="true") {

                    //  $db->close();
                    echo '<script type="text/javascript"> alert("Salary Updated Successfully.");
                                window.location.href = "salaryview.php";
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
    header("location: salaryview.php");
}
function formRender($data)
{ ?>

    <?php include('./../../master.php'); ?>
    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Update Salary</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Update Employee Salary[<?php echo $data[0];?>]
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="payment" action="salaryedit.php?update=1"  accept-charset="utf-8" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-12">
                                     <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Salary Month</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                    <input type="text" placeholder="Salary Month" class="form-control datepicker" name="monthyear" value="<?php echo $data[1];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Amount</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Amount" class="form-control" name="amount" value="<?php echo $data[2]; ?>" required>
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

<?php }?>

<script type="text/javascript">
    $( document ).ready(function() {
        $('.datepicker').datepicker({
            format: "MM-yyyy",
            viewMode: "months",
            minViewMode: "months"
        });


    });

</script>






