<?php

    class Model_Message extends Model {
        private $db;

        public function __construct() {
        	require_once "models/model_db.php";
            $this->db = new DbModel();
        }
        
        //Return all messages
        //sent to the logged in user
        public function incoming() {
        	$where = array 
            (
            	'to_name'=>$_SESSION['user_id'],
            );
            
            return $this->all($where);           
        }
        
        public function outcoming() {
        	$where = array 
            (
            	'from_name'=>$_SESSION['user_id'],
            );
            
            return $this->all($where);  
        }
        
        public function all($where) {
        	$output['messages'] = $this->db->select('messages', 'ALL', $where);
            if(empty($output['messages'])) return "Нет сообщений";
            
			for($i=0; $i<count($output['messages']); $i++)
            {
            	//Shortened subject
            	$output['messages'][$i]->subject = (strlen($output['messages'][$i]->subject) > 50) ? substr($output['messages'][$i]->subject, 0, 30)."..." : $output['messages'][$i]->subject;
                
            	//Shorted boby text
            	$output['messages'][$i]->body = (strlen($output['messages'][$i]->body) > 75) ? substr($output['messages'][$i]->body, 0, 75)."..." : $output['messages'][$i]->body;
                
                //Format time and date
                $output['messages'][$i]->send_date = date('d.m.Y', strtotime($output['messages'][$i]->time));
                $output['messages'][$i]->send_time = date('H:i', strtotime($output['messages'][$i]->time));
                
            }
            
            $output['users'] = array();
            foreach($output['messages'] as $message)
            {
                $output['users'] = $this->get_users($message, $output['users']);
            }
            return $output;
        }
        
        //Select particular message and all its replies
        private function show_select($message_id, $nesting = 0) {
        	$tmp = $this->db->select('messages', 'ALL', $message_id);
            $output['message'] = $tmp[0];
            
            if(($nesting == 0) && ($output['message']->in_reply_to != 0))
            {
            	return $this->show_select($output['message']->in_reply_to);
            }
            
            //Format time and date
            $output['message']->send_date = date('d.m.Y', strtotime($output['message']->time));
            $output['message']->send_time = date('H:i', strtotime($output['message']->time));
            
            //Add line breaks
            $output['message']->body = nl2br($output['message']->body);
            
            //Track message nesting level (<4)
            if($nesting < 4)
            {
            	$output['nesting'] = $nesting++;
            }
            else
            {
            	$output['nesting'] = $nesting;
            }
            
            
            $replies_where = array 
                (
                    'in_reply_to'=>$message_id
                );
            $output['replies'] = $this->db->select('messages', 'id', $replies_where);
            
            //Get all replies and their replies
            if(!empty($output['replies']))
            {	
            	foreach($output['replies'] as $key=>$message)
                {
                	$output['replies'][$key] = $this->show_select($message->id, $nesting);
                }
            }
            
            $nesting--;
            return $output;           
        }
        
                
        public function show($message_id) {
        	$output['messages'] = $this->show_select($message_id);
        	$output['users'] = $this->get_users($output['messages']);
            
            //Get replies to the message
            $output = $this->replies($output['messages'], $output['users']);
            return $output;
        }
        

        
        //Add unique user info to the message array
        private function get_users($input, $users = array()) {
        	require_once 'models/model_user.php';
            $user = new UserModel();
            
            if(is_array($input))
            {
            	$id_to = $input['message']->to_name;
                $id_from = $input['message']->from_name;
            }
            else
            {
            	$id_to = $input->to_name;
                $id_from = $input->from_name;
            }
            
            if(!array_key_exists($id_to, $users))
            {
                $users[$id_to] = $user->read($id_to);
            }
                	
            
            if(!array_key_exists($id_from, $users))
            {
                $users[$id_from] = $user->read($id_from);
            }
            
            if(is_array($input) && !empty($input['replies']))
                {
                	foreach($input['replies'] as $reply)
                    {
                    	$users = $this->get_users($reply, $users);
                    }
                    
                }
            
            return $users;
        }
        
        
        //Prepare list of formatted messages for the view
        public function format_all($input) {
        	
            $messages = $input['messages'];
            $users = $input['users'];
            $output = "";
            
            for($i = (count($messages) - 1); $i>=0; $i--)
            {
            	$message = $messages[$i];
            	$template = file_get_contents(INCURL.'/views/message/view_message_all.php');
                $to_id = $message->to_name;
                $recepient = $users[$to_id];
                $from_id = $message->from_name;
                $sender = $users[$from_id];
                $placeholders = array
                (
                	'%%MESSAGE_LINK%%' => URL."/message/show/".$message->id,
                    '%%SENDER_PROFILE%%' => URL."/user/show/".$from_id,
                    '%%SENDER_IMAGE%%' => $sender->small_image,
                    '%%SENDER_NAME%%' => $sender->name,
                    '%%RECEIVER_PROFILE%%' => URL."/user/show/".$recepient->id,
                	'%%RECEIVER_NAME%%' => $recepient->name,
                    '%%SUBJECT%%' => $message->subject,
                    '%%SEND_DATE%%' => $message->send_date,
                    '%%SEND_TIME%%' => $message->send_time,
                    '%%BODY%%' => $message->body
                );
                
                $template = str_replace(array_keys($placeholders), array_values($placeholders), $template);
                
                $output .= $template;
            }
            return $output;
        }
        
        
        //Dynamically process all message replies
        public function replies($input, $users, $output = "") {
            $message = $input['message'];
            $replies = $input['replies'];
            $nesting = $input['nesting']*55;
            $sender = $users[$message->from_name];
            $receiver = $users[$message->to_name];
            $output .= $this->message($message, $nesting, $sender, $receiver);
            

            if (!empty($replies))
            {
                foreach($replies as $reply)
                {
                    $output = $this->replies($reply, $users, $output);
                }
                
                return $output;
            }

            return $output;
        }
        
        //Format a single message
        public function message($message, $nesting, $sender, $receiver) {
        	$template = file_get_contents(INCURL.'/views/message/view_message_show.php');
            $placeholders = array
            (
                '%%SENDER_PROFILE%%' => URL."/user/show/".$sender->id,
                '%%SENDER_ID%%' => $sender->id,
                '%%RECEIVER_PROFILE%%' => URL."/user/show/".$receiver->id,
                '%%RECEIVER_NAME%%' => $receiver->name,
                '%%NESTING%%' => $nesting,
                '%%MESSAGE_ID%%' => $message->id,
                '%%SENDER_IMAGE%%' => $sender->small_image,
                '%%SENDER_NAME%%' => $sender->name,
                '%%SUBJECT%%' => $message->subject,
                '%%SEND_DATE%%' => $message->send_date,
                '%%SEND_TIME%%' => $message->send_time,
                '%%BODY%%' => $message->body
            );
            
            $template = str_replace(array_keys($placeholders), array_values($placeholders), $template);
            
            //Add reply link
            if($_SESSION['user_id'] != $sender->id)
            {
            	$reply = '<span class="reply-link"><b>Ответить</b></span>';
            	$template = str_replace('%%REPLY%%', $reply, $template);
            }
            else
            {
            	$template = str_replace('%%REPLY%%', '', $template);
            }
            
            //Add edit link
            if($_SESSION['user_id'] == $sender->id)
            {
            	$edit = '<span class="edit-link"><b>Изменить</b></span>';
            	$template = str_replace('%%EDIT%%', $edit, $template);
            }
            else
            {
            	$template = str_replace('%%EDIT%%', '', $template);
            }
            
            return $template;
            
        }
        
        public function add($subject = "", $body = "", $sender = "", $receiver = "", $in_reply_to = 0) {
        	$fields = array 
            (
            	'subject'=>$subject,
                'body'=>$body,
                'from_name'=>$sender,
                'to_name'=>$receiver,
                'in_reply_to'=>$in_reply_to
            );
        	return $this->db->insert('messages', $fields);
        }
        
        public function edit($message_id, $body) {
        	$fields = array
            (
            	'body'=>$body
            );
            $this->db->update('messages', $fields, $message_id);
        }
	}


?>