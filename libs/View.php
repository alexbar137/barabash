<?php
class View {
    private $title;
    private $auth_text;
    public function __construct() {
    }

    public function set_title($title) {
        $this->title = $title;
    }
    
    public function set_auth_text($auth_text) {
        $this->auth_text = $auth_text;
    }

    public function render($name, $noInclude = false) {
        if($noInclude == true) 
        {
            require_once 'views/'.$name.'.php';
        } 
        else 
        {
            echo $this->get_template('views/header.php');
            require_once 'views/'.$name.'.php';
            require_once 'views/footer.php';
        }
    }
    
    private function get_template($file_name) {
        $file = file_get_contents($file_name);
        $file = str_replace('%%TITLE%%', $this->title, $file);
        $file = str_replace('%%URL%%', URL, $file);
        $file = str_replace('%%AUTH_TEXT%%', $this->auth_text, $file);
        
        return $file;
    }
}
?>