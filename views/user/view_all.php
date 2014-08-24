<h1>Пользователи сайта</h1>
<table class='bordered_table'>
    <tr class='first_row'>
        <td>ID</td>
        <td>Имя пользователя</td>
        <td>Электронная почта</td>
        <td>Роль</td>
    </tr>
    <?php
        foreach($this->display['input'] as $key=>$user)
        {
    ?>
    
    <tr>
        <td><?php echo $key;?></td>
        <td><a href="<?php echo URL; ?>/user/show/<?php echo $key;?>"><?php echo $user['user_name'];?></a></td>
        <td><?php echo $user['email'];?></td>
        <td><?php echo $user['role'];?></td>
    </tr>
    <?php
        }
    ?>
</table>