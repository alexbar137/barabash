<?php

/*Test user.*/

if (!isset($_SESSION['users'])) {

	$temp = array (
				'user_name'=>"first",
				'password'=>md5("pass"),
				'email'=>"email@email.com",
				'first_name'=>"Alex",
				'middle_name'=>"B.",
				'last_name'=>"Dorrow",
				'age'=>27,
                'role' =>1
			);


	$_SESSION['users'] = array();
	array_push($_SESSION['users'], $temp);
}

class UserModel {
		
	private function __construct() {
	}

	public static function create($user_name, $password, $email = "", $first_name = "",
		$middle_name = "", $last_name = "", $age = 0) {
		
		//Prevent overwriting users
		if (self::get_id($user_name) != false) return "Имя пользователя уже существует";
		$temp = array (
			'user_name'=>$user_name,
			//Minimum password protection
			'password'=>md5($password),
			'email'=>$email,
			'first_name'=>$first_name,
			'middle_name'=>$middle_name,
			'last_name'=>$last_name,
			'age'=>$age,
            'role'=>0
		);
		
		array_push($_SESSION['users'], $temp);		
	}
	
	public static function read($user_id = -1) {
		if ($user_id == -1) return "User ID is not set";
		if (array_key_exists($user_id, $_SESSION['users'])) {
			$user_tmp = array (
				'user_name'=>$_SESSION['users'][$user_id]['user_name'],
				'email'=>$_SESSION['users'][$user_id]['email'],
				'first_name'=>$_SESSION['users'][$user_id]['first_name'],
				'middle_name'=>$_SESSION['users'][$user_id]['middle_name'],
				'last_name'=>$_SESSION['users'][$user_id]['last_name'],
				'age'=>$_SESSION['users'][$user_id]['age'],
                'role'=>$_SESSION['users'][$user_id]['role']
				);
			return $user_tmp;
			}
		return "User ID is not found";
	}

	
	public static function update($option, $value, $user_id = -1) {
		if ($user_id == -1) return "User ID is not set";
		if ($option == "user_name") return "User name can't be changed";
		if (array_key_exists($user_id, $_SESSION['users'])) {
				foreach ($_SESSION['users'][$user_id] as $key=>$curr_value) {
					//Prevent creating new properties
					if ($key == $option) {
						$_SESSION['users'][$user_id][$key] = $value;
						return "User property is successfully updated";
					}
				}
				return "This property is not found";
			}
		return "User ID is not found";
	}
	
	public static function delete($user_id) {
		if ($user_id == -1) return "User ID is not set";
		if (array_key_exists($user_id, $_SESSION['users'])) {
				unset($_SESSION['users'][$user_id]);
			}
		return "User ID is not found";
	}
	
	public static function login($user_name, $password) {
		foreach ($_SESSION['users'] as $key=>$value) {
			if ($value['user_name'] == $user_name) {
				if (md5($password) == $value['password']) {
					$_SESSION['user_id'] = $key;
					return true;
				}
				return false;
			}
		}
		return false;
	}
		
	public static function pass_reset($user_id, $new_pass) {
		//Confirm action by email. No implementation yet.
		$security_check = true;
		if ($security_check) {
			$_SESSION['users'][$user_id]['password'] = md5($new_pass);
		}
	}
	
	public static function get_id ($user_name) {
		foreach ($_SESSION['users'] as $key=>$value) 
        {
			if ($value['user_name'] == $user_name) 
            {
				return $key;
			}
		}
		return false;
	}
    
    public static function edit_profile_do() {
    	$user_id = $_SESSION['user_id'];
	
    	UserModel::update('email', $_POST['email'], $user_id);
    	UserModel::update('first_name', $_POST['first_name'], $user_id);
    	UserModel::update('middle_name', $_POST['middle_name'], $user_id);
    	UserModel::update('last_name', $_POST['last_name'], $user_id);
    	UserModel::update('age', $_POST['age'], $user_id);
    	    
        $location = "Location: ".URL."/user/profile/";	
    	header($location);
    }
    
    public static function user_exists() {
        if (isset($_POST['user_name'])) 
        {
    		$input_user_name = htmlspecialchars($_POST['user_name']);
    		if (UserModel::get_id($input_user_name) === false) echo 0;
    		else echo 1;
	    }
	    else echo 0;
    }
    
    public static function register_do() {
        if(isset($_POST['user_name']) && isset($_POST['pass'])) 
        {
    		$user_name = htmlspecialchars($_POST['user_name']);
    		$pass = htmlspecialchars($_POST['pass']);
    		UserModel::create($user_name, $pass);
            $location = "Location: ".URL."/user/registered";
    		header($location);
    	}
    	else 
        {
            $location = "Location: ".URL."/user/not_registered";
    		header($location);
    	}
    }
    
    //List all users
    public static function all() {
        $users = array();
        foreach ($_SESSION['users'] as $user)
        {
            $tmp_user['user_name'] = $user['user_name'];
            $tmp_user['email'] = $user['email'];
            $tmp_user['role'] = $user['role'] == 1 ? "Администратор" : "Пользователь";
            $users[] = $tmp_user;
        }
        return $users;
    }
    
    //Is user an admin?
    public static function is_admin() {
        if(!isset($_SESSION['user_id'])) return false; 
        $user = UserModel::read($_SESSION['user_id']);
        if($user['role'] == 1) return true;
        return false;
    }
}
?>