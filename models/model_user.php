<?php

    /*Test user.*/

    if (!file_exists('data/users.xml')) 
    {
    	$XML = new SimpleXMLElement('<users></users>');
    	$user = $XML->addChild('user');
    	$user->addAttribute('id', 1);
    	$user->addChild('user_name', 'first');
    	$pass = md5("pass");
    	$user->addChild('password', $pass);
    	$user->addChild('email','email@email.com');
    	$user->addChild('first_name','Alex');
    	$user->addChild('middle_name', 'B.');
    	$user->addChild('last_name', 'Dorrow');
    	$user->addChild('age', 27);
    	$user->addChild('role', 1);
    	$XML->asXML('data/users.xml');
    }

class UserModel {
		
	private function __construct() {
	}

	public static function create($user_name, $password, $email = "", $first_name = "",
		$middle_name = "", $last_name = "", $age = 0) {
		
		//Prevent overwriting users
		if (self::get_id($user_name) != false) return "Имя пользователя уже существует";
		
        //If file doesn't exist, create new XML
        if (!file_exists('data/users.xml')) 
        {

        	$XML = new SimpleXMLElement('<users></users>');
            $id = 1;
        }
        
        //Else, load file
        else
        {
            $XML = simplexml_load_file('data/users.xml');
            $last_users = $XML->xpath('//user[last()]');
            $id = $last_users[0]->attributes()->id + 1;
        }
        //Add user
    	$user = $XML->addChild('user');
    	$user->addAttribute('id', $id);
    	$user->addChild('user_name', $user_name);
    	$pass = md5($password);
    	$user->addChild('password', $pass);
    	$user->addChild('email', $email);
    	$user->addChild('first_name',$first_name);
    	$user->addChild('middle_name', $middle_name);
    	$user->addChild('last_name', $last_name);
    	$user->addChild('age', $age);
    	$user->addChild('role', 0);
    	$XML->asXML('data/users.xml');
        
        //Send mail to admin
        require_once 'models/model_email.php';
        $email = new Email();
        $email->UserCreated($id);	
	}
	
	public static function read($user_id = -1) {
		if ($user_id == -1) return "User ID is not set";
        if (!file_exists('data/users.xml')) return "User ID is not found";
        $XML = simplexml_load_file('data/users.xml');
        $user = $XML->xpath("//user[@id=$user_id]");
        if (empty($user)) return "User ID is not found";
        
       //Choose name to address user
       $user[0]->name = ($user[0]->first_name != "") ? $user[0]->first_name : $user[0]->user_name;
        
        //User is found. Remove password from array
		unset($user[0]->password);
		return $user[0];
	}

	
	public static function update($option, $value, $user_id = -1) {
		if ($user_id == -1) return "User ID is not set";
		if ($option == "user_name") return "User name can't be changed";
        
        if (!file_exists('data/users.xml')) return "User ID is not found";
        $XML = simplexml_load_file('data/users.xml');
        $user = $XML->xpath("//user[@id=$user_id]");
        if (empty($user)) return "User ID is not found";
        if (!property_exists($user[0], $option)) return "This property is not found";
        $user[0]->$option = $value;
        $XML->asXML('data/users.xml');
	}
	
    
    //Delete user by id
	public static function delete($user_id) 
    {
        if ($user_id == -1) return "User ID is not set";
    	$XML = simplexml_load_file('data/users.xml');
        $len = $XML->count();
        for($i=0; $i<$len; $i++)
        {
            $id = (string)$XML->user[$i]->attributes()->id;
            if($id == $user_id)
            {
                unset ($XML->user[$i]);
                $XML->asXML('data/users.xml');
                return "User is deleted";
            }
        }
        return "User isn't found";
	}
	
	public static function login($user_name, $password) {
		if(!file_exists('data/users.xml')) return false;
        $XML = simplexml_load_file('data/users.xml');
        $user = $XML->xpath("//user[user_name='$user_name']");
        if(empty($user)) return false;
	    if($user[0]->password == md5($password)) 
        {
            $_SESSION['user_id'] = (string)$user[0]->attributes()->id;
            return true;
        }
        return false;
	}
		
	public static function pass_reset($user_id, $new_pass) {
		//Confirm action by email. No implementation yet.
        $XML = simplexml_load_file('data/users.xml');
        $user = $XML->xpath("//user[@user_id=$user_id");
		$security_check = true;
		if ($security_check) {
			$user[0]->password = md5($new_pass);
		}
        $XML->asXML('data/users.xml');
	}
	
    
    //Get id by user_name or email
	public static function get_id ($input, $option = "user_name") {
		if(!file_exists('data/users.xml')) return false;
        $XML = simplexml_load_file('data/users.xml');
        
        switch($option){
            case "email":
                $user = $XML->xpath("//user[email='$input']");
                break;
            case "user_name":
                $user = $XML->xpath("//user[user_name='$input']");
                break;
            default:
                return "You can't use this property to search ID";
        }
        
        if(empty($user)) return false;
		return $user[0]->attributes()->id;
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
    
    //User existence validation for registration
    public static function user_exists() {
        if (isset($_POST['user_name'])) 
        {
    		$input_user_name = htmlspecialchars($_POST['user_name']);
    		if (UserModel::get_id($input_user_name) === false) echo 0;
    		else echo 1;
	    }
	    else echo 0;
    }
    
    //Check if email if occupied for registration
    public static function email_exists() {
        if (isset($_POST['email'])) 
        {
    		$input_email = htmlspecialchars($_POST['email']);
    		if (UserModel::get_id($input_email, 'email') === false) echo 0;
    		else echo 1;
	    }
	    else echo 0;
    }
    
    //Registration logic
    public static function register_do() {
        if(isset($_POST['user_name']) && isset($_POST['pass'])) 
        {
    		$user_name = htmlspecialchars($_POST['user_name']);
    		$pass = htmlspecialchars($_POST['pass']);
            $email = htmlspecialchars($_POST['email']);
    		UserModel::create($user_name, $pass, $email);
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
        if(!file_exists('data/users.xml')) return false;
        $XML = simplexml_load_file('data/users.xml');
        $len = $XML->count();       
        
        $users = array();
        for($i=0; $i<$len; $i++)
        {
            $user = $XML->user[$i];
            $tmp_user['id'] = (string) $user->attributes()->id;
            $tmp_user['user_name'] = (string) $user->user_name;
            $tmp_user['email'] = (string) $user->email;
            $tmp_user['role'] = (string) $user->role == 1 ? "Администратор" : "Пользователь";
            $users[] = $tmp_user;
        }
        return $users;
    }
    
    //Is user an admin?
    public static function is_admin() {
        if(!isset($_SESSION['user_id'])) return false; 
        $user = UserModel::read($_SESSION['user_id']);
        if($user->role == 1) return true;
        return false;
    }
}
?>