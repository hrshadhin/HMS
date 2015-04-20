<?php
$GLOBALS['title']="Employee-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/fileUploader.php');
require('./../../inc/handyCam.php');
$GLOBALS['empId']='';

if (isset($_GET['id']) && $_GET['wtd']) {
    $ses = new \sessionManager\sessionManager();
    $ses->start();
    $ses->Set("empIdFor",$_GET['id']);
    $GLOBALS['empId']=$ses->Get("empIdFor");
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    if($_GET['wtd']==="edit")
    {



        if ($msg = "true") {

            $data = array();
            $result = $db->getData("SELECT * FROM employee where empId='".$GLOBALS["empId"]."'and isActive='Y'");
            // var_dump($result);
            if(false===strpos((string)$result,"Can't"))
            {
                $handyCam = new \handyCam\handyCam();
                $data = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($data,$row['name']);
                    array_push($data,$row['cellNo']);
                    array_push($data,$row['empType']);
                    array_push($data,$row['designation']);
                    array_push($data,$row['gender']);
                    array_push($data,$handyCam->getAppDate($row['dob']));
                    array_push($data,$handyCam->getAppDate($row['doj']));
                    array_push($data,$row['blockNo']);
                    array_push($data,$row['salary']);
                    array_push($data,$row['address']);
                }
                // var_dump($data);
                formRender($data);
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                header("location: view.php");
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
            header("location: view.php");
        }
    }
    elseif($_GET['wtd']==="delete")
    {
        if ($msg = "true") {


            $data = array(

                'isActive' => 'N'
            );
            $result = $db->updateData("employee","empId",$GLOBALS['empId'],$data);

            if(false===strpos((string)$result,"Can't"))
            {
                echo '<script type="text/javascript"> alert("Employee Deleted Successfully.");
                                window.location.href = "view.php";
                        </script>';
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . $result . '");
                                window.location.href = "view.php";
                        </script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");
                                window.location.href = "view.php";
                        </script>';
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

            $empIdFor=$ses->Get("empIdFor");
            $db = new \dbPlayer\dbPlayer();
            $msg = $db->open();
            if ($msg = "true") {

                    $flup = new fileUploader\fileUploader();
                    $perPhoto = $flup->upload("/hms/files/photos/", $_FILES['perPhoto'], $empIdFor);

                    $sal = (float)$_POST['salary'];
                    $handyCam = new \handyCam\handyCam();
                    $dob = $handyCam->parseAppDate($_POST['dob']);
                    $doj = $handyCam->parseAppDate($_POST['doj']);
                    $data = array(
                        'name' => $_POST['name'],
                        'empType' => $_POST['empType'],
                        'designation' => $_POST['designation'],
                        'cellNo' => $_POST['cellNo'],
                        'gender' => $_POST['gender'],
                        'dob' =>$dob,
                        'doj' => $doj,
                        'address' => $_POST['presentAddress'],
                        'blockNo' => $_POST['blockNo'],
                        'salary' => $sal,

                        'isActive' => 'Y'
                    );
                    if(strpos($perPhoto, 'Error:') === false) {
                        $data['perPhoto'] = $perPhoto;
                    }
                       // var_dump($data);
                   $result = $db->updateData("employee","empId",$empIdFor,$data);
                    if($result==="true") {
                        $userPass = md5("hms2015".$_POST['password']);
                        $data = array(
                            'password' => $userPass,

                        );
                        $result=$db->updateData("users","loginId",$empIdFor,$data);
                        if($result==="true")
                        {
                           // $db->close();
                            echo '<script type="text/javascript"> alert("update Employee Info Successfully.");
                                window.location.href = "view.php";
                        </script>';
                        }
                        else
                        {
                            echo '<script type="text/javascript"> alert("Password Can not Update ' . $result . '");
                                window.location.href = "view.php";
                        </script>';
                        }

                    }
                    else
                    {
                        echo '<script type="text/javascript"> alert("' . $result . '");
                                window.location.href = "view.php";
                        </script>';
                    }

            }
            } else {
                echo '<script type="text/javascript"> alert("' . $msg . '");
                                window.location.href = "view.php";
                        </script>';
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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Update Employee</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Update Employee Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form name="payment" action="edit.php?update=1" onsubmit="return checkForm(this);"  accept-charset="utf-8" method="post" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Full Name</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-leaf"></i> </span>
                                                    <input type="text" placeholder="Full Name" class="form-control" name="name" value="<?php echo $data[0];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Password</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-key"></i> </span>
                                                    <input type="password" id="password" placeholder="" class="form-control" name="password" required>
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

                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Cell No(As Login Id)</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-mobile-phone"></i> </span>
                                                    <input type="text" placeholder="Mobile No" class="form-control" name="cellNo" value="<?php echo $data[1];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Employee Type</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Employee Type" class="form-control" name="empType" value="<?php echo $data[2];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Designation</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Designation" class="form-control" name="designation" value="<?php echo $data[3];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control" name="gender" required="">
                                                    <?php
                                                        if($data[4]=="Male")
                                                        {
                                                            echo '<option value="Male" selected>Male</option>';
                                                            echo ' <option value="Female">Female</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="Male" >Male</option>';
                                                            echo ' <option value="Female" selected>Female</option>';
                                                        }
                                                    ?>



                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Date Of Birth</label>
                                                <div class="input-group date" id='dp1'>

                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                    <input type="text" placeholder="Date Of Birth" class="form-control datepicker" name="dob" value="<?php echo $data[5];?>" required  data-date-format="dd/mm/yyyy">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Join Date</label>
                                                <div class="input-group date" id='dp1'>

                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                    <input type="text" placeholder="Join Date" class="form-control datepicker" name="doj" value="<?php echo $data[6];?>" required  data-date-format="dd/mm/yyyy">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">

                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Block No</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-building"></i> </span>
                                                    <input type="text" placeholder="Block No" class="form-control" name="blockNo" value="<?php echo $data[7];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Salary</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                                    <input type="text" placeholder="Salary" class="form-control" name="salary" value="<?php echo $data[8];?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Photo</label>
                                                <div class="input-group">

                                                    <input type="file" class="form-control" name="perPhoto">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">

                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <label>Address</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon"><i class="fa fa-road"></i> </span>
                                                    <textarea rows="3" placeholder="Address" class="form-control" name="presentAddress" required> <?php echo $data[9];?></textarea>
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

<?php }?>
<script type="text/javascript">
    $( document ).ready(function() {
        $('.datepicker').datepicker();


    });
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







