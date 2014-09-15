<?php
	 $maxsize = 100;
     $image = 'images/system/user.jpeg';
        
        if(!empty($image) && $image != "")
        {
        	$pic = imagecreatefromjpeg($image);
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
            $new_path = substr($image, 0, -5)."_small.jpeg";
            echo $new_path."<br>";
            imagejpeg($new_pic, $new_path, 50);
        }
    
?>