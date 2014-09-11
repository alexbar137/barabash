<?php
	class DB extends Controller {
        public function __construct() {
            parent::__construct();
        }
        
        public function check_select() {
            $this->db->check_select();
        }
    }
?>