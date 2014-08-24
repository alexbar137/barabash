<?php
    $XML = simplexml_load_file('data/articles.xml');
    foreach ($XML->article as $article)
    {
        echo $article->title;
        echo "<br>";
    }
    $len = $XML->article->count()-1;
    echo $len;
    echo $XML->article[$len]->title;
 ?>