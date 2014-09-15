<?php


class UserModel {

	private $db;
		
	public function __construct() {
    	require_once INCURL."/models/model_db.php";
        $this->db = new DbModel();
	}
    

	public function create($user_name, $password, $email, $first_name = "",
		$middle_name = "", $last_name = "", $age = 0) {
		
		//Prevent overwriting users
		if (self::get_id($user_name) != false) return "Имя пользователя уже существует";

    	$user['user_name'] = $user_name;
    	$pass = md5($password);
    	$user['password'] = $pass;
    	$user['email'] = $email;
    	$user['first_name'] = $first_name;
    	$user['middle_name'] = $middle_name;
    	$user['last_name'] = $last_name;
    	$user['age'] = $age;
    	$user['role'] = 0;
        $id = $this->db->insert('users', $user);
        var_dump($id);
        if(!is_int($id)) return "Ошибка подключения к базе данных";
        
        //Send mail to admin
        require_once 'models/model_email.php';
        $email = new Email();
        $email->UserCreated($id);	
	}
	
	public function read($user_id = -1) {
		if ($user_id == -1) return "User ID is not set";
        
        $fields = array ('user_name', 'email', 'first_name', 'middle_name', 'last_name', 
        'age', 'role', 'image');
        
        $user = $this->db->select('users', $fields, $user_id);
        
       //Choose name to address user
       $user[0]->name = ($user[0]->first_name != "") ? $user[0]->first_name : $user[0]->user_name;
       
       //Use standard picture, if user provided no custom image
       if(!isset($user[0]->image) || $user[0]->image == "")
       {
           $user[0]->image = URL."/images/system/user.jpeg";
       }
       
       $user[0]->small_image = substr($user[0]->image, 0, -5)."_small.jpeg";
        
        //User is found
		return $user[0];
	}

	
	public function update(array $values, $user_id = -1) {
		if ($user_id == -1) return "User ID is not set";
        foreach($values as $key=>$value)
        {
			if ($key == "user_name") return "User name can't be changed";
        }
        
        if (!$this->id_exists($user_id)) return "User ID is not found";
        $this->db->update('users', $values, $user_id);
	}
    
    //Check if user with the required id exists in DB
    private function id_exists($id) {	
        $user = $this->db->select('users', 'user_name', $id);
        if(empty($user)) return false;
        return true;
    }
	
    
    //Delete user by id
	public function delete($user_id) 
    {
        if ($user_id == -1) return "User ID is not set";
    	if (!id_exists($user_id)) return "User ID is not found";
        $this->db->delete('users', $user_id);
	}
	
	public function login($user_name, $password) {
    	$fields = array('user_name', 'password', 'id');
        $user = $this->db->select('users', $fields, $user_name, 'user_name');
        if(empty($user)) return false;
	    if($user[0]->password == md5($password)) 
        {
            $_SESSION['user_id'] = (string)$user[0]->id;
            return true;
        }
        return false;
	}
		
	public function pass_reset($user_id, $new_pass) {
		//Confirm action by email. No implementation yet.
		$security_check = true;
		if ($security_check) 
        {
        	$new_data = array();
			$new_data['password'] = md5($new_pass);
            $this->db->update('users', $new_data, $user_id);
         }   
	}
	
    
    //Get id by user_name or email
	public function get_id ($input, $option = "user_name") {
           
        switch($option){
            case "email":
                break;
            case "user_name":
                break;
            default:
                return "You can't use this property to search ID";
        }
        
        $user = $this->db->select('users', 'id', $input, $option);
        if(empty($user)) return false;
		return $user[0]->id;
	}
    
    public function edit_profile_do() {
    	$user_id = $_SESSION['user_id'];
		$new_data = array();
    	$new_data['email'] = $_POST['email'];
    	$new_data['first_name'] = $_POST['first_name'];
    	$new_data['middle_name'] = $_POST['middle_name'];
    	$new_data['last_name'] = $_POST['last_name'];
    	$new_data['age'] = $_POST['age'];
        $this->update($new_data, $user_id);
    	    
        $location = "Location: ".URL."/user/profile/";	
    	header($location);
    }
    
    //User name existence validation for registration
    public function user_exists() {
        if (isset($_POST['user_name'])) 
        {
    		$input_user_name = htmlspecialchars($_POST['user_name']);
    		if ($this->get_id($input_user_name) === false) echo 0;
    		else echo 1;
	    }
	    else echo 0;
    }
    
    //Check if email if occupied for registration
    public function email_exists() {
        if (isset($_POST['email'])) 
        {
    		$input_email = htmlspecialchars($_POST['email']);
    		if ($this->get_id($input_email, 'email') === false) echo 0;
    		else echo 1;
	    }
	    else echo 0;
    }
    
    //Registration logic
    public function register_do() {
        if(isset($_POST['user_name']) && isset($_POST['pass'])) 
        {
    		$user_name = htmlspecialchars($_POST['user_name']);
    		$pass = htmlspecialchars($_POST['pass']);
            $email = htmlspecialchars($_POST['email']);
    		$this->create($user_name, $pass, $email);
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
    public function all() {
    	$fields = array('id', 'user_name', 'email', 'role');
		$users = $this->db->select('users', $fields, 'ALL');
        for($i=0; $i<count($users); $i++)
        {
        	$users[$i]->role = ($users[$i]->role == 1) ? "Администратор" : "Пользователь";
        }
        return $users;
    }
    
    //Is user an admin?
    public function is_admin() {
        if(!isset($_SESSION['user_id'])) return false; 
        $user = $this->read($_SESSION['user_id']);
        if($user->role == 1) return true;
        return false;
    }
    
    //Change user photo
    public function change_photo() {
        if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
        {
            $UploadDirectory    = 'images/upload/';
                       
            
            //check if this is an ajax request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                die();
            }
    
    
            //Is file size is less than allowed size.
            if ($_FILES["FileInput"]["size"] > 5242880) {
                die("File size is too big!");
            }
    
            //allowed file type Server side check
            if (strtolower($_FILES['FileInput']['type']) != 'image/jpeg')
            {
                die('Unsupported File!');
            }
            
            $user = $this->read($_SESSION['user_id']);
            $user_name = (string)$user->user_name;
            $dir = $UploadDirectory.$user_name;
            //Create user's subfolder in 'upload' folder
            if(!file_exists($dir) || !is_dir($dir)) mkdir($dir);
    
            
            $File_Name = strtolower($_FILES['FileInput']['name']);
            
           
            $time = time();
            $NewFileName = $user->user_name."_".$time.".jpeg"; //new file name
            $NewFileName_small = $user->user_name."_".$time."_small.jpeg"; //new small file name
            $Dir_file = $dir."/".$NewFileName;
            $Dir_file_small = $dir."/".$NewFileName_small;
            
            if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $Dir_file))
            {   
                //Resize image to 200px at larger side
                $maxsize = 200;      
                $pic = imagecreatefromjpeg($Dir_file);
                $width = imagesx($pic);
                $height = imagesy($pic);
                
                if ($width > $height)
                {
                    $new_width = $maxsize;
                    $new_height = ($height * $maxsize / $width);
                }
                else
                {
                    $new_height = $maxsize;
                    $new_width = ($width * $maxsize / $height);
                }
                
                $new_pic = imagecreatetruecolor($new_width, $new_height);
                $new_pic_small = imagecreatetruecolor($new_width/2, $new_height/2);
                imagecopyresampled($new_pic, $pic, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagecopyresampled($new_pic_small, $pic, 0, 0, 0, 0, $new_width/2, $new_height/2, $width, $height);
                imagejpeg($new_pic, $Dir_file, 50);
                imagejpeg($new_pic_small, $Dir_file_small, 50);
                imagedestroy($new_pic);
                imagedestroy($new_pic_small);
                imagedestroy($pic);
                
                //Add image path to DB
                $full_path = URL."/".$Dir_file;
                if($user->image == URL."/images/system/user.jpeg")
                {
                	$field = array("image"=>$full_path);
                    $this->update($field, $_SESSION['user_id']);
                }
                else
                {
                    //Remove previous images
                    $image = (string)$user->image;
                    $image_unlink = substr($image, strlen(URL) + 1);
                    $image_unlink_small = substr($image, strlen(URL) + 1, -5)."_small.jpeg";
                    unlink($image_unlink);
                    unlink($image_unlink_small);
                    
                    //Save path to the new image
                	$field = array("image"=>$full_path);
                    $this->update($field, $_SESSION['user_id']);
                }
                
                //Display image
                $result = "<img src='$full_path' alt='$user_name' title='$user_name' />";
                echo $result;
                die();
            }
            else
            {
                die('Еrror uploading file!');
            }
            
        }
        else
        {
            die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
        }
    }
}
?>