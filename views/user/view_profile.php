<h1>Профиль</h1>
<div id="avatar-wrapper">
<img  id="avatar"  src="<?php echo $this->display['input']->image; ?>" alt="<?php echo $this->display['input']->user_name; ?>" title="<?php echo $this->display['input']->user_name; ?>"/>

<div id="changeAvatar" style="display: none;"><a href="<?php echo URL;?>/user/change_photo">Изменить фотографию</a></div>
</div>

	<table id="profile">
		<tr>
			<td>Имя пользователя:</td>
			<td><?php echo $this->display['input']->user_name; ?></td>
		</tr>
		<tr>
			<td>Адрес электронной почты:</td>
			<td><?php echo $this->display['input']->email; ?></td>
		</tr>
		<tr>
			<td>Имя:</td>
			<td><?php echo $this->display['input']->first_name; ?></td>
		</tr>
		<tr>
			<td>Отчество:</td>
			<td><?php echo $this->display['input']->middle_name; ?></td>
		</tr>
		<tr>
			<td>Фамилия:</td>
			<td><?php echo $this->display['input']->last_name; ?></td>
		</tr>
		<tr>
			<td>Возраст:</td>
			<td><?php echo $this->display['input']->age; ?></td>
		</tr>
		<tr>
			<td align="center"><input type="button" onclick="window.location=SITE_URL+'/user/edit_profile'" value="Изменить"/></td>
			<td align="center"><input type="button" onclick="window.location=SITE_URL" value="На главную"/></td>
		</tr>
	</table>