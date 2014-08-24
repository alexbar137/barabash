<?php
  class Bootstrap {
    //Get url attributes
    public function __construct() {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        
        //If no attributes provided, call index controller
        if(empty($url[0])) 
        {
            require 'controllers/controller_index.php';
            $controller = new Index();
            $controller->index();
            return false;
        }
        
        //Check if required controller exists
        $file = 'controllers/controller_'.$url[0].'.php';
        
        
        //If no, create new error
        if(file_exists($file)) 
        {
            require $file;
        } 
        else 
        {
            require 'controllers/controller_error.php';
            $controller = new Error("Этой страницы не существует");
            return false;
        }
        
        
        //If yes, create required controller (from the required file)
        $controller = new $url[0];
        
        
        //Call required controller's method, if it exists
        if(isset($url[1]))
        {
            if (method_exists($controller, $url[1]))
            {
                isset($url[2]) ? $controller->$url[1]($url[2]) : $controller->$url[1]();
            }
            else
            {
                require 'controllers/controller_error.php';
                $error = new Error("Ошибка! Этой страницы не существует!");
            }
        }
        //If method is not specified, load default method
        else
        {
            $controller->default_method();
        }
    }
  }
?>