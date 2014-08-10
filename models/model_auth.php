<?php
    class AuthModel extends Model {    
        public function __construct() {
            
        }
        
        public function auth_text() {
            if ($this->is_auth()) 
            {
                require_once URL . '/models/model_user.php';
    			$user = UserModel::read($_SESSION['user_id']);
    			if ($user['first_name'] == "") 
                {
    				return "Здравствуйте, <a href='".URL."/user/profile/'>пользователь</a> | <a href='".URL."/auth/logout/'>Выход</a>";
    			} 
                else 
                {
    				return "Здравствуйте, <a href='".URL."/user/profile/'>" . $user['first_name'] . "</a> | <a href='".URL."/auth/logout/'>Выход</a>";
    			}
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
    }
?>