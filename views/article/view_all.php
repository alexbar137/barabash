<table>
    <?php foreach($this->display['input'] as $article): ?>
        <tr>
            <td>
                <tr><a href="<?php echo URL;?>/article/show/<?php echo $article['id'];?>"><h3><?php echo $article['title']; ?></h3></a></tr>
                <tr><sub>Категория: <?php echo $article['category']; ?></sub></tr>
                <tr><p><?php echo $article['short_desc'];?></p></tr>
                <hr>
            </td>
        </tr>
    <?php endforeach; ?>
</table>