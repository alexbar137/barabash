<form id="login_form" method="POST">
<table>
<tr>
	<td><label for="user_name">Имя пользователя:</label></td>
	<td><input type="text" name="user_name" width="10em" style="font-family: sans-serif"></td>
</tr>
<tr>
	<td><label for="pass">Пароль:</label></td>
	<td><input type="password" name="pass" width="10em" style="font-family: sans-serif"></td>
</tr>
<tr>
	<td colspan=2 id="error_space" align="center" style="color: red; height: 20"></td>
</tr>
<tr>
	<td align="center" onclick="check_login();return false;"><input type="submit" name="send" value="Вход"></td>
	<td align="center"><a href="index.php"><input type="button" name="cancel" value="Отмена"></a></td>
</tr>
<tr>
	<td colspan=2 align="center"><a href="#">Забыли пароль?</a></td>
</tr>
</table>
</form>