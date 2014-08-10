<?php
    class Controller {
        protected $auth_model;
        protected $view;
        
        public function __construct() {
            require_once INCURL . "/models/model_auth.php";
            $this->auth_model = new AuthModel();
            $auth_text = $this->auth_model->auth_text();
            $this->view = new View();
            $this->view->set_auth_text($auth_text);
        }
        
        public function default_method() {
            
        }
    }
?>