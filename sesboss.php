<?php
/**
 * Created by PhpStorm.
 * User: lmx
 * Date: 2/26/2015
 * Time: 2:24 PM
 */

require('./inc/sessionManager.php');
if(isset($_POST['serial']))
{
    $ses = new \sessionManager\sessionManager();
    $ses->start();
    $ses->Set("serial",$_POST['serial']);
    echo "Done";

}
else
{
    echo "Not Done".$_POST['serial'];

}


