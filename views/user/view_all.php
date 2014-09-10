<h1>Пользователи сайта <?php if($this->display['is_admin']) {?><a href="<?php echo URL; ?>/user/send_to_all">
    <i class="fa fa-envelope-o" style="color: blue" title="Отправить сообщение всем пользователям"></i></a><?php };?></h1>
<table class='bordered_table'>
    <tr class='first_row'>
        <td>ID</td>
        <td>Имя пользователя</td>
        <?php if($this->display['is_admin']) {?><td>Электронная почта</td><?php };?>
        <td>Роль</td>
        <?php if($this->display['is_admin']) {?><td>Действия</td><?php };?>
    </tr>
    <?php
        foreach($this->display['input'] as $user)
        {
    ?>
    
    <tr>
        <td><?php echo $user['id'];?></td>
        <td><a href="<?php echo URL; ?>/user/show/<?php echo $user['id'];?>"><?php echo $user['user_name'];?></a></td>
        <?php if($this->display['is_admin']) {?><td><?php echo $user['email'];?></td><?php };?>
        <td><?php echo $user['role'];?></td>
        <?php if($this->display['is_admin']) {?><td align="center">
            <a href="<?php echo URL; ?>/user/send/<?php echo $user['id'];?>"<i class="fa fa-envelope-o" style="color: blue" title="Отправить сообщение пользователю <?php echo $user['user_name']; ?>"></i></a>
            <a href="<?php echo URL; ?>/user/delete/<?php echo $user['id'];?>"><i class="fa fa-trash-o" style="color: red; padding-left: 5px" title="Удалить пользователя <?php echo $user['user_name']; ?>"></i></a>
        </td><?php };?>
    </tr>
    <?php
        }
    ?>
</table>