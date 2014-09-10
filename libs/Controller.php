<?php 
    class Controller {
        
        protected $auth_model;
        protected $view;
        
        public function __construct() {
            
            //Track last visited page
            if(!isset($_SESSION['prev_page'])) $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
            $check = substr($_SERVER['REQUEST_URI'], -3);
            if(isset($_SESSION['cur_page']) && ($_SESSION['cur_page'] != $_SERVER['REQUEST_URI']) && 
               ($check != "_do") && !strpos($_SERVER['REQUEST_URI'], "favicon"))
            {
                $_SESSION['prev_page'] = $_SESSION['cur_page'];
                
            } 
            $_SESSION['cur_page'] = $_SERVER['REQUEST_URI'];
            
            require_once INCURL . "/models/model_auth.php";
            require_once INCURL . "/models/model_article.php";
            
            $this->view = new View();
            
            $this->article_model = new Model_Article();
            $categories = $this->article_model->list_categories();
            $this->view->set_categories($categories);
            
            $this->auth_model = new AuthModel();
            $auth_text = $this->auth_model->auth_text();
            
            $this->view->set_auth_text($auth_text);
            $this->view->set_is_auth($this->auth_model->is_auth());
        }
        
        
        public function default_method() {
            
        }
        
    }
?>