<?php
$GLOBALS['title']="Profile-HMS";
$page_name="DashBoard";
require('./../../inc/sessionManager.php');
require('./../../inc/handyCam.php');
require('./../../inc/dbPlayer.php');
$base_url="http://localhost/hms/";
$ses = new \sessionManager\sessionManager();

$ses->start();

$loginId=$ses->Get("userIdLoged");
$loginGrp=$ses->Get("userGroupId");
if($ses->isExpired())
{
    header( 'Location: '.$base_url.'login.php');


}
elseif($ses->Get("userGroupId")=="UG004")
{
    header( 'Location: '.$base_url.'sdashboard.php');
}
else
{
    $name=$ses->Get("name");
    $userIdf = $ses->Get("userIdLoged");
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {

        $data = array();
        //  var_dump($qery);
        $result = $db->getData("SELECT * FROM employee where empId='".$userIdf."'");
        $handyCam = new \handyCam\handyCam();

        if(false===strpos((string)$result,"Can't"))
        {
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
                array_push($data,$row['perPhoto']);

            }
        }
        else
        {
            echo '<script type="text/javascript"> alert("' . $result . '");</script>';
        }

    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
    }


}
if($loginGrp==="UG004"){

    include('./../../smater.php');

}
elseif($loginGrp==="UG003")

{

    include('./../../emaster.php');
}
else
{
    include('./../../master.php');
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Profile<i class="fa fa-hand-o-left"></i></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <i class="fa fa-info-circle fa-fw"></i>Employee Information
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <img src="./../../files/photos/<?php echo $data[10]?>" alt="Avatar" height="220px" class="img-responsive img-rounded proimg" >
                        </div>
                        <div class="col-lg-4">
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Name:</label>
                                <span><?php echo $data[0];?></span>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Cell No:</label>
                                <span><?php echo $data[1];?></span>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Employee Type:</label>
                                <span><?php echo $data[2];?></span>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Designation:</label>
                                <span><?php echo $data[3];?></span>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Gender:</label>
                                <span><?php echo $data[4];?></span>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Birth Date:</label>
                                <span><?php echo $data[5];?></span>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Join Date:</label>
                                <span><?php echo $data[6];?></span>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Block No:</label>
                                <span><?php echo $data[7];?></span>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Salary:</label>
                                <span><?php echo $data[8];?></span>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Address:</label>
                                <span><?php echo $data[9];?></span>

                            </div>

                        </div>

                    </div>
                </div>

                </div>
            </div>
        </div>
   </div>

<?php include('./../../footer.php'); ?>

