<h1>Сообщение пользователю <?php echo $this->display['input']->user_name;?></h1>



<p>Здравствуйте, <b><?php echo $this->display['input']->name; ?></b>!</p>
<form action="/user/send_do/<?php echo $this->display['id'];?>" method="POST">
    <textarea name="message" cols="50" rows="10" placeholder="Введите сообщение"></textarea>
    <p>С уважением,<br/>администратор сайта</p>
<table>    
    <tr>
        <td>
            <input type="submit" name="Send" value="Отправить" />
        </td>
        <td>
            <a href="<?php echo $_SESSION['prev_page']; ?>"><input type="button" name="Cancel" value="Отмена" /></a>
        </td>
    </tr>
</table>
</form>
