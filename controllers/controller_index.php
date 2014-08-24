<?php
  class Index extends Controller {
   public function __construct() {
    parent::__construct();
   }
   
    public function index() {
      $this->view->render('index/view_index', 'Главная страница');
    }
   
   public function default_method() {
       $this->index();
   }
}
?>