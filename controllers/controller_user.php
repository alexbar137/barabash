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
        
        //Action for Ajax call. Check if user_name is occupied.
        public function user_exists() {
            UserModel::user_exists();
        }
        
        //Action for Ajax call. Check if email is occupied.
        public function email_exists() {
            UserModel::email_exists();
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
            if(!is_object($user)) 
            {
                $this->not_found();
                return;
            }
            $title = "Пользователь: ".$user->user_name;
            $this->view->set_display($user);
            $this->view->render('user/view_show', $title);
        }
        
        public function not_found() {
            $this->auth_model->protected_admin();
            $this->view->render('user/view_not_found', "Пользователь не найден");
        }
        
        //Delete confirmation page
        public function delete($id = -1) {
            $this->auth_model->protected_admin();
            if($id == -1)
            {
                require_once "controllers/controller_error.php";
                $error = new Error('Не выбран пользователь');
                return;
            }
            
            //Prevent user from deleting themselves
            if($id == $_SESSION['user_id'])
            {
                require_once "controllers/controller_error.php";
                $error = new Error('Вы не можете удалить себя');
                return;
            }
            $user = UserModel::read($id);
            $user_name = $user->user_name;
            $this->view->set_display($user_name, 'user_name');
            $this->view->set_display($id, 'id');
            $this->view->render('user/view_delete', "Удаление пользователя $user_name");
        }
        
        //Delete action
        public function delete_do($id = -1) {
            $this->auth_model->protected_admin();
            if ($id == -1) 
            {
                require_once "controllers/controller_error.php";
                $error = new Error('Не выбран пользователь');
                return;
            }
            if($_SESSION['prev_page'] != "/barabash/user/delete/$id")
            {
                require_once "controllers/controller_error.php";
                $error = new Error('Вероятно, вы попали сюда случайно');
                return;
            }
            UserModel::delete($id);
            $header = "Location: ".URL."/user/all";
            header($header);
        }
        
        //Send mail page
        public function send($id = -1) {
            $this->auth_model->protected_admin();
            if ($id == -1) 
            {
                require_once "controllers/controller_error.php";
                $error = new Error('Не выбран пользователь');
                return;
            } 
            $this->view->set_display(UserModel::read($id));
            $this->view->set_display($id, 'id');
            $this->view->render('user/view_send');
        }
        
        //Send mail action
        public function send_do($id = -1) {
            $this->auth_model->protected_admin();
            if ($id == -1) 
            {
                require_once "controllers/controller_error.php";
                $error = new Error('Не выбран пользователь');
                return;
            }
            
            require_once 'models/model_email.php';
            $email = new Email();
            $email->AdminEmail($id, $_POST['message']);
            $header = 'Location: '.URL.'/user/sent';
            header($header);
        }
        
        //Page for sending email to multiple users
        public function send_to_all() {
            $this->view->render('user/view_send_to_all');
        }
        
        public function send_to_all_do() {
            require_once 'models/model_email.php';
            $email = new Email();
            $email->AdminEmailMult($_POST['message']);
            $header = 'Location: '.URL.'/user/sent';
            header($header);
        }
        
        //Send successful
        public function sent() {
            $this->view->render('user/view_sent');
        }
        
        
    }
?>