<?php
    class User extends Controller {
        
        public function __construct() {
            parent::__construct();
            require_once INCURL."/models/model_user.php";
            
        }
        
        public function default_method() {
            $this->profile();
        }
        
        public function profile() {
            $this->auth_model->protected_section();
            $user = UserModel::read($_SESSION['user_id']);
            $this->view->set_display($user);
            $this->view->render('user/view_profile', 'Профиль');
        }
        
        public function edit_profile() {
            $this->auth_model->protected_section();
            $user = UserModel::read($_SESSION['user_id']);
            $this->view->set_display($user);
            $this->view->render('user/view_edit_profile', 'Редактирование профиля');
        }
        
        public function edit_profile_do() {
            $this->auth_model->protected_section();
            UserModel::edit_profile_do();            
        }
        
        public function register() {
           $this->view->render('user/view_register', 'Создание учетной записи'); 
        }
        
        public function user_exists() {
            UserModel::user_exists();
        }
        
        public function register_do() {
            UserModel::register_do();
        }
        
        public function registered() {
            $this->view->render('user/view_registered', 'Учетная запись создана');
        }
        
        public function not_registered() {
            $this->view->render('user/view_not_registered', 'Учетная запись создана');
        }
        
        public function all(){
            $this->auth_model->protected_section();
            $this->auth_model->protected_admin();
            $this->view->set_display(UserModel::all());
            $this->view->render('user/view_all', 'Пользователи сайта');
        }
        
        public function show($id) {
            $this->auth_model->protected_admin();
            $user = UserModel::read($id);
            if(!is_array($user)) 
            {
                $this->not_found();
                return;
            }
            $title = "Пользователь: ".$user['user_name'];
            $this->view->set_display($user);
            $this->view->render('user/view_show', $title);
        }
        
        public function not_found() {
            $this->view->render('user/view_not_found', "Пользователь не найден");
        }
    }
?>