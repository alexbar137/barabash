<h1>Новости из категории "<?php echo $this->display['category'];?>"</h1>
<?php
    if(is_array($this->display['input'])) 
    {
?>
<table>
    <?php
        foreach($this->display['input'] as $key=>$article)
        {
    ?>
    <tr>
        <h3><a href="<?php echo URL;?>/article/show/<?php echo $article['id'];?>"><?php echo $article['title'];?></a></h3>
        <p><?php echo $article['short_desc'];?></p>
        <hr>
    </tr>
    <?php
        }
    }
    else
    {
        echo $this->display['input'];
    }
    ?>
</table>