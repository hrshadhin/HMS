<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/2/15
 * Time: 1:51 PM
 */

namespace sessionManager;


class sessionManager {

    public function Set($key,$value)
    {

        $_SESSION[$key] = $value;
       // $_SESSION['start'] = time();
       // $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
    }
    public function Get($key)
    {

       // session_start();
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        else
        {
            return null;
        }

    }
     public function isExpired()
    {
        //session_start();
        $now = time();
        if ($now > $_SESSION['expire']) {
            session_unset();
            session_destroy();
            return true;
        }
        else
        {
            return false;
        }
    }
    public function remove($key)
    {
        //session_start();
        unset($_SESSION[$key]);
    }
    public function  start()
    {
        session_start();
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

    }



}