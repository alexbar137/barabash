<?php
class View {
    private $title;
    private $auth_text;
    private $display;
    private $categories;
    private $is_auth;
    
    public function __construct() {
    }

    public function set_display($input, $key = 'input') {
        $this->display[$key] = $input;
    }
    
    public function set_auth_text($auth_text) {
        $this->auth_text = $auth_text;
    }
    
    public function set_is_auth($is_auth) {
        $this->is_auth = $is_auth;
    } 
    
    public function set_categories($categories) {
        $this->categories = $categories;
    }

    public function render($name, $title = "") {
        $this->title = $title;
            echo $this->get_template('views/header.php');
            require_once 'views/'.$name.'.php';
            require_once 'views/footer.php';
    }
    
    private function get_template($file_name) {
        $file = file_get_contents($file_name);
        $users = "<li><a href='%%URL%%/user/all'>Пользователи</a></li>";
        if($this->is_auth) 
        {
            $file = str_replace('%%USERS%%', $users, $file);
        }
        else
        {
            $file = str_replace('%%USERS%%', "", $file);
        }
        $file = str_replace('%%TITLE%%', $this->title, $file);
        $file = str_replace('%%URL%%', URL, $file);
        $file = str_replace('%%AUTH_TEXT%%', $this->auth_text, $file);
        $file = str_replace('%%CATEGORIES%%', $this->categories, $file);
        $file = str_replace('%%PREV_PAGE%%', $_SESSION['prev_page'], $file);
        
        
        return $file;
    }
}
?>