<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/7/15
 * Time: 7:15 PM
 */

$GLOBALS['title']="Billing-HMS";
$base_url="http://localhost/hms/";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');


$ses = new \sessionManager\sessionManager();
$ses->start();
if($ses->isExpired())
{
header( 'Location:'.$base_url.'login.php');


}
else
{
    $name = $ses->Get("loginId");
    $msg = "";
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    //load student list
    $data = array();
    $result = $db->getData("SELECT userId,name FROM studentinfo  where isActive='Y'");
    $GLOBALS['output'] = '';
    if (false === strpos((string)$result, "Can't")) {
        while ($row = mysql_fetch_array($result)) {
            $GLOBALS['isData'] = "1";
            $GLOBALS['output'] .= '<option value="' . $row['userId'] . '">' . $row['name'] . '</option>';

        }


    } else {
        echo '<script type="text/javascript"> alert("' . $result . '");</script>';
    }

    if(isset($_POST["btnSave"])) {

        $db = new \dbPlayer\dbPlayer();
        $msg = $db->open();

        if ($msg = "true") {
            $billId = $db->getAutoId("BIL");
         //   $handyCam = new \handyCam\handyCam();
            //var_dump($billId);
            $userId = $_POST['person'];

            $rows = sizeof($_POST['type']);
            $count = 0;
            for ($i = 0; $i < $rows; $i++) {
                if ($_POST['type'][$i] !== "" && $_POST['amount'][$i] !== "") {

                    $data = array(
                        'billId' => $billId[1],
                        'type' => $_POST['type'][$i],
                        'amount' => $_POST['amount'][$i],
                        'billTo' => $userId,
                        'billingDate' => date("Y-m-d")
                    );

                    $result = $db->insertData("billing", $data);
                    if ($result >= 0) {
                        $count++;
                    }
                }


            }
            if ($rows == $count) {
                echo '<script type="text/javascript"> alert("Billing added successfull.[' . $count . ']");</script>';
            } else {
                echo '<script type="text/javascript"> alert("' . $result . '");</script>';
            }
            $id = intval($billId[0]) + 1;

            $query = "UPDATE auto_id set number=" . $id . " where prefix='BIL';";
            $result = $db->update($query);


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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Billing Add</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Bill  Information
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form name="bill" action="add.php"  accept-charset="utf-8" method="post" enctype="multipart/form-data">


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Student Name</label>
                                        <select class="form-control" name="person" required="">
                                            <?php echo $GLOBALS['output'];?>

                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-8"></div>
                            <div class="col-lg-4">
                                <div class="form-group pull-right ">
                                    <button type="button" class="btn btn-success" id="btnAddRow"  onClick="addRow('billingTable')"><i class="fa fa-2x fa-plus"></i>Row</button>
                                    <button type="button" class="btn btn-danger" id="btnDeleteRow" onClick="deleteRow('billingTable')" ><i class="fa fa-2x fa-trash"></i>Row</button>
                                </div>

                            </div>

                        </div>
                </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table id="billingTable" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bill Type</th>
                                            <th>Amount</th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                    <tr>
                                        <td><input type="checkbox" name="chk"/></td>
                                        <td> <input type="text" name="type[]" class="form-control" placeholder="Bill Type I.E. establish,paper,meal"/> </td>
                                        <td> <input type="text" name="amount[]" class="form-control" placeholder="Amount"/> </td>
                                    </tr>
                                        <tbody>
                                </table>
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


<SCRIPT language="javascript">
    function addRow(tableID) {

        var table = document.getElementById(tableID);

        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);

        var cell1 = row.insertCell(0);
        var element1 = document.createElement("input");
        element1.type = "checkbox";
        element1.name="chkbox[]";
        cell1.appendChild(element1);



        var cell2 = row.insertCell(1);
        var element2 = document.createElement("input");
        element2.type = "text";
        element2.name = "type[]";
        element2.className ="form-control";
        cell2.appendChild(element2);

        var cell3 = row.insertCell(2);
        var element3 = document.createElement("input");
        element3.type = "text";
        element3.name = "amount[]";
        element3.className ="form-control";
        cell3.appendChild(element3);


    }

    function deleteRow(tableID) {
        try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }



            }
        }catch(e) {
            alert(e);
        }
    }

</SCRIPT>

