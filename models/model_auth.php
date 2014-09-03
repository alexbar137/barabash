<?php
    class AuthModel extends Model {    
        public function __construct() {
            require_once INCURL . '/models/model_user.php';
        }
        
        //Login text in the header
        public function auth_text() {
            if ($this->is_auth()) 
            {
                $user = UserModel::read($_SESSION['user_id']);
    			return "Здравствуйте, <a href='".URL."/user/profile/'>" . $user->name . "</a> | <a href='".URL."/auth/logout/'>Выход</a>";
    		}
    		else 
            {
    			return "<a href='".URL."/auth/login/'>Вход</a> | <a href='".URL."/user/register/'>Регистрация</a>";
    		}
        }
        
        public function is_auth() {
            if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] !== "")) 
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
        
        //Check credentials action
        public function check_login() {
        	if (isset($_POST['user_name']) && isset($_POST['pass'])) 
            {
        		$input_user_name = htmlspecialchars($_POST['user_name']);
        		$input_pass = htmlspecialchars($_POST['pass']);
        		return UserModel::login($input_user_name, $input_pass);
        	}
        	return false;
        }
        
        
        //Logout action
        public function logout () {   
             unset($_SESSION['user_id']);
        	session_destroy();
            $location = "Location: ".URL;
        	header($location);
        }
        
        //Redirect to not_logged_in if user is not authenticated
        public function protected_section() {
            if (!$this->is_auth()) {
                $location = "Location: ".URL."/auth/not_logged_in";
                header($location);
            }
        }
        
        //Provide admin access only
        public function protected_admin() {
            if(!UserModel::is_admin())
            {
                $location = "Location: ".URL."/auth/not_admin";
                header($location);
            }
        }
    }
?>