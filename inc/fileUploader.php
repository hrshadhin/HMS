<?php
/**
 * Created by PhpStorm.
 * User: troot
 * Date: 1/4/15
 * Time: 4:26 PM
 */

namespace fileUploader;


class fileUploader {

    public  function upload($des,$fileControl,$name)
    {
        $doc_root = $_SERVER["DOCUMENT_ROOT"];
       // var_dump($des);
        //var_dump($name);
        $perPhoto="";
        if((!empty($fileControl)) && ($fileControl['error'] == 0)) {

         //   var_dump($fileControl);

            $filename = basename($fileControl['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            if( exif_imagetype ($fileControl["tmp_name"] ))
            {
                //check file size less than 1MB in Byte
             if ($fileControl["size"] < 1048576){
                //Determine the path to which we want to save this file
                $newname = $doc_root.$des.$name.'.'.$ext;
               //  var_dump($newname);
                //Check if the file with the same name is already exists on the server
                if (!file_exists($newname)) {

                    //Attempt to move the uploaded file to it's new place
                    if ((move_uploaded_file($fileControl['tmp_name'],$newname))) {
                            $perPhoto=$name.'.'.$ext;
                    } else {
                        $perPhoto="Error: A problem occurred during file upload!";
                    }
                }
                 else
                 {
                     unlink($newname);
                     if ((move_uploaded_file($fileControl['tmp_name'],$newname))) {
                         $perPhoto=$name.'.'.$ext;
                     } else {
                         $perPhoto="Error: A problem occurred during file upload!";
                     }
                 }
            } else {
                 $perPhoto ="Error: Only images under 1MB are accepted for upload";
            }
        } else {

            }
    }
        else
        {
            $perPhoto= "Error: No file selected!";
        }
        return $perPhoto;
    }


}