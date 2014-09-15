<?php

    class Model_Message extends Model {
        private $db;

        public function __construct() {
        	require_once "models/model_db.php";
            $this->db = new DbModel();
        }
        
        //Return all original messages (not replies)
        //sent to the logged in user
        public function all() {
        	$where = array 
            (
            	'to_name'=>$_SESSION['user_id'],
                'in_reply_to'=>0
            );
        	$output['messages'] = $this->db->select('messages', 'ALL', $where);
            
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
        
                
        
        public function show($message_id) {
        	$output['messages'] = $this->show_select($message_id);
        	$output['users'] = $this->get_users($output['messages']);
            return $output;
        }
        
    }

?>