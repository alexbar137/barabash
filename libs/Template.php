<?php
    function get_template($file_name) {
        $file = file_get_contents($file_name);
        
        if (preg_match('/%%TITLE%%/', $file))
        {
            preg_replace('/%%TITLE%%/', $this->title, $file);
        }
        
        echo $file;
    }
?>