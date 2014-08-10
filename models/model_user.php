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
				'age'=>27
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
			'age'=>$age
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
				'age'=>$_SESSION['users'][$user_id]['age']
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
		foreach ($_SESSION['users'] as $key=>$value) {
			if ($value['user_name'] == $user_name) {
				return $key;
			}
		}
		return false;
	}
}
?>