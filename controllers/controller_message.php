<?php

    class Message extends Controller {
    	
        private $message;

        public function __construct() {
			parent::__construct();
            require_once INCURL."/models/Model_Message.php";
            $this->message = new Model_Message();
            
        }
        
        public function default_method() {
        	$this->all();
        }
        
        public function all() {
        	$this->auth_model->protected_section();
        	$messages = $this->message->all();
            $this->view->set_display($messages);
            $this->view->render('message/view_all');
        }
        
        public function show($id) {
        	$this->auth_model->protected_section();
        	$messages = $this->message->show($id);
            $this->view->set_display($messages);
            $this->view->render('message/view_show');
        }

    }

?>