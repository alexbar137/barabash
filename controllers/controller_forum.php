<?php
  class Forum extends Controller {
   public function __construct() {
    parent::__construct();
   }
   
    public function forum() {
      $this->auth_model->protected_section();
      $this->view->render('forum/view_forum', 'Форум');
    }
   
   public function default_method() {
       $this->forum();
   }
}
?>