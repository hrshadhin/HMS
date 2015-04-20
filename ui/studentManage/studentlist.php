
<?php

$GLOBALS['title']="Student-HMS";
$base_url="http://localhost/hms/";
$GLOBALS['output']='';
$GLOBALS['isData']="";
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/handyCam.php');

$ses = new \sessionManager\sessionManager();
$ses->start();
$name=$ses->Get("name");
if($ses->isExpired())
{
    header( 'Location:'.$base_url.'login.php');


}
else
{
    $name=$ses->Get("loginId");
    $msg="";
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg = "true") {
        $handyCam = new \handyCam\handyCam();
        $data = array();
        $result = $db->execDataTable("SELECT * from studentinfo where isActive='Y'");
        $GLOBALS['output']='';
        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['output'].='<div class="table-responsive">
                                <table id="studentList" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Mobile No</th>
                                            <th>Institute</th>
                                             <th>Program</th>
                                            <th>L.Guardian</th>
                                           <th>L.G. Mobile</th>
                                           <th>P.Address</th>
                                           <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($row = mysql_fetch_array($result)) {
                $GLOBALS['isData']="1";
                $GLOBALS['output'] .= "<tr>";

                $GLOBALS['output'] .= "<td>" . $row['name'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['cellNo'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['nameOfInst'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['program'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['localGuardian'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['localGuardianCell'] . "</td>";
                $GLOBALS['output'] .= "<td>" . $row['presentAddress'] . "</td>";
                $GLOBALS['output'] .= "<td><a title='View' class='btn btn-danger btn-circle' href='studentedit.php?id=" . $row['userId'] ."&wtd=view'"."><i class='fa fa-file-o'></i></a><a title='Edit' class='btn btn-success btn-circle' href='studentedit.php?id=" . $row['userId'] ."&wtd=edit'"."><i class='fa fa-pencil'></i></a><a title='Delete' class='btn btn-danger btn-circle' href='studentedit.php?id=" . $row['userId'] ."&wtd=delete'"."><i class='fa fa-trash-o'></i></a></td>";
                $GLOBALS['output'] .= "</tr>";

            }

            $GLOBALS['output'].=  '</tbody>
                                </table>
                            </div>';


        }
        else
        {
            echo '<script type="text/javascript"> alert("' . $result . '");</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
    }



}

?>
<?php include('./../../master.php'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Student List</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Student List
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">


                    <div class="row">
                        <div class="col-lg-12">
                            <hr />
                            <?php if($GLOBALS['isData']=="1"){echo $GLOBALS['output'];}?>
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



        $('#studentList').dataTable();
    });




</script>