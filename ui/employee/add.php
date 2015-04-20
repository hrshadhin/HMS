<?php

$GLOBALS['title']="Employee-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/fileUploader.php');
require('./../../inc/handyCam.php');
$ses = new \sessionManager\sessionManager();
$ses->start();
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else
{
    $name=$ses->Get("loginId");


}


$msg="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["btnSave"])) {

        $db = new \dbPlayer\dbPlayer();
        $msg = $db->open();
        //echo '<script type="text/javascript"> alert("'.$msg.'");</script>';
        if ($msg = "true") {
            $userIds = $db->getAutoId("EMP");
            $flup = new fileUploader\fileUploader();
            $perPhoto = $flup->upload("/hms/files/photos/",$_FILES['perPhoto'], $userIds[1]);
            // var_dump($perPhoto);
            if (strpos($perPhoto, 'Error:') == false) {
               // $dateNow=date("Y-m-d");
               $sal = (float)$_POST['salary'];
                $handyCam = new \handyCam\handyCam();
                $data = array(
                    'empId' => $userIds[1],
                    'userGroupId' => "UG003",
                    'name' => $_POST['name'],
                    'empType' => $_POST['empType'],
                    'designation' => $_POST['designation'],
                    'cellNo' => $_POST['cellNo'],
                    'gender' => $_POST['gender'],
                    'dob' =>$handyCam->parseAppDate($_POST['dob']),
                    'doj' =>$handyCam->parseAppDate($_POST['doj']),
                    'address' => $_POST['presentAddress'],
                    'blockNo' => $_POST['blockNo'],
                    'salary' => $sal,
                    'perPhoto' => $perPhoto,
                    'isActive' => 'Y'
                );
                $result = $db->insertData("employee",$data);
                if($result>0) {
                    $userPass = md5("hms2015".$_POST['password']);
                    $data = array(
                        'userId' => $userIds[1],
                        'userGroupId' => "UG003",
                        'name' => $_POST['name'],
                        'loginId' => $_POST['cellNo'],
                        'password' => $userPass,
                        'verifyCode' => "vhms2115",
                        'expireDate' => "2115-01-4",
                        'isVerifed' => 'Y'
                    );
                    $result=$db->insertData("users",$data);
                    if($result>=0)
                    {
                        $id =intval($userIds[0])+1;

                        $query="UPDATE auto_id set number=".$id." where prefix='EMP';";
                        $result=$db->update($query);
                       // $db->close();
                        echo '<script type="text/javascript"> alert("Employee Added Successfully.");</script>';
                    }
                    else
                    {
                        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                    }

                }
                elseif(strpos($result,'Duplicate') !== false)
                {
                    echo '<script type="text/javascript"> alert("Employee Already Exits!");</script>';
                }
                else
                {
                    echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                }
            } else {
                echo '<script type="text/javascript"> alert("' . $perPhoto . '");</script>';
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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>New Employee</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Employee Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form name="admission" action="add.php" onsubmit="return checkForm(this);" accept-charset="utf-8" method="post" enctype="multipart/form-data">


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Full Name</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-leaf"></i> </span>
                                            <input type="text" placeholder="Full Name" class="form-control" name="name" required>
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
                                                <input type="text" placeholder="Mobile No" class="form-control" name="cellNo" required>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Employee Type</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                            <input type="text" placeholder="Employee Type" class="form-control" name="empType" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Designation</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                            <input type="text" placeholder="Designation" class="form-control" name="designation" required>
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
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Date Of Birth</label>
                                        <div class="input-group date" id='dp1'>

                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                            <input type="text" placeholder="Date Of Birth" class="form-control datepicker" name="dob" required  data-date-format="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Join Date</label>
                                        <div class="input-group date" id='dp1'>

                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                            <input type="text" placeholder="Join Date" class="form-control datepicker" name="doj" required  data-date-format="dd/mm/yyyy">
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
                                            <input type="text" placeholder="Block No" class="form-control" name="blockNo" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Salary</label>
                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-info"></i> </span>
                                            <input type="text" placeholder="Salary" class="form-control" name="salary" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <label>Photo</label>
                                        <div class="input-group">

                                            <input type="file" class="form-control" name="perPhoto" required>
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
                                            <textarea rows="3" placeholder="Address" class="form-control" name="presentAddress" required> </textarea>
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
                                        <button type="submit" class="btn btn-success" name="btnSave" ><i class="fa fa-2x fa-check"></i>Save</button>
                                    </div>

                                </div>
                                <div class="col-lg-5">
                                </div>
                            </div>
                        </div>
                    </form>
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