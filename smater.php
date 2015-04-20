<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/4/15
 * Time: 2:04 AM
 */
$base_url="http://localhost/hms/";

$ses = new \sessionManager\sessionManager();
//$ses->start();
$name=$ses->Get("name");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $GLOBALS['title'];?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $base_url;?>dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_url;?>dist/css/datepicker.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo $base_url;?>dist/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo $base_url;?>dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo $base_url;?>dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo $base_url;?>dist/css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo $base_url;?>dist/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $base_url;?>dist/css/dataTable.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $base_url;?>dist/css/timepicker.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $base_url;?>dist/css/calendar.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $base_url;?>dist/css/custom_2.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $base_url;?>dist/css/app.css" rel="stylesheet" type="text/css">
    <style>

        #page-wrapper {
            border-left: 1px solid #e7e7e7;
            margin: 0 0 0 0px;
            padding: 0 30px;
            position: inherit;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img alt="HMS" class="pull-left" src="<?php echo $base_url.'site/images/logonav.png'?>"><a class="navbar-brand titlehms" href="<?php echo $base_url.'sdashboard.php'?>">Hostel Management System</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">

            <li>
                <h5 class="titlehms"><?php echo $name?></h5>
            </li>

            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-bars fa-fw"></i> Menu <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="<?php echo $base_url;?>ui/usr/profile.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url.'ui/attendence/view.php'?>"><i class="fa fa-file-text-o fa-fw"></i> Attendence View</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url.'ui/stdpayment/add.php'?>"><i class="fa fa-money fa-fw"></i> Payment Add</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url.'ui/stdpayment/view.php'?>"><i class="fa fa-money fa-fw"></i> Payment view</a>
                        <a href="<?php echo $base_url.'ui/bill/view.php'?>"><i class="fa fa-money fa-fw"></i>Bill view</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $base_url;?>logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->


        <!-- /.navbar-static-side -->
    </nav>
