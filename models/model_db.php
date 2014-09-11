<?php

class DbModel extends Model {
    
    private $DBH;
    private $valid_keys = array
        ('users'=>array
            ('user_name', 'email', 'password', 'first_name', 
             'middle_name', 'last_name', 'age', 'role', 'image')
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

   //Build insert statement and send to PDO 
    private function insert($table, array $input) {
        $keys = array ();
        $values = array ();
        foreach ($input as $key=>$value)
        {	
            //Check if all keys are valid
            if(in_array($key, $this->valid_keys[$table]))
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
        $this->exec_request($request, $values);
    }
    
    //Build update statement and send to PDO
    private function update($table, array $input, $id, $id_name = 'id') {
        
        $placeholders =  array();
        foreach ($input as $key=>$value)
        {
            //Check if all keys are valid
            if(in_array($key, $this->valid_keys[$table]))
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
        if(($id_name == "id") || (in_array($id_name, $valid_keys)))
        {
            $where = "$id_name=:$id_name";
            $input[$id_name] = $id;
        }
        else
        {
           return "Неверный идентификатор";
        }   
           
        $request = "UPDATE $table SET $placeholder_string WHERE $where";
        $this->exec_request($request, $input);
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
    public function select($table, array $fields, $id, $id_name = 'id') {
        $placeholders =  array();
        foreach($fields as $field)
        {
            //Check if all keys are valid
            if(!in_array($field, $this->valid_keys[$table])) return "Неверное название поля";
        }
        $key_string = implode(", ", $fields);
        if (!((in_array($id_name, $this->valid_keys[$table])) || ($id_name == 'id')))  return "Неверный идентификатор";
        $request = "SELECT $key_string FROM $table WHERE $id_name = ?";
        $STH = $this->exec_request($request, $id);
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