<?php
  class Help extends Controller {
   public function __construct() {
    echo "Мы в контроллере HELP";
    parent::__construct();
   }
   
   public function other($arg = false) {
    echo "Мы в методе other контроллера Help";
    echo "Параметры: ".$arg;
    
    require 'models/help_model.php';
    $model = new Help_Model();
    }
   }
 
?>