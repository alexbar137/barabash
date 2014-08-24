<?php
    class Controller {
        protected $auth_model;
        protected $view;
        
        public function __construct() {
            require_once INCURL . "/models/model_auth.php";
            require_once INCURL . "/models/model_article.php";
            
            $this->view = new View();
            
            $this->article_model = new Model_Article();
            $categories = $this->article_model->list_categories();
            $this->view->set_categories($categories);
            
            $this->auth_model = new AuthModel();
            $auth_text = $this->auth_model->auth_text();
            
            $this->view->set_auth_text($auth_text);
            
        }
        
        public function default_method() {
            
        }
    }
?>