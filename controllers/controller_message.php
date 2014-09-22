<?php

    class Message extends Controller {
    	
        private $model_message;
        private $user;

        public function __construct() {
			parent::__construct();
            require_once INCURL."/models/Model_Message.php";
            $this->model_message = new Model_Message();
            
        }
        
        public function default_method() {
        	$this->incoming();
        }
        
        public function incoming() {
        	$this->auth_model->protected_section();
        	$messages = $this->model_message->incoming();
            
            //Check if user has any messages
            if(!is_array($messages)) 
            {
            	$this->view->set_display($messages);
            }
            else
            {
            	//If user has messages, format them
            	$formatted_messages = $this->model_message->format_all($messages);
                $this->view->set_display($formatted_messages);
            }
            
            $this->view->render('message/view_all');
        }
        
                
        public function outcoming() {
        	$this->auth_model->protected_section();
        	$messages = $this->model_message->outcoming();
            
            //Check if user has any messages
            if(!is_array($messages)) 
            {
            	$this->view->set_display($messages);
            }
            else
            {
            	//If user has messages, format them
            	$formatted_messages = $this->model_message->format_all($messages);
                $this->view->set_display($formatted_messages);
            }
            
            $this->view->render('message/view_all');
        }
        
        public function show($id) {
        	$this->auth_model->protected_section();
        	$messages = $this->model_message->show($id);
            $this->view->set_display($messages);
            $this->view->render('message/view_show');
        }
        
        public function add_reply() {
        	$this->auth_model->protected_section();
        	require_once 'models/model_user.php';
            $this->user = new UserModel();
        	$subject = $_POST['subject'];
            $body = $_POST['body'];
            $sender = $_SESSION['user_id'];
            $receiver = $_POST['receiver'];
            $in_reply_to = $_POST['in_reply_to'];
			$this->model_message->add($subject, $body, $sender, $receiver, $in_reply_to);

        }
        
        public function edit() {
        	$this->auth_model->protected_section();
        	$message_id = $_POST['message_id'];
            $body = $_POST['body'];
            $this->model_message->edit($message_id, $body);
        }
        
        public function new_message() {
        	$this->auth_model->protected_section();
        	require_once 'models/model_user.php';
            $this->user = new UserModel();
        	$this->view->set_display($this->user->all(), 'users');
            $this->view->render('message/view_new');          
        }
        
        public function add_message() {
        	$this->auth_model->protected_section();
            if ($this->model_message->add($_POST['subject'], $_POST['body'], $_SESSION['user_id'], $_POST['receiver']))
            {
            	header('Location: /message/added');
            }
            else
            {
            	header('Location: /message/not_added');
            }
        }
        
        public function added() {
        	$this->view->render('message/view_added');
        }
        
        public function not_added() {
            $this->view->render('message/view_not_added');
        }

    }

?>