<?php
  class Help {
   public function __construct() {
    echo "Мы в контроллере HELP";
   }
   
   public function other($arg = false) {
    echo "Мы в методе other контроллера Help";
    echo "Параметры: ".$arg;
    }
   }
 
?>