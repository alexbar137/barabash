<?php
    class Model_Article extends Model {
        
        public $categories;
        
        public function __construct() {
            $this->categories = array(
                'None'=>"Нет",
                'Software'=>"ПО",
                'Hardware'=>"Оборудование",
                'Cloud'=>"Облако",
                'Games'=>"Игры"
            );  
        }

        
        //Adding article to XML
        public function add() {
            //Check if required data is received
            if (!(isset($_POST['article_title']) && isset($_POST['article_category']) && isset($_POST['article_short_desc']) && isset($_POST['article_content'])))
            {
                //If not, display error
                require 'controllers/controller_error.php';
                $error = new Error("Недостаточно данных");
                return;
            }
            
            
            //If XML doesn't exist, create it
            if(!file_exists('data/articles.xml')) 
            {
                $XML = new SimpleXMLElement("<articles></articles>");
                $id = 1;
                
            }
            else 
            {
                //Else load it
                $XML = simplexml_load_file('data/articles.xml');
                $len = $XML->article->count() - 1;
                $id = $XML->article[$len]->attributes()->id + 1;
            }
                
                $article = $XML->addChild("article");
                $article->addAttribute('id', $id);
                $article->addChild("title", $_POST['article_title']);
                $article->addChild("category", $_POST['article_category']);
                $article->addChild("short_desc", $_POST['article_short_desc']);
                $article->addChild("content", nl2br($_POST['article_content']));
                
                //Save file
                $XML->asXML('data/articles.xml');
        }
        
        public function all() {
            if(!file_exists('data/articles.xml')) 
            {
                return "Нет статей";
            }
            $articles = array();
            $XML = simplexml_load_file('data/articles.xml');
            foreach ($XML->article as $article)
            {
                $temp_art['id'] = $article->attributes()->id;
                $temp_art['title'] = $article->title;
                $category = (string)$article->category;
                $temp_art['category'] = $this->categories[$category];
                $temp_art['short_desc'] = $article->short_desc;
                $articles[] = $temp_art;
            }
            return $articles;            
        }
        
        public function list_categories() {
            $result = "";
            foreach ($this->categories as $key=>$category) {
                $result .= "<li><a href='".URL."/article/category/$key'>$category</a></li>";
            }
            
            return $result;
        }
        
        public function category($cat) {
            if(!file_exists('data/articles.xml')) 
            {
                return "Нет статей";
            }
            $articles = array();
            $XML = simplexml_load_file('data/articles.xml');
            foreach ($XML->article as $article)
            {
                if ($article->category == $cat) 
                {
                    $temp_art['id'] = $article->attributes()->id;
                    $temp_art['title'] = $article->title;
                    $temp_art['short_desc'] = $article->short_desc;
                    $articles[] = $temp_art;
                }
                
            }
            if (empty($articles)) return "Нет статей";
            return $articles;
            
        }
        
        public function show($id) {
            $XML = simplexml_load_file('data/articles.xml');
            foreach ($XML->article as $article)
            {
                if ($article->attributes()->id == $id)
                {
                    $show_article['title'] = $article->title;
                    $show_article['content'] = $article->content;
                    return $show_article;
                }
            }
            $show_article['title'] = "Статья не найдена";
            $show_article['content'] = "К сожалению, эта статья не найдена";
            return $show_article;
        }
    }
?>