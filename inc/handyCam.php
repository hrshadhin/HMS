<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/6/15
 * Time: 2:04 AM
 */

namespace handyCam;


class handyCam
{

    public function  getAppDate($datestr)
    {
        $date = explode('-', $datestr);
        return $date[2].'/'.$date[1].'/'.$date[0];
    }
    public function  parseAppDate($datestr)
    {
        $date = explode('/', $datestr);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }


}