<?php
$GLOBALS['title']="Dashboard-HMS";
$page_name="DashBoard";
require('inc/sessionManager.php');

$ses = new \sessionManager\sessionManager();
require('inc/dbPlayer.php');
$ses->start();
if($ses->isExpired())
{
    header( 'Location: login.php');


}
elseif($ses->Get("userGroupId")=="UG003")
{
    header( 'Location: edashboard.php');
}
elseif($ses->Get("userGroupId")=="UG002" || $ses->Get("userGroupId")=="UG001")
{
    header( 'Location:dashboard.php');
}
else
{
    $name=$ses->Get("name");
    $userIdf = $ses->Get("userIdLoged");
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["btnAttend"])) {


        if ($msg = "true") {

            $result=$db->getData("SELECT * FROM attendence WHERE userId='".$userIdf."' and date=CURDATE()");
            if(mysql_num_rows($result)<1) {


                $data = array(
                    'userId' => $userIdf,
                    'date' => date("Y-m-d"),
                    'isAbsence' => 'No',
                    'isLeave' => 'No',
                    'remark' => 'Self',


                );
                $result = $db->insertData("attendence", $data);
                //var_dump($result);
                if (is_numeric($result)) {

                    //  $db->close();
                    echo '<script type="text/javascript"> alert("Attendence Added Successfully.");</script>';
                } elseif (strpos($result, 'Duplicate') !== false) {
                    echo '<script type="text/javascript"> alert("Attendence Already Exits for today!"); </script>';

                } else {
                    echo '<script type="text/javascript"> alert("' . $result . '");</script>';
                }
            }else
            {
                echo '<script type="text/javascript"> alert("Attendence Already Exits for today!"); </script>';
            }

        } else {
            echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
        }
    }
}


    if ($msg = "true") {

        $data = array();
        $qery="select case when sum(amount) is NULL THEN 0 ELSE sum(amount) END as totals from deposit where depositDate >= DATE_FORMAT(CURDATE(), '%Y-%m-%d')-interval 1 MONTH and userId='".$userIdf."' UNION ALL SELECT case when sum(noOfMeal) is null THEN 0 ELSE sum(noOfMeal) END as toMeal from meal WHERE DATE(date)=DATE(NOW()) and userId='".$userIdf."' UNION ALL select case when sum(noOfMeal) is null THEN 0 ELSE sum(noOfMeal) end from meal where date >= DATE_FORMAT(CURDATE(), '%Y-%m-%d')-interval 1 MONTH and userId='".$userIdf."'UNION ALL select case when rate is null THEN 0 ELSE rate END from mealrate";
        //  var_dump($qery);
        $result = $db->getData($qery);

        $GLOBALS['totals']=array();
        // $data = array();
        if(false===strpos((string)$result,"Can't"))
        {
            while ($row = mysql_fetch_array($result)) {

                // array_push($data,$row['totals']);
                array_push($GLOBALS['totals'],$row['totals']);
            }
        }
        else
        {
            echo '<script type="text/javascript"> alert("' . $result . '");</script>';
        }
        //var_dump( $GLOBALS['totals']);
        $GLOBALS['data']='';
        $result = $db->getData("SELECT serial,title,description,DATE_FORMAT(createdDate,'%D %M,%Y %h:%i:%s %p') as date FROM notice ORDER BY serial DESC LIMIT 4");

        if(false===strpos((string)$result,"Can't"))
        {

            $GLOBALS['data']=$result;
            //var_dump($GLOBALS['data']);
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
<?php include('./smater.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-6">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Dashboard<i class="fa fa-hand-o-left"></i></h1>
            </div>
            <div class="col-lg-6">
            <form action="sdashboard.php" accept-charset="utf-8" method="post" enctype="multipart/form-data">
                <button class="btn btn-success pull-right margin-to-5" type="submit" name="btnAttend"><i class="fa fa-2x fa-check"></i>Attendence</button>
            </form>
                </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-4x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo   $GLOBALS['totals'][0];?></div>
                            <div>Current Deposit</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-maxcdn fa-4x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo   intval($GLOBALS['totals'][1]);?></div>
                            <div>Today's Meal</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-maxcdn fa-4x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo  intval($GLOBALS['totals'][2]);?></div>
                            <div>Total Meal</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-4x"></i>
                            <div>Rate:<?php echo   $GLOBALS['totals'][3];?></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo  floatval($GLOBALS['totals'][3]*$GLOBALS['totals'][2]);?></div>
                            <div>Total Meal Cost</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-list-alt fa-fw"></i>Notice Board
                </div>
                <div class="panel-body">
                    <div id="accordion" class="panel-group">
                        <?php while ($row = mysql_fetch_array($GLOBALS['data'])) {


                            echo  '<div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#'.$row['serial'].'" data-parent="#accordion" data-toggle="collapse" aria-expanded="false" class="collapsed">'.$row['title'].'&nbsp;['.$row['date'].']</a>';
                            echo         '</h4>
                                </div>
                                <div class="panel-collapse collapse" id="'.$row['serial'].'" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">';
                            echo $row['description'];
                            echo    '</div></div></div>';


                        }
                        ?>
                    </div>
                </div>
                <div class="panel-footer">

                </div>
            </div>
        </div>
        <div class="col-lg-6">

            <div class="custom-calendar-wrap">
                <div id="custom-inner" class="custom-inner">
                    <div class="custom-header clearfix">
                        <nav>
                            <span id="custom-prev" class="custom-prev"></span>
                            <span id="custom-next" class="custom-next"></span>
                        </nav>
                        <h2 id="custom-month" class="custom-month"></h2>
                        <h2 id="custom-year" class="custom-year"></h2>
                    </div>
                    <div id="calendar" class="fc-calendar-container"></div>

                </div>
            </div>


        </div>
    </div>

</div>
<!-- /#page-wrapper -->

<?php include('./footer.php'); ?>
<script type="text/javascript">
    $(function() {

        var transEndEventNames = {
                'WebkitTransition' : 'webkitTransitionEnd',
                'MozTransition' : 'transitionend',
                'OTransition' : 'oTransitionEnd',
                'msTransition' : 'MSTransitionEnd',
                'transition' : 'transitionend'
            },
            transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
            $wrapper = $( '#custom-inner' ),
            $calendar = $( '#calendar' ),
            cal = $calendar.calendario( {
                onDayClick : function( $el, $contentEl, dateProperties ) {

                    if( $contentEl.length > 0 ) {
                        showEvents( $contentEl, dateProperties );
                    }

                },
                caldata : codropsEvents,
                displayWeekAbbr : true
            } ),
            $month = $( '#custom-month' ).html( cal.getMonthName() ),
            $year = $( '#custom-year' ).html( cal.getYear() );

        $( '#custom-next' ).on( 'click', function() {
            cal.gotoNextMonth( updateMonthYear );
        } );
        $( '#custom-prev' ).on( 'click', function() {
            cal.gotoPreviousMonth( updateMonthYear );
        } );

        function updateMonthYear() {
            $month.html( cal.getMonthName() );
            $year.html( cal.getYear() );
        }

        // just an example..
        function showEvents( $contentEl, dateProperties ) {

            hideEvents();

            var $events = $( '<div id="custom-content-reveal" class="custom-content-reveal"><h4>Events for ' + dateProperties.monthname + ' ' + dateProperties.day + ', ' + dateProperties.year + '</h4></div>' ),
                $close = $( '<span class="custom-content-close"></span>' ).on( 'click', hideEvents );

            $events.append( $contentEl.html() , $close ).insertAfter( $wrapper );

            setTimeout( function() {
                $events.css( 'top', '0%' );
            }, 25 );

        }
        function hideEvents() {

            var $events = $( '#custom-content-reveal' );
            if( $events.length > 0 ) {

                $events.css( 'top', '100%' );
                Modernizr.csstransitions ? $events.on( transEndEventName, function() { $( this ).remove(); } ) : $events.remove();

            }

        }

    });
</script>
