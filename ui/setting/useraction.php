<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/5/15
 * Time: 1:57 PM
 */


$GLOBALS['title']="User-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
$GLOBALS['loginId']='';

if (isset($_GET['id']) && $_GET['wtd']) {
    $ses = new \sessionManager\sessionManager();
    $ses->start();
    $ses->Set("loginIdFor",$_GET['id']);
    $GLOBALS['loginIdFor']=$ses->Get("loginIdFor");

    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    if($_GET['wtd']==="edit")
    {



        if ($msg = "true") {

            $data = array();
            $result = $db->getData("SELECT * FROM users where loginId='".$GLOBALS['loginIdFor']."'");
            // var_dump($result);
            if(false===strpos((string)$result,"Can't"))
            {
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data,$row['loginId']);
                    array_push($data,$row['name']);
                    array_push($data,$row['password']);
                }
                // var_dump($data);
                formRender($data[0],$data[1],$data[2]);
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


            $result = $db->delete("delete from users where loginId='".$GLOBALS['loginIdFor']."'");

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("User Deleted Successfully.");
                                window.location.href = "adduser.php";
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
        header("location: adduser.php");

    }

}
elseif($_GET['update']=="1")
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["btnUpdate"])) {
            $ses = new \sessionManager\sessionManager();
            $ses->start();

            $loginIdFor=$ses->Get("loginIdFor");
            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();
            if ($msg = "true") {
                $userPass = md5("hms2015".$_POST['password']);

                $data = array(

                    'name' => $_POST['name'],
                    'loginId' => $_POST['loginId'],
                    'password' => $userPass,


                );

                $result = $db->updateData("users", "loginId",$loginIdFor,$data);
                // var_dump($result);
                if ($result==="true") {

                    //  $db->close();
                    echo '<script type="text/javascript"> alert("User Info Updated Successfully.");
                                window.location.href = "adduser.php";
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
    header("location: adduser.php");
}
function formRender($loginId,$name,$pass)
{ ?>

    <?php include('./../../master.php'); ?>
    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Update User Info</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>System Admin User Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="fees" action="useraction.php?update=1" onsubmit="return checkForm(this);"  accept-charset="utf-8" method="post" enctype="multipart/form-data">



                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Login Id</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-user"></i> </span>
                                                    <input type="text" placeholder="Login Id" class="form-control" name="loginId" value="<?php echo $loginId;?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Name</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-leaf"></i> </span>
                                                    <input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name;?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Password</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-key"></i> </span>
                                                    <input type="password" id="password" placeholder="" class="form-control" name="password" value="<?php echo $pass;?>" required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Confirm Password</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-key"></i> </span>
                                                    <input type="password" id="rePassword" placeholder="" class="form-control" name="rePassword" required>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label id="lblmsg" class="red"></label>
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
    <script type="text/javascript">


        function checkForm(form) {

            var password = document.getElementById("password")
                , confirm_password = document.getElementById("rePassword");
            console.log(password.value);
            console.log(confirm_password.value);
            if(password.value != confirm_password.value) {

                $("#lblmsg").text("**Passwords Don't Match");

                return false;
            } else {

                return true;
            }

        }

    </script>

<?php }










