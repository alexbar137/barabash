<?php
session_start();
if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
    $UploadDirectory    = 'images/upload/';
    require_once "models/model_user.php";
    $user = UserModel::read($_SESSION['user_id']);
    $user_name = (string)$user->user_name;
    $dir = $UploadDirectory.$user_name;
    if(!file_exists($dir) && !is_dir($dir)) mkdir($dir);
    
    //check if this is an ajax request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        die();
    }
    
    
    //Is file size is less than allowed size.
    if ($_FILES["FileInput"]["size"] > 5242880) {
        die("File size is too big!");
    }
    
    //allowed file type Server side check
    if (strtolower($_FILES['FileInput']['type']) != 'image/jpeg')
    {
        die('Unsupported File!');
    }

    
    $File_Name = strtolower($_FILES['FileInput']['name']);
    
   
    $NewFileName = $user->user_name.".jpeg"; //new file name
    $Dir_file = $dir."/".$NewFileName;
    
    if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $Dir_file))
        {   $maxsize = 200;      
            $pic = imagecreatefromjpeg($Dir_file);
            $width = imagesx($pic);
            $height = imagesy($pic);
            
            if ($width > $height)
            {
                $new_width = $maxsize;
                $new_height = ($height * $maxsize / $width);
            }
            else
            {
                $new_height = $maxsize;
                $new_width = ($width * $maxsize / $height);
            }
            
            $new_pic = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($new_pic, $pic, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($new_pic, $Dir_file, 50);
            imagedestroy($new_pic);
            imagedestroy($pic);
            
            echo "<img src='".$Dir_file."' />";
            die();
    }else{
        die('Еrror uploading file!');
    }
    
}
else
{
    die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}