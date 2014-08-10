<?php
  class Index extends Controller {
   public function __construct() {
    parent::__construct();
   }
   
    public function index() {
      $this->view->set_title("Главная страница");
      $this->view->render('index/view_index');
    }
   
   public function default_method() {
       $this->index();
   }
}
?>