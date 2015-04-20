<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 1:57 PM
 */


$GLOBALS['title']="Notice-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
$GLOBALS['serial']='';

if (isset($_GET['id']) && $_GET['wtd']) {
    $ses = new \sessionManager\sessionManager();
    $ses->start();
    $ses->Set("serial",$_GET['id']);
    $GLOBALS['serial']=$ses->Get("serial");

    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    if($_GET['wtd']==="edit")
    {



        if ($msg = "true") {

            $data = array();
            $result = $db->getData("SELECT * FROM notice where serial='".$GLOBALS['serial']."'");
            // var_dump($result);
            if(false===strpos((string)$result,"Can't"))
            {
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data,$row['title']);
                    array_push($data,$row['description']);

                }
                // var_dump($data);
                formRender($data[0],$data[1]);
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


            $result = $db->delete("delete from notice where serial='".$GLOBALS['serial']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Notice Deleted Successfully.");
                                window.location.href = "create.php";
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
        header("location: create.php");

    }

}
elseif($_GET['update']=="1")
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnUpdate"])) {
            $ses = new \sessionManager\sessionManager();
            $ses->start();

            $serialFor=$ses->Get("serial");
            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();
            if ($msg = "true") {


                $data = array(

                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'createdDate' => $dateNow=date("Y-m-d H:i:s")


                );

                $result = $db->updateData("notice", "serial",$serialFor,$data);
                // var_dump($result);
                if ($result==="true") {

                    //  $db->close();
                    echo '<script type="text/javascript"> alert("Notice Info Updated Successfully.");
                                window.location.href = "create.php";
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
    header("location: create.php");
}
function formRender($title,$desc)
{ ?>

    <?php include('./../../master.php'); ?>
    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms">Update Notice</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Notice Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="notic" action="noticeaction.php?update=1"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Title</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Notice Title" class="form-control" name="title" value="<?php echo $title;?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label>Description</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <textarea rows="1" placeholder="Description" class="form-control" name="description" required><?php echo $desc;?></textarea>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">

                                        <div class="col-lg-2">
                                            <div class="form-group ">
                                                <button type="submit" class="btn btn-success" name="btnUpdate" ><i class="fa fa-2x fa-check"></i>Update</button>
                                            </div>

                                        </div>
                                        <div class="col-lg-7">
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










