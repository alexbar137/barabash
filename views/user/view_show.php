<h1>Профиль пользователя <?php echo $this->display['input']->user_name; ?></h1>
<img src="<?php echo $this->display['input']->image; ?>" alt="<?php echo $this->display['input']->user_name; ?>" title="<?php echo $this->display['input']->user_name; ?>"/>
	<table id="profile">
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
			<td>Роль:</td>
			<td><?php
                $role =  $this->display['input']->role == 1 ? "Администратор" : "Пользователь";
                echo $role; 
                ?>
            </td>
		</tr>
		<tr>
        <td colspan="2" align="center">
			<input type="button" onclick="window.location=SITE_URL+'/user/all'" value="К списку пользователей"/>
        </td>
		</tr>
	</table>