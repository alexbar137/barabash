<?php
    class Error extends Controller {

        public function __construct($msg) {
            parent::__construct();
            $this->view->msg = $msg;
            $this->view->render('error/view_error');
        }
    }
?>