<h1>Профиль</h1>
	<form name="user_profile" action="<?php echo URL; ?>/user/edit_profile_do" method="POST" onsubmit="return edit_validate_data();">
	<table id="profile">
		<tr>
			<td>Имя пользователя:</td>
			<td><input name="user_name" type="text" value="<?php echo $this->display['input']->user_name;?>" disabled="true"/></td>
		</tr>
		<tr>
			<td>Адрес электронной почты:</td>
			<td><input name="email" type="text" value="<?php echo $this->display['input']->email; ?>"/></td>
		</tr>
		<tr>
			<td>Имя:</td>
			<td><input name="first_name" type="text" value="<?php echo $this->display['input']->first_name; ?>"/></td>
		</tr>
		<tr>
			<td>Отчество:</td>
			<td><input name="middle_name" type="text" value="<?php echo $this->display['input']->middle_name; ?>"/></td>
		</tr>
		<tr>
			<td>Фамилия:</td>
			<td><input type="text" name="last_name" value="<?php echo $this->display['input']->last_name; ?>"/></td>
		</tr>
		<tr>
			<td>Возраст:</td>
			<td><input type="text" name="age" value="<?php echo $this->display['input']->age; ?>"/></td>
		</tr>
		<tr>
			<td align="center"><input type="submit" value="Отправить"/></td>
			<td align="center"><input type="button" onclick="window.location=SITEURL+'/user/profile'" value="Отмена"/></td>
		</tr>
	</table>
	</form>