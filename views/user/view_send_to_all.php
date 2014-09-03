<h1>Сообщение всем пользователям</h1>



<p>Здравствуйте!</p>
<form action="/barabash/user/send_to_all_do" method="POST">
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
