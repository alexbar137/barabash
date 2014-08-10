<?php
    class Auth extends Controller {
        public function __construct() {
            parent::__construct();

        }
           
        public function login() {
            $this->view->render('auth/view_login');
        }
        
        public function default_method() {
            $this->view->render('auth/view_login');
        }
       
    }
?>