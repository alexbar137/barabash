<form name="register" onsubmit="return register_validate_data();" action="<?php echo URL;?>/user/register_do" method="POST">
		<table>
			<tr>
				<td colspan="2" align="center"><h3>Регистрация</h3></td>				
			</tr>
			<tr>
				<td>Имя пользователя: </td>
				<td><input type="text" name="user_name" width="10em" style="font-family: sans-serif" onchange="user_exists();"></td>
				<td width="100"></td>
			</tr>
			<tr>
				<td>Пароль: </td>
				<td><input type="password" name="pass" width="10em" style="font-family: sans-serif"></td>
			</tr>
			<tr>
				<td>Подтверждение пароля: </td>
				<td><input type="password" name="confirm_pass" width="10em" style="font-family: sans-serif"></td>
			</tr>
			<tr>
				<td align="center"><input type="submit" name="send" value="Отправить"/></td>
				<td align="center"><a href="index.php">
						<input type="button" name="cancel" value="Отмена"/>
					</a></td>
			</tr>
		</table>
	</form>