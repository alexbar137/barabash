<?php
    class Auth extends Controller {
        public function __construct() {
            parent::__construct();

        }
        
        //Login page   
        public function login() {
            $this->view->render('auth/view_login', 'Страница входа');
        }
        
        //Redirect to login page if no method specified
        public function default_method() {
            $this->login();
        }
       
       //Check entered credentials on login page
        public function check_login() {
            echo $this->auth_model->check_login();
        }
        
        //Logout page
        public function logout () {
            $this->view->render('auth/view_logout', 'Страница выхода');
        }
        
        //Logout user and redirect to index
        public function logout_do() {
            $this->auth_model->logout();
           
        }
        
        //Show if user try to access protected page without authentication
        public function not_logged_in() {
            $this->view->render('auth/view_not_logged_in', 'Не выполнен вход');
        }
        
        public function not_admin() {
            $this->view->render('auth/view_not_admin', 'Нет доступа');
        }
        
    }
?>