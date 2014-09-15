<?php

class DbModel extends Model {
    
    private $DBH;
    private $valid_keys = array
        ('users'=>array
            ('id', 'user_name', 'email', 'password', 'first_name', 
             'middle_name', 'last_name', 'age', 'role', 'image'),
         'messages'=>array
         	('id', 'from_name', 'to_name', 'in_reply_to', 'time', 'subject', 'body')
         );
    
    public function __construct() {
        try 
        {  
            $this->DBH = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
            $this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }  
        catch(PDOException $e) 
        {  	
            $this->log_error($e->getMessage());
            return false;
        }
       
    }
    
    //Log PDO errors to file
    private function log_error($error) {
        $filename = INCURL.'/logs/PDO_log.log';
        $timestamp = date('d.m.Y H:i:s');
        file_put_contents($filename, $error."\t".$timestamp."\n", FILE_APPEND);
    }
    
    public function is_valid_key($table, $key) {
    	return (in_array($key, $this->valid_keys[$table]));
    }

   //Build insert statement and send to PDO 
    public function insert($table, array $input) {
        $keys = array ();
        $values = array ();
        $placeholders = array();
        foreach ($input as $key=>$value)
        {	
            //Check if all keys are valid
            if($this->is_valid_key($table, $key))
            {
                $keys[] = $key;
                $values[] = $value;
                //Use named placeholders
                $placeholders[] = ":$key";
            }
            else
            {
                return "Неверное название поля";
            }
            
        }
        $key_string = implode(", ", $keys);
        $placeholder_string = implode(", ", $placeholders);
        $request = "INSERT INTO $table ($key_string) VALUES ($placeholder_string)";
        $req = $this->exec_request($request, $input);
        if($req)
        {
        	return $this->DBH->lastInsertId();
        };
        
    }
    
    //Build update statement and send to PDO
    public function update($table, $input, $id, $id_name = 'id') {
        
        $placeholders =  array();
        foreach ($input as $key=>$value)
        {
            //Check if all keys are valid
            if($this->is_valid_key($table, $key))
            {
                //Use named placeholders
                $placeholders[] = "$key=:$key";
            }
            else
            {
                return "Неверное название поля";
            }
        }
        $placeholder_string = implode(", ", $placeholders);
        //Check if where id is a valid field key
        if($this->is_valid_key($table, $id_name))
        {
            $where = "$id_name=:$id_name";
            $input[$id_name] = $id;
        }
        else
        {
           return "Неверный идентификатор";
        }   
           
        $request = "UPDATE $table SET $placeholder_string WHERE $where";
        return $this->exec_request($request, $input);
    }
    
    //Build delete statement and send to PDO
    public function delete($table, $id, $id_name = 'id') {
    	if($this->is_valid_key($table, $id_name))
        {
        	$where = "$id_name=:$id_name";
            $request = "DELETE FROM $table WHERE $where";
        }
        else
        {
        	return "Неверный идентификатор";
        }
        return $this->exec_request($request, $id);
    }
    
    //Execute prepared SQL statement
    //Accepts array of values or a single value
    public function exec_request($request, $values) {
        if (!is_array($values)) $values = array ($values);
        try
        {
            $STH = $this->DBH->prepare($request);
            $STH->execute($values);
        }
        catch(PDOException $e) 
        {
            $this->log_error($e->getMessage());
            return false;
        }
        
        return $STH;
    }
    
    //Build select statement, send to PDO, and then fetch and parse result to
    //an array of objects
    public function select($table, $fields = "ALL", $id = 'ALL', $id_name = 'id') {
    	if($fields == "ALL") $fields = $this->valid_keys[$table];
    	if(!is_array($fields)) $fields = array($fields);
        $placeholders =  array();
        foreach($fields as $field)
        {
            //Check if all keys are valid
            if(!$this->is_valid_key($table, $field)) return "Неверное название поля";
        }
        $key_string = implode(", ", $fields);
        if (!$this->is_valid_key($table, $id_name))  return "Неверный идентификатор";
        
        if (($id_name == 'id') && ($id == "ALL"))
        {
        	$request = "SELECT $key_string FROM $table";
            $STH = $this->DBH->query($request);
        }
        elseif (is_array($id))
        {
        	$wheres = array();
        	foreach($id as $where=>$value)
            {
            	$wheres[] = "$where = :$where";
            }
            $where_string = implode(" AND ", $wheres);
            $request = "SELECT $key_string FROM $table WHERE $where_string";
            $STH = $this->exec_request($request, $id);
        }
        else
        {
        	$request = "SELECT $key_string FROM $table WHERE $id_name = ?";
            $STH = $this->exec_request($request, $id);
        }
            
        if(empty($STH)) return "Ничего не найдено";
        $STH->setFetchMode(PDO::FETCH_OBJ);
        
        //Parse selected data
        $result = array();
        while($row = $STH->fetch()) 
        {  
            $result[] = $row;
        }
        
        return $result;
    }
    
    
    public function check_select() {
        $fields = array ("user_name", "email", "password");
        var_dump($this->select('users', $fields, 7));
    }
    
    public function __destruct() {
        $this->DBH = null;
    }
}

?>