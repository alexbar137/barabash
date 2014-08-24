<?php
    class Article extends Controller {
        public function __construct() {
            parent::__construct();
            require_once('models/model_article.php');
            $this->article = new Model_Article();
        }
        
        //Load if no method is defined        
        public function default_method() {
            $this->all();
        }
        
        //Add action for article
        public function add() {
            $this->article->add();
            $location = "Location: ".URL."/article/added";
            header($location); 
        }
        
        //Article is added
        public function added() {
            $this->view->render('article/view_added', 'Статья добавлена');
        }
        
        //Create Article page
        public function create() {
            $this->auth_model->protected_section();
            $this->view->render('article/view_create', 'Создание статьи');
        }
        
        //List all articles
        public function all() {
            if(!is_array($this->article->all())) 
            {
                require_once 'controllers/controller_error.php';
                $error = new Error($this->article->all());
            }
            else
            {
            $this->view->set_display($this->article->all());
            $this->view->render('article/view_all', "Новости");
            }
        }
        
        //Show articles from a certain category
        public function category($cat) {
            $this->view->set_display($this->article->category($cat));
            $this->view->set_display($this->article->categories[$cat], 'category');
            $title = "Новости: ".$this->article->categories[$cat];
            $this->view->render('article/view_category', $title);
        }
        
        //Show detailed info from one article
        public function show($id) {
            $article = $this->article->show($id);
            $this->view->set_display($article);
            $this->view->render('article/view_show', $article['title']);
        }
    }
?>